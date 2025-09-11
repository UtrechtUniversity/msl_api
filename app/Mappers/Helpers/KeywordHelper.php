<?php
namespace App\Mappers\Helpers;

use App\Models\KeywordSearch;
use App\Datasets\BaseDataset;
use App\Models\Vocabulary;


class KeywordHelper
{        
    private $vocabularySubDomainMapping = [
        'rockphysics' => 'rock and melt physics',
        'analogue' => 'analogue modelling of geologic processes',
        'paleomagnetism' => 'paleomagnetism',
        'geochemistry' => 'geochemistry',
        'microscopy' => 'microscopy and tomography',
        'testbeds' => 'geo-energy test beds'
    ];       
    
    
    
    public function mapKeywords(BaseDataset $dataset, $keywords, $extractLastTerm = false, $lastTermDelimiter = '>')
    {                
        foreach ($keywords as $keyword) {
            if($extractLastTerm) {
                if(str_contains($keyword, $lastTermDelimiter)) {
                    $splitKeywords = explode($lastTermDelimiter, $keyword);
                    $keyword = end($splitKeywords);
                }
            }
            
            $keyword = trim($keyword);
            $keywordTag = $this->cleanKeyword($keyword);
            if(strlen($keywordTag) > 1) {
                $dataset->tag_string[] = $keywordTag;
                $dataset->addTag($keywordTag);                
            }
            
            $searchKeywords = KeywordSearch::where('search_value', strtolower($keyword))->where('version', config('vocabularies.vocabularies_current_version'))->get();
            
            if(count($searchKeywords) > 0) {
                foreach ($searchKeywords as $searchKeyword) {
                    $keyword = $searchKeyword->keyword;                
                                       
                    $dataset->addOriginalKeyword($keyword->value, $keyword->uri, $keyword->vocabulary->uri);
                    $dataset->addUriToTag($keywordTag, $keyword->uri);
                    
                    foreach ($keyword->getFullHierarchy() as $enrichedKeyword) {
                        if($enrichedKeyword->exclude_domain_mapping) {
                            if($enrichedKeyword->uri == $keyword->uri) {
                                $dataset->addEnrichedKeyword($enrichedKeyword->value, $enrichedKeyword->uri, $enrichedKeyword->vocabulary->uri, [], ['keyword']);
                            } else {
                                $dataset->addEnrichedKeyword($enrichedKeyword->value, $enrichedKeyword->uri, $enrichedKeyword->vocabulary->uri, [], ['parent'], [$keyword->uri]);
                            }
                        } else {
                            if(isset($this->vocabularySubDomainMapping[$enrichedKeyword->vocabulary->name])) {
                                $dataset->addSubDomain($this->vocabularySubDomainMapping[$enrichedKeyword->vocabulary->name], false);
                                if($enrichedKeyword->uri == $keyword->uri) {
                                    $dataset->addEnrichedKeyword($enrichedKeyword->value, $enrichedKeyword->uri, $enrichedKeyword->vocabulary->uri, [$this->vocabularySubDomainMapping[$keyword->vocabulary->name]], ['keyword']);
                                } else {
                                    $dataset->addEnrichedKeyword($enrichedKeyword->value, $enrichedKeyword->uri, $enrichedKeyword->vocabulary->uri, [$this->vocabularySubDomainMapping[$keyword->vocabulary->name]], ['parent'], [$keyword->uri]);
                                }
                            } else {
                                if($enrichedKeyword->uri == $keyword->uri) {
                                    $dataset->addEnrichedKeyword($enrichedKeyword->value, $enrichedKeyword->uri, $enrichedKeyword->vocabulary->uri, [], ['keyword']);
                                } else {
                                    $dataset->addEnrichedKeyword($enrichedKeyword->value, $enrichedKeyword->uri, $enrichedKeyword->vocabulary->uri, [], ['parent'], [$keyword->uri]);
                                }
                            }
                            
                        }
                    }
                    
                }
            }            
            
        }
                        
        return $dataset;
    }
    
