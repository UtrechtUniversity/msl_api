<?php

namespace App\Mappers\Helpers;

use App\Models\Ckan\DataPublication;
use App\Models\Ckan\EnrichedKeyword;
use App\Models\Ckan\OriginalKeyword;
use App\Models\KeywordSearch;
use App\Models\Vocabulary;
use Exception;

class KeywordHelper
{
    private $vocabularySubDomainMapping = [
        'rockphysics' => 'rock and melt physics',
        'analogue' => 'analogue modelling of geologic processes',
        'paleomagnetism' => 'paleomagnetism',
        'geochemistry' => 'geochemistry',
        'microscopy' => 'microscopy and tomography',
        'testbeds' => 'geo-energy test beds',
    ];

    /**
     * Add original and enriched keywords to the data publication by finding matching keywords using the vocabularies
     */
    public function mapTagsToKeywords(DataPublication $dataPublication): DataPublication
    {
        foreach ($dataPublication->msl_tags as $tag) {
            // retrieve all searchkeywords that match keyword and belong to currently used vocabularies
            $searchKeywords = KeywordSearch::where('search_value', strtolower(trim($tag->msl_tag_string)))->where('version', config('vocabularies.vocabularies_current_version'))->get();

            foreach ($searchKeywords as $searchKeyword) {
                // get the keyword associated with the search keyword
                $keyword = $searchKeyword->keyword;

                // add a orginal keyword to the data publication
                $dataPublication->addOriginalKeyword(
                    new OriginalKeyword(
                        $keyword->value,
                        $keyword->uri,
                        $keyword->vocabulary->uri
                    )
                );

                // add the msl uri to the tag
                $dataPublication->addUriToTag($tag->msl_tag_string, $keyword->uri);

                // get the full hierarchy of keywords to be added as enriched keywords
                foreach ($keyword->getFullHierarchy() as $relatedKeyword) {
                    // create the base enriched keyword
                    $enrichedKeyword = new EnrichedKeyword(
                        $relatedKeyword->value,
                        $relatedKeyword->uri,
                        $relatedKeyword->vocabulary->uri
                    );

                    // add subdomain information if the keyword is not excluded
                    if (! $relatedKeyword->exclude_domain_mapping) {
                        if (isset($this->vocabularySubDomainMapping[$relatedKeyword->vocabulary->name])) {
                            $dataPublication->addSubDomain($this->vocabularySubDomainMapping[$relatedKeyword->vocabulary->name], false);
                            $enrichedKeyword->msl_enriched_keyword_associated_subdomains = [$this->vocabularySubDomainMapping[$keyword->vocabulary->name]];
                        }
                    }

                    // setup the location of the enriched keyword match, keyword is a direct or parent match
                    if ($relatedKeyword->uri == $keyword->uri) {
                        $enrichedKeyword->msl_enriched_keyword_match_locations = ['keyword'];
                    } else {
                        $enrichedKeyword->msl_enriched_keyword_match_locations = ['parent'];
                        $enrichedKeyword->msl_enriched_keyword_match_child_uris = [$keyword->uri];
                    }

                    $dataPublication->addEnrichedKeyword($enrichedKeyword);
                }
            }
        }

        return $dataPublication;
    }

