<?php
namespace App\Converters;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class ExcelToJsonConverter
{
    
    public function ExcelToJson($filepath)
    {
        
        $spreadsheet = IOFactory::load($filepath);
        $worksheet = $spreadsheet->getActiveSheet();
        
        $fields = [];
        $observations = [];
        foreach ($worksheet->getRowIterator() as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(FALSE);
            
            $observation = [];                        
            
            foreach ($cellIterator as $cell) {
                if($row->getRowIndex() == 1) {
                    $fields[trim($cell->getColumn())] = $cell->getValue();
                } else {
                    $observation[$fields[trim($cell->getColumn())]] = $cell->getValue();
                }
            }
            if(count($observation) > 0) {
                $observations[] = $observation;
            }
        }
        
        return (json_encode($observations, JSON_PRETTY_PRINT));                
    }    
}

