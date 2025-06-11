<?php

namespace App\Exports\Vocabs;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class ExcelSheetColumnDescriptions implements FromArray, WithHeadings, WithMapping, WithTitle
{
    protected $descriptionsMapped = [
        ['columnName' => '1, 2, 3, etc.', 'columnDescription' => 'layered terms'],
        ['columnName' => 'indicator terms', 'columnDescription' => 'Words indicating the use of the term. Which can be a synonym, but does not have to be'],
        ['columnName' => 'exclude_domain_mapping', 'columnDescription' => 'Indicates if term is being used in domain mapping, which is the process of mapping the data publication to a domain'],
        ['columnName' => 'uri', 'columnDescription' => 'The unique identifier for the term'],
        ['columnName' => 'hyperlink', 'columnDescription' => 'The link to an external resource. This is not a definition, but may contain more information about the term. For instance a link to a page which provides more context'],
        ['columnName' => 'external_uri', 'columnDescription' => 'A reference to a term in an external vocabulary, which is equivalent to the term'],
        ['columnName' => 'external_vocab_scheme', 'columnDescription' => 'Name of the vocuabulary in which the column value "external_uri" exists. For example "GeoSciML"'],
        ['columnName' => 'external_description', 'columnDescription' => 'The description found on the "external_uri" of external vocabulary'],
        ['columnName' => 'extracted_definition', 'columnDescription' => '!!!This is a temporary field!!! Definition of the term given by a contributor like you working in this excel sheet. This data should be transferred to other columns, like "external_uri" or "hyperlink"'],
        ['columnName' => 'extracted_definition_link', 'columnDescription' => '!!!This is a temporary field!!! Definition of the term given by a contributor like you working in this excel sheet. This data should be transferred to other columns, like "external_uri" or "hyperlink"'],
        ['columnName' => 'terms_exclude_abstract_mapping', 'columnDescription' => '!!!Awareness required!!! Any entry from "indicator terms" and the term name itself which is inserted in this field 
        (in the same format with #) will be excluded from the abstract mapping process. 
        This process determines if the indicator term is used to detect this term within the textual metadata of a data publication. This is just used within the catalogue itself and not in the process of selecting relevant data publications. 
        For instance, it may be excluded using this indicator term to find the term within the title of the data publication 
        or any other place where text is present'],
        ['columnName' => 'selection_group_1', 'columnDescription' => 'Contains a Boolean value indicating if the keywords is used in the group 1 words.'],
        ['columnName' => 'selection_group_2', 'columnDescription' => 'Contains a Boolean value indicating if the keywords is used in the group 2 words.'],
    ];

    public function array(): array
    {
        return $this->descriptionsMapped;
    }

    public function headings(): array
    {
        return ['field', 'description'];
    }

    public function title(): string
    {
        return 'Headers descriptions';
    }

    public function map($row): array
    {
        return [
            [$row['columnName'], $row['columnDescription']],
        ];
    }
}