    public function mapKeywordsFromText(BaseDataset $dataset, $text, $source = "") 
    {
        $searchKeywords = KeywordSearch::where('exclude_abstract_mapping', false)->where('version', config('vocabularies.vocabularies_current_version'))->get();
        
        switch ($source) {
            case 'title':
                $dataset->msl_title_annotated = $dataset->title;
                
                $combinedMatches = [];
                
                foreach ($searchKeywords as $searchKeyword) {
                    if($searchKeyword->search_value !== '') {
                        $expr = $this->createKeywordSearchRegex($searchKeyword->search_value);
                        if (preg_match($expr, $text)) {
                            $keyword = $searchKeyword->keyword;
                            
                            //set keyword origin to parent if parent instead of source match
                            
                            foreach ($keyword->getFullHierarchy() as $enrichedKeyword) {
                                $sourceRelation = $source;
                                $childUri = [];
                                if($enrichedKeyword->value !== $keyword->value) {
                                    $sourceRelation = 'parent';
                                    $childUri = [$keyword->uri];
                                }
                                                                                                    
                                if($enrichedKeyword->exclude_domain_mapping) {
                                    $dataset->addEnrichedKeyword($enrichedKeyword->value, $enrichedKeyword->uri, $enrichedKeyword->vocabulary->uri, [], [$sourceRelation], $childUri);
                                } else {
                                    if(isset($this->vocabularySubDomainMapping[$enrichedKeyword->vocabulary->name])) {
                                        $dataset->addSubDomain($this->vocabularySubDomainMapping[$enrichedKeyword->vocabulary->name], false);
                                        $dataset->addEnrichedKeyword($enrichedKeyword->value, $enrichedKeyword->uri, $enrichedKeyword->vocabulary->uri, [$this->vocabularySubDomainMapping[$keyword->vocabulary->name]], [$sourceRelation], $childUri);
                                    } else {
                                        $dataset->addEnrichedKeyword($enrichedKeyword->value, $enrichedKeyword->uri, $enrichedKeyword->vocabulary->uri, [], [$sourceRelation], $childUri);
                                    }                                    
                                }                                
                            }
                                                        
                            $matches = [];
                            preg_match_all($expr, $dataset->msl_title_annotated, $matches, PREG_OFFSET_CAPTURE);
                            
                            foreach ($matches[0] as $match) {
                                $combinedMatches[] = [
                                    'uri' => [$keyword->uri],
                                    'text' => $match[0],
                                    'offset' => $match[1],
                                    'end' => $match[1] + strlen($match[0])
                                ];
                            }
                            
                            
                        }
                                                
                    }
                }
                // merge matches
                $combinedMatches = $this->mergeMatches($combinedMatches);
                
                //remove elements included in greater elements (?)
                $combinedMatches = $this->removeIncludedMatches($combinedMatches);
                
                //sort merge matches from start to end
                usort($combinedMatches, function($a, $b) {
                    return $a['offset'] <=> $b['offset'];
                });
                    
                // annotate text
                $annotatedText = $this->annotateText($dataset->msl_title_annotated, $combinedMatches);
                
                $dataset->msl_title_annotated = $annotatedText;
                break;
                
            case 'notes':
                $dataset->msl_notes_annotated = $dataset->notes;
                
                $combinedMatches = [];
                
                foreach ($searchKeywords as $searchKeyword) {
                    if($searchKeyword->search_value !== '') {
                        $expr = $this->createKeywordSearchRegex($searchKeyword->search_value);
                        if (preg_match($expr, $text)) {
                            $keyword = $searchKeyword->keyword;                                                       
                            
                            foreach ($keyword->getFullHierarchy() as $enrichedKeyword) {
                                $sourceRelation = $source;
                                $childUri = [];
                                if($enrichedKeyword->value !== $keyword->value) {
                                    $sourceRelation = 'parent';
                                    $childUri = [$keyword->uri];
                                }
                                
                                if($enrichedKeyword->exclude_domain_mapping) {
                                    $dataset->addEnrichedKeyword($enrichedKeyword->value, $enrichedKeyword->uri, $enrichedKeyword->vocabulary->uri, [], [$sourceRelation], $childUri);
                                } else {
                                    if(isset($this->vocabularySubDomainMapping[$enrichedKeyword->vocabulary->name])) {
                                        $dataset->addSubDomain($this->vocabularySubDomainMapping[$enrichedKeyword->vocabulary->name], false);
                                        $dataset->addEnrichedKeyword($enrichedKeyword->value, $enrichedKeyword->uri, $enrichedKeyword->vocabulary->uri, [$this->vocabularySubDomainMapping[$keyword->vocabulary->name]], [$sourceRelation], $childUri);
                                    } else {
                                        $dataset->addEnrichedKeyword($enrichedKeyword->value, $enrichedKeyword->uri, $enrichedKeyword->vocabulary->uri, [], [$sourceRelation], $childUri);
                                    }
                                    
                                }
                            }
                            
                            $matches = [];                           
                            preg_match_all($expr, $dataset->msl_notes_annotated, $matches, PREG_OFFSET_CAPTURE);
                            
                            foreach ($matches[0] as $match) {
                                $combinedMatches[] = [
                                    'uri' => [$keyword->uri],
                                    'text' => $match[0],
                                    'offset' => $match[1],
                                    'end' => $match[1] + strlen($match[0])
                                ];
                            }                                                       
                        }                        
                    }                                        
                }
                
                // merge matches
                $combinedMatches = $this->mergeMatches($combinedMatches);
                
                //remove elements included in greater elements (?)
                $combinedMatches = $this->removeIncludedMatches($combinedMatches);
                
                //sort merge matches from start to end
                usort($combinedMatches, function($a, $b) {
                    return $a['offset'] <=> $b['offset'];
                });
                    
                // annotate text
                $annotatedText = $this->annotateText($dataset->msl_notes_annotated, $combinedMatches);
                
                $dataset->msl_notes_annotated = $annotatedText;
                break;
        }
        
                               
        return $dataset;
    }
    
