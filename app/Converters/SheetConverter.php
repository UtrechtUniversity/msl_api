<?php

namespace App\Converters;

use App\Exports\Vocabs\ExcelSheetInternal;
use App\Models\Vocabulary;
use Hamcrest\Type\IsInteger;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Redirect;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\Relations\HasOne;
use PhpOption\None;

class SheetConverter {

    public function excelToJson($filepath, $selectedDomain){
        $spreadsheet = IOFactory::load($filepath);

        $dbSheetNameOptions = Vocabulary::where('name', $selectedDomain)->where('version', '1.3')->get();

        $dbSheetName = '';
        if(sizeof($dbSheetNameOptions) == 1){
            $dbSheetName = $dbSheetNameOptions[0]->display_name;
            if(strlen($dbSheetName) > 31){
                $dbSheetName = substr($dbSheetName, 0, 31); //excel tab name character limit is 31
            }
        } else {
            // this does not work for some reason
            return redirect()->back()->with('error','There are multiple or no entries for this domain in the database with this version');
        }

        $worksheet = $spreadsheet->getSheetByName($dbSheetName);
        
        $data = $this->retrieveData($worksheet);

        return json_encode($data, JSON_PRETTY_PRINT);

    }

    public function getFileList(){
        return array_keys($this->verifyDomainContent);
    }

    private $verifyDomainContent = array (
        'analogue' => [],
        'geochemistry' => [],
        'geologicalage' => [],
        'geologicalsetting' => [],
        'materials' => [],
        'microscopy' => [],
        'paleomagnetism' => [],
        'porefluids' => [],
        'rockphysics' => ['Apparatus', 'Ancillary equipment', 'Measured property', 'Inferred deformation behavior'],
        'subsurface' => [],
        'testbeds' => [],
    );

    private function retrieveData($worksheet){

        $allColNames =  $this->getAllHeaderStrings($worksheet);

        $lastSynonymCol = $this->getLastSynonymCol($allColNames);

        $nodes = [];
        $counter = 0;
        $baseLevel = 1;


        foreach ($worksheet->getRowIterator(2, $worksheet->getHighestDataRow()) as $row) {
            
            
            $cellIterator = $row->getCellIterator('A', $worksheet->getHighestDataColumn());
            $cellIterator->setIterateOnlyExistingCells(false);

            $node = $this->createSimpleNode();

            foreach ($cellIterator as $cell) {
                $currentColumn = $cell->getColumn();

                if ($cell->getValue() && $cell->getValue() !== '') {

                    if ( in_array($currentColumn, range('A', $lastSynonymCol))) {
                        
                        $node['value'] = $cell->getValue();
                        $node['level'] = Coordinate::columnIndexFromString($cell->getColumn()) + ($baseLevel - 1);
                        $node['rowNr'] = $cell->getRow();
                        
                    }
                    else if($currentColumn == $this->checkColumnByName('indicator terms', $allColNames)){
                        $node['indicator terms'] = $this->extractSynonyms($cell->getValue());
                    }
                    else if($currentColumn == $this->checkColumnByName('uri', $allColNames)){
                        $node['uri'] = $cell->getValue();
                    }
                    else if($currentColumn == $this->checkColumnByName('hyperlink', $allColNames)){
                        $node['hyperlink'] = $cell->getValue();
                    }
                    else if($currentColumn == $this->checkColumnByName('external_uri', $allColNames)){
                        $node['external_uri'] = $cell->getValue();
                    }
                    else if($currentColumn == $this->checkColumnByName('external_vocab_scheme', $allColNames)){
                        $node['external_vocab_scheme'] = $cell->getValue();
                    }
                    else if($currentColumn == $this->checkColumnByName('external_description', $allColNames)){
                        $node['external_description'] = $cell->getValue();
                    }
                    else if($currentColumn == $this->checkColumnByName('extracted_definition', $allColNames)){
                        $node['extracted_definition'] = $cell->getValue();
                    }
                    else if($currentColumn == $this->checkColumnByName('extracted_definition_link', $allColNames)){
                        $node['extracted_definition_link'] = $cell->getValue();
                    }
                    else if($currentColumn == $this->checkColumnByName('indicators_exclude_abstract_mapping', $allColNames)){
                        $node['indicators_exclude_abstract_mapping'] = $cell->getValue();
                    }
                    else if($currentColumn == $this->checkColumnByName('selection_group_1', $allColNames)){
                        $node['selection_group_1'] = $cell->getValue();
                    }
                    else if($currentColumn == $this->checkColumnByName('selection_group_2', $allColNames)){
                        $node['selection_group_2'] = $cell->getValue();
                    }
                    else if($currentColumn == $this->checkColumnByName('exclude_selection_group_1', $allColNames)){
                        $node['exclude_selection_group_1'] = $this->extractSynonyms($cell->getValue());
                    }
                    else if($currentColumn == $this->checkColumnByName('exclude_selection_group_2', $allColNames)){
                        $node['exclude_selection_group_2'] = $this->extractSynonyms($cell->getValue());
                    }

                }
            }

            $nodes[] = $node;
            $counter++;
        }

        //nest the nodes
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

    private function checkColumnByName($columnName, $allColNames){
        $targetKey = array_search($columnName, $allColNames);
        return $this->getLetterFromNumber($targetKey);
    }

    private function getLetterFromNumber($no){
        // cheap, but works until Z
        $alphabet = range('A', 'Z');
        return $alphabet[$no];
    }

   

    private function getLastSynonymCol($allColNames){
        
        $all = [];

        foreach ($allColNames as $value) {
            if(is_integer($value)) {
                $all [] = $value;
            } else {
                break;
            }
        }
        
        return $this->getLetterFromNumber(end($all)-1);

    }

    private function getAllHeaderStrings($worksheet){
        $allcols =[];
        $highestColumn = $worksheet->getHighestDataColumn(); // e.g 'F'
        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

        for ($col = 1; $col <= $highestColumnIndex; ++$col) {
            $value = $worksheet->getCell([$col, 1])->getValue();
            $allcols [] = $value;
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

// why not closing the manual addition and base the node on the ExcelSHeetInternal.php column list?
        // get column list via reflection class: https://www.php.net/manual/en/class.reflectionclass.php
    // https://stackoverflow.com/questions/2555883/in-php-is-it-possible-to-create-an-instance-of-an-class-without-calling-classs
    // $excelSheetHeadings = ExcelSheetInternal::newInstanceWithoutConstructor(); 
    // dd($excelSheetHeadings->headings());
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
            'extracted_definition_link'  => '',
            'indicators_exclude_abstract_mapping'  => '',
            'selection_group_1'  => '',
            'selection_group_2'  => '',
            'exclude_selection_group_1'  => '',
            'exclude_selection_group_2'  => '',
            'subTerms' => []
        ];

        return $node;
    }
    
    //////////////////////////////////////////////////////////////
    //what to do with these functions? implement or toss?
    ///////

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

}