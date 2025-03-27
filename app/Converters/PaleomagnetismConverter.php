<?php
namespace App\Converters;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
require 'fullExtractor.php';

class PaleomagnetismConverter
{
    
    public function ExcelToJson($filepath)
    {
        $spreadsheet = IOFactory::load($filepath);
        
        $data = [
            [
                'value' => 'Apparatus',
                'level' => 1,
                'hyperlink' => '',
                'vocabUri' => '',
                'uri' => '',
                'synonyms' => [],
                'subTerms' => $this->getBySheet($spreadsheet, 'Apparatus', 2),
                "defininition-link" => '',
                "defininition" => ''
            ],
            [
                'value' => 'Environment control',
                'level' => 1,
                'hyperlink' => '',
                'vocabUri' => '',
                'uri' => '',
                'synonyms' => [],
                'subTerms' => $this->getBySheet($spreadsheet, 'Environment control', 2),
                "defininition-link" => '',
                "defininition" => ''
            ],
            [
                'value' => 'Measured property',
                'level' => 1,
                'hyperlink' => '',
                'vocabUri' => '',
                'uri' => '',
                'synonyms' => [],
                'subTerms' => $this->getBySheet($spreadsheet, 'Measured property', 2),
                "defininition-link" => '',
                "defininition" => ''
            ],
            [
                'value' => 'Inferred behavior',
                'level' => 1,
                'hyperlink' => '',
                'vocabUri' => '',
                'uri' => '',
                'synonyms' => [],
                'subTerms' => $this->getBySheet($spreadsheet, 'Inferred behavior', 2),
                "defininition-link" => '',
                "defininition" => ''
            ]
        ];

        $newData = [];
        foreach ($data as $rootNode) {
            switch ($rootNode["value"]) {
                case "Apparatus":
                    $rootNode["subTerms"] = checkRootNode($rootNode, 'E', $spreadsheet->getSheetByName('Apparatus'));
                    // $rootNode = $this->definitionForRoot($rootNode, 'C', 'definition-link', $spreadsheet->getSheetByName('Apparatus'));
                    $newData [] = $rootNode;
                    break;
                case "Environment control":
                    $rootNode["subTerms"] = checkRootNode($rootNode, 'D', $spreadsheet->getSheetByName('Environment control'));
                    // $rootNode = $this->definitionForRoot($rootNode, 'C', 'definition-link', $spreadsheet->getSheetByName('Ancillary equipment'));
                    $newData [] = $rootNode;
                    break;
                case "Measured property":
                    $rootNode["subTerms"] = checkRootNode($rootNode, 'D', $spreadsheet->getSheetByName('Measured property'));
                    // $rootNode = $this->definitionForRoot($rootNode, 'E', 'definition-link', $spreadsheet->getSheetByName('Technique'));
                    $newData [] = $rootNode;
                    break;
                case "Inferred behavior":
                    $rootNode["subTerms"] = checkRootNode($rootNode, 'D', $spreadsheet->getSheetByName('Inferred behavior'));
                    // $rootNode = $this->definitionForRoot($rootNode, 'E', 'definition-link', $spreadsheet->getSheetByName('Analyzed feature'));
                    $newData [] = $rootNode;
                    break;
                default:
                    break;
                }        

        }
        $data = $newData;
        
        return json_encode($data, JSON_PRETTY_PRINT);
    }
        
    
    private function getBySheet($spreadsheet, $sheetName, $baseLevel = 1) {
        $worksheet = $spreadsheet->getSheetByName($sheetName);
        
        $nodes = [];
        
        $counter = 0;
        foreach ($worksheet->getRowIterator(3, $worksheet->getHighestDataRow()) as $row) {
            switch ($sheetName) {
                case 'Apparatus':
                    $cellIterator = $row->getCellIterator('A', 'D');
                    break;
                    
                case 'Environment control':
                    $cellIterator = $row->getCellIterator('A', 'C');
                    break;
                    
                case 'Measured property':
                    $cellIterator = $row->getCellIterator('A', 'C');
                    break;
                    
                case 'Inferred behavior':
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
                        $node['rowNr'] = $cell->getRow();
                        
                        
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
            'subTerms' => [],
            "defininition-link" => '',
            "defininition" => ''
        ];
        
        return $node;
    }
    
}

