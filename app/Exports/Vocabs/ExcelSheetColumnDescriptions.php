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
                ['indicator terms', 'Words indicating the use of the term. Which can be a synonym, but does not have to be'],
                ['exclude_domain_mapping', 'Indicates if term is being used in domain mapping, which is the process of mapping the data publication to a domain'],
                ['uri', 'The unique identifier for the term'],
                ['hyperlink', 'The link to an external resource. This is not a definition, but may contain more information about the term. For instance a link to a page which provides more context'],
                ['external_uri', 'A reference to a term in an external vocabulary, which is equivalent to the term'],
                ['external_vocab_scheme', 'Name of the vocuabulary in which the column value "external_uri" exists. For example "GeoSciML"'],
                ['external_description', 'The description found on the "external_uri" of external vocabulary'],
                ['extracted_definition', '!!!This is a temporary field!!! Definition of the term given by a contributor like you working in this excel sheet. This data should be transferred to other columns, like "external_uri" or "hyperlink"'],
                ['extracted_definition_link', '!!!This is a temporary field!!! Definition of the term given by a contributor like you working in this excel sheet. This data should be transferred to other columns, like "external_uri" or "hyperlink"'],
                ['indicators_exclude_abstract_mapping', '!!!Awareness required!!! Any entry from "indicator terms" which is inserted in this field 
                (in the same format with #) will be excluded from the abstract mapping process. 
                This process determines if the indicator term is used to detect this term within the textual metadata of a data publication. 
                For instance, it may be excluded using this indicator term to find the term within the title of the data publication 
                or any other place where text is present' ]
            ]
        ;
    }

    public function title(): string
    {
         return 'Headers descriptions';
    }

}
