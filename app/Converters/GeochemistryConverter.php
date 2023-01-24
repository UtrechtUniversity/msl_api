<?php
namespace App\Converters;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class GeochemistryConverter
{
    
    public function ExcelToJson($filepath)
    {
        $spreadsheet = IOFactory::load($filepath);
        
        $data = [
            [
                'value' => 'Technique',
                'level' => 1,
                'hyperlink' => '',
                'vocabUri' => '',
                'uri' => '',
                'synonyms' => [],
                'subTerms' => $this->getBySheet($spreadsheet, 'Technique', 2)
            ],
            [
                'value' => 'Measured property',
                'level' => 1,
                'hyperlink' => '',
                'vocabUri' => '',
                'uri' => '',
                'synonyms' => [],
                'subTerms' => $this->getBySheet($spreadsheet, 'Measured property #parameter', 2)
            ]
        ];
        
        return json_encode($data, JSON_PRETTY_PRINT);
    }
    
    private function getBySheet($spreadsheet, $sheetName, $baseLevel = 1) {
        $worksheet = $spreadsheet->getSheetByName($sheetName);
        
        $nodes = [];
        
        $counter = 0;
        foreach ($worksheet->getRowIterator(3, $worksheet->getHighestDataRow()) as $row) {
            switch ($sheetName) {
                case 'Technique':
                    $cellIterator = $row->getCellIterator('A', 'C');
                    break;
                    
                case 'Measured property #parameter':
                    $cellIterator = $row->getCellIterator('A', 'C');
                    break;                
            }
            
            $cellIterator->setIterateOnlyExistingCells(false);
            
            foreach ($cellIterator as $cell) {
                if($cell->getValue()) {
                    if($cell->getValue() !== "") {
                        $node = $this->createSimpleNode();
                        
                        $node['value'] = $this->cleanValue($cell->getValue());
                        
                        
                        if($cell->hasHyperlink()) {
                            $node['hyperlink'] = $cell->getHyperlink()->getUrl();
                            $node['uri'] = $this->extractLinkUri($node['hyperlink']);
                            $node['vocabUri'] = $this->extractVocabUri($node['hyperlink']);
                        }
                        
                        $node['level'] = Coordinate::columnIndexFromString($cell->getColumn()) + ($baseLevel - 1);
                        $node['synonyms'] = $this->extractSynonyms($cell->getValue());
                        
                        
                        $nodes[] = $node;
                    }
                }
            }
            $counter++;
        }
        
        $nestedNodes = [];
        for ($i = 0; $i < count($nodes); $i++) {
            if($nodes[$i]['level'] == $baseLevel) {
                $node = $nodes[$i];
                $node['subTerms'] = $this->getChildren($i, $nodes);
                $nestedNodes[] = $node;
            }
        }
        
        
        return $nestedNodes;
    }
    
    //http://cgi.vocabs.ga.gov.au/object?vocab_uri=http://resource.geosciml.org/classifierScheme/cgi/2016.01/simplelithology&uri=http%3A//resource.geosciml.org/classifier/cgi/lithology/igneous_rock
    private function isGovAuUrl($url)
    {
        if(str_contains($url, 'cgi.vocabs.ga.gov.au')) {
            return true;
        }
        
        return false;
    }
    
    private function extractLinkUri($url) 
    {
        if($this->isGovAuUrl($url)) {
            $urlParts = parse_url($url);
            parse_str($urlParts['query'], $queryParts);
            
            if(isset($queryParts['uri'])) {
                return $queryParts['uri'];
            }
        }
        
        return '';
    }
    
    private function extractVocabUri($url) 
    {
        if($this->isGovAuUrl($url)) {
            $urlParts = parse_url($url);
            parse_str($urlParts['query'], $queryParts);
            
            if(isset($queryParts['vocab_uri'])) {
                return $queryParts['vocab_uri'];
            }
        }
        
        return '';        
    }
    
    private function cleanValue($string)
    {        
        if(str_contains($string, '#')) {
            $parts = explode('#', $string);
            return trim($parts[0]);
        }
        
        return trim($string);
    }
    
    private function extractSynonyms($string) 
    {
        $synonyms = [];
        if(str_contains($string, '#')) {
            $parts = explode('#', $string);
            array_shift($parts);
            foreach ($parts as $part) {                
                $synonyms[] = trim($part);
            }
        }
                
        return $synonyms;
    }
    
    private function getChildren($current, $nodes)
    {
        $children = [];
        for($i = $current + 1; $i < count($nodes); $i++) {
            if(($nodes[$i]['level'] - $nodes[$current]['level']) == 1) {
                $node = $nodes[$i];
                $node['subTerms'] = $this->getChildren($i, $nodes);
                $children[] = $node;
            } elseif ($nodes[$i]['level'] == $nodes[$current]['level']) {
                return $children;
            }                                
        }
        return $children;
    }
    
    private function createSimpleNode()
    {
        $node = [
            'value' => '',
            'level' => '',
            'hyperlink' => '',
            'vocabUri' => '',
            'uri' => '',
            'synonyms' => [],
            'subTerms' => []
        ];
        
        return $node;
    }
    
}

