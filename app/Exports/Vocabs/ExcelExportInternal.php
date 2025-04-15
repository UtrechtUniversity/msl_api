<?php

namespace App\Exports\Vocabs;

use App\Models\Keyword;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\Models\Vocabulary;

class ExcelExportInternal implements FromCollection, WithHeadings, WithMapping
{
    public $vocabulary;
    
    public $levels;
    
    
    public function __construct(Vocabulary $vocabulary)
    {
        $this->vocabulary = $vocabulary;        
        $this->levels = range(1, $this->vocabulary->maxLevel());
    }
    
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {      
        $keywords = $this->vocabulary->keywords;
                
        return $keywords;
    }
    
    public function headings(): array
    {
        return array_merge(
            $this->levels, 
            [
                'indicator terms',
                'exclude_domain_mapping',   
                'uri',
                'hyperlink',
                'external_uri',
                'external_vocab_scheme',
                'external_description',
                'extracted_definition',
                'extracted_definition_link',
                'indicators_exclude_abstract_mapping'
            ]
        );
    }
    
    public function map($keyword): array
    {
        return array_merge(            
            $this->getLevels($keyword),
            [
                $keyword->getSynonymString(),
                $keyword->exclude_domain_mapping,
                $keyword->uri,
                $keyword->hyperlink,
                $keyword->external_uri,
                '',
                '',
                $keyword->extracted_definition,
                $keyword->extracted_definition_link,
                0
            ]
        );
    }
    
    private function getLevels(Keyword $keyword)
    {
        $return = array();
        for ($i = 1; $i <= count($this->levels); $i++) {
            if($i === $keyword->level) {
                $return[] = $keyword->value;
            } else {
                $return[] = "";
            }            
        }
        return $return;
    }

}