    public function extractFromText($text, $domainVocabulariesOnly = false)
    {        
        if($domainVocabulariesOnly) {
            $vocabularies = Vocabulary::where('version', config('vocabularies.vocabularies_current_version'))->whereIn('name', ['rockphysics', 'analogue', 'paleomagnetism', 'geochemistry', 'microscopy', 'testbeds'])->get();
            $searchKeywords = collect([]);
            foreach ($vocabularies as $vocabulary) {
                $searchKeywords = $searchKeywords->merge($vocabulary->search_keywords()->where('exclude_abstract_mapping', false)->get());
            }                        
        } else {
            $searchKeywords = KeywordSearch::where('exclude_abstract_mapping', false)->where('version', config('vocabularies.vocabularies_current_version'))->get();
        }
        
        $matchedKeywords = [];
        
        foreach ($searchKeywords as $searchKeyword) {
            if($searchKeyword->search_value !== '') {
                $expr = $this->createKeywordSearchRegex($searchKeyword->search_value);
                if (preg_match($expr, $text)) {
                    $matchedKeywords[] = $searchKeyword->keyword;
                }
            }
        }
                               
        return $matchedKeywords;
    }
    
    private function createKeywordSearchRegex($searchValue) {
        if(str_ends_with($searchValue, ',')) {
            return '/\b' . preg_quote($searchValue, '/') . '/i';
        }
        return '/\b' . preg_quote($searchValue, '/') . '\b/i';
    }
    
    private function cleanKeyword($string)
    {
        $keyword = preg_replace("/[^A-Za-z0-9 ]/", '', $string);
        if(strlen($keyword) >= 100) {
            $keyword = substr($keyword, 0, 95);
            $keyword = $keyword . "...";
        }
        
        return trim($keyword);
    }
    
    private function mergeMatches($matches) {
        $merged = [];
        
        foreach ($matches as $match) {
            $matched = false;
            foreach ($merged as $mergedKey => $mergedValue) {
                if(($match['offset'] == $mergedValue['offset']) && ($match['end'] == $mergedValue['end'])) {
                    $merged[$mergedKey]['uri'][] = $match['uri'][0];
                    $matched = true;
                    break;
                }
            }
            
            if(!$matched) {
                $merged[] = $match;
            }
        }
        
        return $merged;
    }
    
    private function removeIncludedMatches($matches) {
        $cleanedMatches = [];
        
        foreach ($matches as $matchValue) {
            $innerMatch = false;
            foreach ($matches as $match) {
                if((($matchValue['offset'] >= $match['offset']) && ($matchValue['end'] <= $match['end'])) && (($matchValue['offset'] !== $match['offset']) || ($matchValue['end'] !== $match['end']))) {
                    $innerMatch = true;
                    break;
                }
            }
            
            if(!$innerMatch) {
                $cleanedMatches[] = $matchValue;
            }
        }
        
        return $cleanedMatches;
    }
    
    private function annotateText($text, $matches) {
        if(count($matches) > 0) {
            $offset = 0;
            foreach ($matches as $match) {
                $startTag = "<span data-uris='[";
                $startTagUris = [];
                foreach ($match['uri'] as $uri) {
                    $startTagUris[] = "\"" . $uri . "\"";
                }
                $startTag .= implode(', ', $startTagUris);
                $startTag .= "]'>";
                $text = substr_replace($text, $startTag, $match['offset'] + $offset, 0);
                $offset = $offset + strlen($startTag);
                
                $endTag = '</span>';
                $text = substr_replace($text, $endTag, $match['end'] + $offset, 0);
                $offset = $offset + strlen($endTag);
            }
        }
        
        return $text;
    }
}
