<?php

namespace App\Converters;

use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;

class GeologicalSettingConverter
{
    public function ExcelToJson($filepath)
    {

        $spreadsheet = IOFactory::load($filepath);
        $worksheet = $spreadsheet->getActiveSheet();

        $nodes = [];

        $counter = 0;
        foreach ($worksheet->getRowIterator(3, $worksheet->getHighestDataRow()) as $row) {
            $cellIterator = $row->getCellIterator('A', 'D');
            $cellIterator->setIterateOnlyExistingCells(false);

            foreach ($cellIterator as $cell) {
                if ($cell->getValue()) {
                    if ($cell->getValue() !== '') {
                        $node = $this->createSimpleNode();

                        $node['value'] = $this->cleanValue($cell->getValue());

                        if ($cell->hasHyperlink()) {
                            $node['hyperlink'] = $cell->getHyperlink()->getUrl();
                            $node['uri'] = $this->extractLinkUri($node['hyperlink']);
                            $node['vocabUri'] = $this->extractVocabUri($node['hyperlink']);
                        }

                        $node['level'] = Coordinate::columnIndexFromString($cell->getColumn());
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
            if ($nodes[$i]['level'] == 1) {
                $node = $nodes[$i];
                $node['subTerms'] = $this->getChildren($i, $nodes);
                $nestedNodes[] = $node;
            }
        }

        $newData = [];
        foreach ($nestedNodes as $rootNode) {
            $rootNode['subTerms'] = $this->checkRootNode($rootNode, 'E', $spreadsheet->getSheetByName('Geological setting'));
            $rootNode = $this->definitionForRoot($rootNode, 'E', $spreadsheet->getSheetByName('Geological setting'));
            $newData[] = $rootNode;
        }
        $nestedNodes = $newData;

        return json_encode($nestedNodes, JSON_PRETTY_PRINT);
    }

    private function definitionForRoot($node, $columnToCheck, $worksheet)
    {
        // check rootnode itself
        $getCell = $worksheet->getCell($columnToCheck.$node['rowNr']);
        $cellValue = $worksheet->getCell($columnToCheck.$node['rowNr'])->getValue();

        if ($cellValue != '') {
            $node = $this->checkupValue($cellValue, $getCell, $node);
        }

        return $node;
    }

    private function checkRootNode($node, $columnToCheck, $worksheet)
    {
        $newSubNode = [];
        foreach ($node['subTerms'] as $subnode) {
            // recursive
            $subnode = $this->addCellValueToEntry($subnode, $columnToCheck, $worksheet);
            $newSubNode[] = $subnode;
        }

        return $newSubNode;
    }

    // recursive
    private function addCellValueToEntry($node, $columnToCheck, $worksheet)
    {
        $getCell = $worksheet->getCell($columnToCheck.$node['rowNr']);
        $cellValue = $worksheet->getCell($columnToCheck.$node['rowNr'])->getValue();

        if ($cellValue != '') {
            $node = $this->checkupValue($cellValue, $getCell, $node);
        }

        if (count($node['subTerms']) > 0) {
            $newData = [];

            foreach ($node['subTerms'] as $subNode) {
                $subNode = $this->addCellValueToEntry($subNode, $columnToCheck, $worksheet);
                $newData[] = $subNode;
            }
            $node['subTerms'] = $newData;
        }

        return $node;
    }

    private function checkupValue($cellValue, $getCell, $node)
    {
        if ($cellValue instanceof \PhpOffice\PhpSpreadsheet\RichText\RichText) {
            $fullstring = '';
            foreach ($cellValue->getRichTextElements() as $richTextElement) {
                $fullstring = $fullstring.$richTextElement->getText();
            }
            $node['defininition'] = $fullstring;
        } else {

            if ($getCell->hasHyperlink()) {
                $node['defininition-link'] = $getCell->getHyperlink()->getUrl();

            } elseif (str_contains(substr($cellValue, 0, 4), 'http')) {

                $node['defininition-link'] = $cellValue;

            } else {
                $node['defininition'] = $cellValue;

            }
        }

        return $node;
    }

    // http://cgi.vocabs.ga.gov.au/object?vocab_uri=http://resource.geosciml.org/classifierScheme/cgi/2016.01/simplelithology&uri=http%3A//resource.geosciml.org/classifier/cgi/lithology/igneous_rock
    private function isGovAuUrl($url)
    {
        if (str_contains($url, 'cgi.vocabs.ga.gov.au')) {
            return true;
        }

        return false;
    }

    private function extractLinkUri($url)
    {
        if ($this->isGovAuUrl($url)) {
            $urlParts = parse_url($url);
            parse_str($urlParts['query'], $queryParts);

            if (isset($queryParts['uri'])) {
                return $queryParts['uri'];
            }
        }

        return '';
    }

    private function extractVocabUri($url)
    {
        if ($this->isGovAuUrl($url)) {
            $urlParts = parse_url($url);
            parse_str($urlParts['query'], $queryParts);

            if (isset($queryParts['vocab_uri'])) {
                return $queryParts['vocab_uri'];
            }
        }

        return '';
    }

    private function cleanValue($string)
    {
        if (str_contains($string, '#')) {
            $parts = explode('#', $string);

            return trim($parts[0]);
        }

        return trim($string);
    }

    private function extractSynonyms($string)
    {
        $synonyms = [];
        if (str_contains($string, '#')) {
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
        for ($i = $current + 1; $i < count($nodes); $i++) {
            if (($nodes[$i]['level'] - $nodes[$current]['level']) == 1) {
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
            'defininition-link' => '',
            'defininition' => '',
        ];

        return $node;
    }
}
