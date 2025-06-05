<?php

namespace App\Converters;

use App\Models\Vocabulary;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;

class VocabularyToJsonConverter
{
    public function excelToJson($filepath, $selectedDomain)
    {
        $spreadsheet = IOFactory::load($filepath);

        $dbSheetNameOptions = Vocabulary::where('name', $selectedDomain)->where('version', config('vocabularies.vocabularies_current_version'))->get();

        $dbSheetName = '';
        if (count($dbSheetNameOptions) == 1) {
            $dbSheetName = $dbSheetNameOptions[0]->display_name;
            if (strlen($dbSheetName) > 31) {
                $dbSheetName = substr($dbSheetName, 0, 31); // excel tab name character limit is 31
            }
        } else {
            throw new \Exception('There are multiple or no entries for this domain in the database with this version');
        }

        $worksheet = $spreadsheet->getSheetByName($dbSheetName);

        $data = $this->retrieveData($worksheet);

        return json_encode($data, JSON_PRETTY_PRINT);

    }

    private function retrieveData($worksheet): array
    {
        $allColNames = $this->getAllHeaderStrings($worksheet);

        $lastColumnLetter = $this->getLastColumnAsLetter($allColNames);

        $nodes = [];
        $baseLevel = 1;

        foreach ($worksheet->getRowIterator(2, $worksheet->getHighestDataRow()) as $row) {

            $cellIterator = $row->getCellIterator('A', $worksheet->getHighestDataColumn());
            $cellIterator->setIterateOnlyExistingCells(false);

            $node = $this->createSimpleNode();

            foreach ($cellIterator as $cell) {
                $currentColumn = $cell->getColumn();

                if ($cell->getValue() && $cell->getValue() !== '') {

                    if (in_array($currentColumn, range('A', $lastColumnLetter))) {

                        $node['value'] = $cell->getValue();
                        $node['level'] = Coordinate::columnIndexFromString($cell->getColumn()) + ($baseLevel - 1);
                        $node['rowNr'] = $cell->getRow();

                    } elseif ($currentColumn == $this->checkColumnByName('indicator terms', $allColNames)) {
                        $node['synonyms'] = $this->extractTermsFromString($cell->getValue());
                    } elseif ($currentColumn == $this->checkColumnByName('exclude_domain_mapping', $allColNames)) {
                        if ($cell->getValue() == 'yes') {
                            $node['exclude_domain_mapping'] = 1;
                        } elseif ($cell->getValue() == 'no') {
                            $node['exclude_domain_mapping'] = 0;
                        } else {
                            throw new \Exception('entry is not string "no" or "yes" for term "'.$node['value'].'" in column "'.$currentColumn.'"');
                        }
                    } elseif ($currentColumn == $this->checkColumnByName('uri', $allColNames)) {
                        $node['uri'] = $cell->getValue();
                    } elseif ($currentColumn == $this->checkColumnByName('hyperlink', $allColNames)) {
                        $node['hyperlink'] = $cell->getValue();
                    } elseif ($currentColumn == $this->checkColumnByName('external_uri', $allColNames)) {
                        $node['external_uri'] = $cell->getValue();
                    } elseif ($currentColumn == $this->checkColumnByName('external_vocab_scheme', $allColNames)) {
                        $node['external_vocab_scheme'] = $cell->getValue();
                    } elseif ($currentColumn == $this->checkColumnByName('external_description', $allColNames)) {
                        $node['external_description'] = $cell->getValue();
                    } elseif ($currentColumn == $this->checkColumnByName('extracted_definition', $allColNames)) {
                        $node['extracted_definition'] = $cell->getValue();
                    } elseif ($currentColumn == $this->checkColumnByName('extracted_definition_link', $allColNames)) {
                        $node['extracted_definition_link'] = $cell->getValue();
                    } elseif ($currentColumn == $this->checkColumnByName('terms_exclude_abstract_mapping', $allColNames)) {
                        $node['terms_exclude_abstract_mapping'] = $this->extractTermsFromString($cell->getValue());
                    } elseif ($currentColumn == $this->checkColumnByName('selection_group_1', $allColNames)) {
                        if ($cell->getValue() == 'yes') {
                            $node['selection_group_1'] = 1;
                        } elseif ($cell->getValue() == 'no') {
                            $node['selection_group_1'] = 0;
                        } else {
                            throw new \Exception('entry is not string "no" or "yes" for term "'.$node['value'].'" in column "'.$currentColumn.'"');
                        }
                    } elseif ($currentColumn == $this->checkColumnByName('selection_group_2', $allColNames)) {
                        if ($cell->getValue() == 'yes') {
                            $node['selection_group_2'] = 1;
                        } elseif ($cell->getValue() == 'no') {
                            $node['selection_group_2'] = 0;
                        } else {
                            throw new \Exception('entry is not string "no" or "yes" for term "'.$node['value'].'" in column "'.$currentColumn.'"');
                        }
                    } elseif ($currentColumn == $this->checkColumnByName('exclude_selection_group_1', $allColNames)) {
                        $node['exclude_selection_group_1'] = $this->extractTermsFromString($cell->getValue());
                    } elseif ($currentColumn == $this->checkColumnByName('exclude_selection_group_2', $allColNames)) {
                        $node['exclude_selection_group_2'] = $this->extractTermsFromString($cell->getValue());
                    }

                }
            }

            $nodes[] = $node;
        }

        // nest the nodes
        $nestedNodes = [];
        for ($i = 0; $i < count($nodes); $i++) {
            if ($nodes[$i]['level'] == $baseLevel) {
                $node = $nodes[$i];
                $node['subTerms'] = $this->getChildren($i, $nodes);
                $nestedNodes[] = $node;
            }
        }

        return $nestedNodes;
    }

    private function checkColumnByName($columnName, $allColNames)
    {
        $targetKey = array_search($columnName, $allColNames);

        return $this->getLetterFromNumber($targetKey);
    }

    private function getLetterFromNumber($no)
    {
        // cheap, but works until Z
        $alphabet = range('A', 'Z');

        return $alphabet[$no];
    }

    private function getLastColumnAsLetter($allColNames)
    {
        $all = [];

        foreach ($allColNames as $value) {
            if (is_int($value)) {
                $all[] = $value;
            } else {
                break;
            }
        }

        return $this->getLetterFromNumber(end($all) - 1);
    }

    private function getAllHeaderStrings($worksheet)
    {
        $allcols = [];
        $highestColumn = $worksheet->getHighestDataColumn(); // e.g 'F'
        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

        for ($col = 1; $col <= $highestColumnIndex; $col++) {
            $value = $worksheet->getCell([$col, 1])->getValue();
            $allcols[] = $value;
        }

        return $allcols;
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

    private function extractTermsFromString($string)
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

    private function createSimpleNode()
    {
        $node = [
            'value' => '',
            'level' => '',
            'synonyms' => [],
            'exclude_domain_mapping' => '',
            'uri' => '',
            'hyperlink' => '',
            'external_uri' => '',
            'external_vocab_scheme' => '',
            'external_description' => '',
            'extracted_definition' => '',
            'extracted_definition_link' => '',
            'terms_exclude_abstract_mapping' => [],
            'selection_group_1' => '',
            'selection_group_2' => '',
            'exclude_selection_group_1' => [],
            'exclude_selection_group_2' => [],
            'subTerms' => [],
        ];

        return $node;
    }
}
