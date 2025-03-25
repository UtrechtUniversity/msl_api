<?php
namespace App\Converters;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class MicroscopyConverter
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
                'subTerms' => $this->getBySheet($spreadsheet, 'Apparatus', 2)
            ],
            [
                'value' => 'Ancillary equipment',
                'level' => 1,
                'hyperlink' => '',
                'vocabUri' => '',
                'uri' => '',
                'synonyms' => [],
                'subTerms' => $this->getBySheet($spreadsheet, 'Ancillary equipment', 2)
            ],
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
                'value' => 'Analyzed feature',
                'level' => 1,
                'hyperlink' => '',
                'vocabUri' => '',
                'uri' => '',
                'synonyms' => [],
                'subTerms' => $this->getBySheet($spreadsheet, 'Analyzed feature', 2)
            ],
            [
                'value' => 'Inferred parameter',
                'level' => 1,
                'hyperlink' => '',
                'vocabUri' => '',
                'uri' => '',
                'synonyms' => [],
                'subTerms' => $this->getBySheet($spreadsheet, 'inferred parameter ', 2)
            ]            
        ];

        $newData = [];
        foreach ($data as $rootNode) {
            switch ($rootNode["value"]) {
                case "Apparatus":
                    $rootNode["subTerms"] = $this->checkRootNode($rootNode, 'C', 'definition-link', $spreadsheet->getSheetByName('Apparatus'));
                    // $rootNode = $this->definitionForRoot($rootNode, 'C', 'definition-link', $spreadsheet->getSheetByName('Apparatus'));
                    $newData [] = $rootNode;
                    break;
                case "Ancillary equipment":
                    $rootNode["subTerms"] = $this->checkRootNode($rootNode, 'C', 'definition-link', $spreadsheet->getSheetByName('Ancillary equipment'));
                    // $rootNode = $this->definitionForRoot($rootNode, 'C', 'definition-link', $spreadsheet->getSheetByName('Ancillary equipment'));
                    $newData [] = $rootNode;
                    break;
                case "Technique":
                    $rootNode["subTerms"] = $this->checkRootNode($rootNode, 'E', 'definition-link', $spreadsheet->getSheetByName('Technique'));
                    // $rootNode = $this->definitionForRoot($rootNode, 'E', 'definition-link', $spreadsheet->getSheetByName('Technique'));
                    $newData [] = $rootNode;
                    break;
                case "Analyzed feature":
                    $rootNode["subTerms"] = $this->checkRootNode($rootNode, 'E', 'definition-link', $spreadsheet->getSheetByName('Analyzed feature'));
                    // $rootNode = $this->definitionForRoot($rootNode, 'E', 'definition-link', $spreadsheet->getSheetByName('Analyzed feature'));
                    $newData [] = $rootNode;
                    break;
                case "Inferred parameter":
                    $rootNode["subTerms"] = $this->checkRootNode($rootNode, 'D', 'definition-link', $spreadsheet->getSheetByName('inferred parameter '));
                    // $rootNode = $this->definitionForRoot($rootNode, 'D', 'definition-link', $spreadsheet->getSheetByName('inferred parameter'));
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
                    $cellIterator = $row->getCellIterator('A', 'B');
                    break;
                
                case 'Ancillary equipment':
                    $cellIterator = $row->getCellIterator('A', 'B');
                    break;
                    
                case 'Technique':
                    $cellIterator = $row->getCellIterator('A', 'D');
                    break;
                
                case 'Analyzed feature':
                    $cellIterator = $row->getCellIterator('A', 'D');
                    break;
                    
                case 'inferred parameter ':
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
    


    private function definitionForRoot($node, $columnToCheck, $entryName, $worksheet){
        // check rootnode itself
        $cellValue = $worksheet->getCell($columnToCheck.$node['rowNr'])->getValue();
        if($cellValue != ''){
            $node[$entryName] = $cellValue;
        }
         return $node;
    }

    private function checkRootNode($node, $columnToCheck, $entryName, $worksheet){
        $newSubNode = [];
        foreach ($node["subTerms"] as $subnode) {
            //recursive
            $subnode = $this->addCellValueToEntry($subnode, $columnToCheck, $entryName, $worksheet);
            $newSubNode [] = $subnode;
        }

        return $newSubNode;
    }

    //recursive
    private function addCellValueToEntry($node, $columnToCheck, $entryName, $worksheet){

        $cellValue = $worksheet->getCell($columnToCheck.$node['rowNr'])->getValue();

        if($cellValue != ''){
            $node[$entryName] = $cellValue;
        }

        if(sizeof($node['subTerms']) > 0){
            $newData = [];

            foreach ($node['subTerms'] as $subNode) {
                $subNode = $this->addCellValueToEntry($subNode, $columnToCheck, $entryName, $worksheet);
                $newData []= $subNode;
            }
            $node['subTerms'] = $newData;
        }

        return $node;
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
            'subTerms' => []
        ];
        
        return $node;
    }
    
}

