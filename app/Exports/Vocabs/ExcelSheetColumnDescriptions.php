<?php

namespace App\Exports\Vocabs;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class ExcelSheetColumnDescriptions implements WithHeadings, WithTitle
{
    
     
    public function headings(): array
    {
        return      
            [
                ['field', 'description'],
                ['1, 2, 3, etc.', 'layered terms'],
                ['indicator terms', 'empty'],
                ['exclude_domain_mapping', ''],
                ['uri', ''],
                ['hyperlink', ''],
                ['external_uri', ''],
                ['external_vocab_scheme', ''],
                ['external_description', ''],
                ['extracted_definition', ''],
                ['extracted_definition_link', ''],
                ['indicators_exclude_abstract_mapping', '' ]
            ]
        ;
    }

    public function title(): string
    {
         return 'Headers descriptions';
    }

}