    /**
     * Locates keyword matches within a given field of a datapublication. Keywords are added to the data publication on matches and an annotated version of the text
     * is stored in the given property. A specific relation can be supplied to store as the keyword source relation if the relation type is not parental.
     */
    public function mapTextToKeywordsAnnotated(DataPublication $dataPublication, string $sourceProperty, string $annotatedProperty, string $sourceRelation): DataPublication
    {
        // check if the properties provided exist
        if (! (property_exists($dataPublication, $sourceProperty) && property_exists($dataPublication, $annotatedProperty))) {
            throw new Exception('invalid properties provided');
        }

        // skip processing if no information is present
        if (strlen($dataPublication->{$sourceProperty}) < 2) {
            return $dataPublication;
        }

        // retrieve all searchkeywords that match keyword and belong to currently used vocabularies
        $searchKeywords = KeywordSearch::where('exclude_abstract_mapping', false)->where('version', config('vocabularies.vocabularies_current_version'))->get();

        $dataPublication->{$annotatedProperty} = $dataPublication->{$sourceProperty};

        $combinedMatches = [];

        foreach ($searchKeywords as $searchKeyword) {
            if ($searchKeyword->search_value !== '') {
                // check if the searchkeyword is present within the text
                $expr = $this->createKeywordSearchRegex($searchKeyword->search_value);
                if (preg_match($expr, $dataPublication->{$sourceProperty})) {
                    $keyword = $searchKeyword->keyword;

                    foreach ($keyword->getFullHierarchy() as $relatedKeyword) {
                        // create the base enriched keyword
                        $enrichedKeyword = new EnrichedKeyword(
                            $relatedKeyword->value,
                            $relatedKeyword->uri,
                            $relatedKeyword->vocabulary->uri
                        );

                        // add subdomain information if the keyword is not excluded
                        if (! $relatedKeyword->exclude_domain_mapping) {
                            if (isset($this->vocabularySubDomainMapping[$relatedKeyword->vocabulary->name])) {
                                $dataPublication->addSubDomain($this->vocabularySubDomainMapping[$relatedKeyword->vocabulary->name], false);
                                $enrichedKeyword->msl_enriched_keyword_associated_subdomains = [$this->vocabularySubDomainMapping[$keyword->vocabulary->name]];
                            }
                        }

                        // setup the location of the enriched keyword match, keyword is a direct or parent match
                        if ($relatedKeyword->uri == $keyword->uri) {
                            $enrichedKeyword->msl_enriched_keyword_match_locations = [$sourceRelation];
                        } else {
                            $enrichedKeyword->msl_enriched_keyword_match_locations = ['parent'];
                            $enrichedKeyword->msl_enriched_keyword_match_child_uris = [$keyword->uri];
                        }

                        $dataPublication->addEnrichedKeyword($enrichedKeyword);
                    }

                    $matches = [];
                    // get all matches within the text including offsets
                    preg_match_all($expr, $dataPublication->{$annotatedProperty}, $matches, PREG_OFFSET_CAPTURE);

                    foreach ($matches[0] as $match) {
                        $combinedMatches[] = [
                            'uri' => [$keyword->uri],
                            'text' => $match[0],
                            'offset' => $match[1],
                            'end' => $match[1] + strlen($match[0]),
                        ];
                    }
                }
            }
        }

        // merge matches
        $combinedMatches = $this->mergeMatches($combinedMatches);

        // remove elements included in greater elements
        $combinedMatches = $this->removeIncludedMatches($combinedMatches);

        // sort merge matches from start to end
        usort($combinedMatches, function ($a, $b) {
            return $a['offset'] <=> $b['offset'];
        });

        // annotate and store text
        $dataPublication->{$annotatedProperty} = $this->annotateText($dataPublication->{$annotatedProperty}, $combinedMatches);

        return $dataPublication;
    }

    /**
     * Return array with keywords found within provided text
     *
     * @param  bool  $domainVocabulariesOnly
     */
    public function extractFromText(string $text, $domainVocabulariesOnly = false): array
    {
        if ($domainVocabulariesOnly) {
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
            if ($searchKeyword->search_value !== '') {
                $expr = $this->createKeywordSearchRegex($searchKeyword->search_value);
                if (preg_match($expr, $text)) {
                    $matchedKeywords[] = $searchKeyword->keyword;
                }
            }
        }

        return $matchedKeywords;
    }

    /**
     * Return regex string used to locate provided searchvalue
     */
    private function createKeywordSearchRegex(string $searchValue): string
    {
        if (str_ends_with($searchValue, ',')) {
            return '/\b'.preg_quote($searchValue, '/').'/i';
        }

        return '/\b'.preg_quote($searchValue, '/').'\b/i';
    }

    /**
     * cleanup provided text by removing non standard characters and maximizing lenght
     */
    private function cleanKeyword(string $string): string
    {
        $keyword = preg_replace('/[^A-Za-z0-9 ]/', '', $string);
        if (strlen($keyword) >= 100) {
            $keyword = substr($keyword, 0, 95);
            $keyword = $keyword.'...';
        }

        return trim($keyword);
    }

    /**
     * Merge array of matches by offsets
     */
    private function mergeMatches(array $matches): array
    {
        $merged = [];

        foreach ($matches as $match) {
            $matched = false;
            foreach ($merged as $mergedKey => $mergedValue) {
                if (($match['offset'] == $mergedValue['offset']) && ($match['end'] == $mergedValue['end'])) {
                    $merged[$mergedKey]['uri'][] = $match['uri'][0];
                    $matched = true;
                    break;
                }
            }

            if (! $matched) {
                $merged[] = $match;
            }
        }

        return $merged;
    }

    /**
     * Remove matches that are included in other matches by provided offsets
     */
    private function removeIncludedMatches(array $matches): array
    {
        $cleanedMatches = [];

        foreach ($matches as $matchValue) {
            $innerMatch = false;
            foreach ($matches as $match) {
                if ((($matchValue['offset'] >= $match['offset']) && ($matchValue['end'] <= $match['end'])) && (($matchValue['offset'] !== $match['offset']) || ($matchValue['end'] !== $match['end']))) {
                    $innerMatch = true;
                    break;
                }
            }

            if (! $innerMatch) {
                $cleanedMatches[] = $matchValue;
            }
        }

        return $cleanedMatches;
    }

    /**
     * Annotate provided text with matched location by adding html span elements
     */
    private function annotateText(string $text, array $matches): string
    {
        if (count($matches) > 0) {
            $offset = 0;
            foreach ($matches as $match) {
                $startTag = "<span data-uris='[";
                $startTagUris = [];
                foreach ($match['uri'] as $uri) {
                    $startTagUris[] = '"'.$uri.'"';
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
