<?php

namespace App\Exports\Vocabs;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class ExcelSheetColumnDescriptions implements FromArray, WithHeadings, WithMapping, WithTitle
{
    protected $descriptionsMapped = [
        [
            'columnName' => '1, 2, 3, etc.',
            'columnPurpose' => 'For general use',
            'columnDescription' => 'Indicates the position of the term in the vocabulary taxonomy, where lower and higher numbers incidate broader and narrower terms, respectively.',
        ],
        [
            'columnName' => 'indicator terms',
            'columnPurpose' => 'For general use',
            'columnDescription' => 'Words that are synonymous to the term, or serve to indicate that the term they are associated to is relevant to the dataset.',
        ],
        [
            'columnName' => 'uri',
            'columnPurpose' => 'For general use',
            'columnDescription' => 'The unique identifier for the term',
        ],
        [
            'columnName' => 'hyperlink',
            'columnPurpose' => 'For general use',
            'columnDescription' => 'The link to an external resource. This is not a definition, but may contain more information about the term. For instance a link to a page which provides more context',
        ],
        [
            'columnName' => 'external_uri',
            'columnPurpose' => 'For general use',
            'columnDescription' => 'A reference to a term in an external vocabulary, which is equivalent to the term',
        ],
        [
            'columnName' => 'external_vocab_scheme',
            'columnPurpose' => 'For general use',
            'columnDescription' => 'Name of the vocuabulary in which the column value "external_uri" exists. For example "GeoSciML"',
        ],
        [
            'columnName' => 'external_description',
            'columnPurpose' => 'For general use',
            'columnDescription' => 'The description found on the "external_uri" of external vocabulary',
        ],
        [
            'columnName' => 'contributor_definition',
            'columnPurpose' => 'For continued vocabulary development',
            'columnDescription' => '[may contain information that is not verified by the EPOS-MSL community]: Definition or other notes about the term, provided by a contributor, like yourself, when working with this excel sheet. Structured information should be transferred to other columns, like "external_uri" or "hyperlink"',
        ],
        [
            'columnName' => 'contributor_definition_link',
            'columnPurpose' => 'For continued vocabulary development',
            'columnDescription' => '[may contain information that is not verified by the EPOS-MSL community]: External link provided by a contributor, like yourself, when working with this excel sheet. Structured information should be transferred to other columns, like "external_uri" or "hyperlink"',
        ],
        [
            'columnName' => 'exclude_domain_mapping',
            'columnPurpose' => 'For use by MSL. Details how the term is used by EPOS-MSL',
            'columnDescription' => 'Indicates if the term is being used in mapping the associated data publication to one or more scientific domain(s) in MSL ("yes"/"no")',
        ],
        [
            'columnName' => 'terms_exclude_abstract_mapping',
            'columnPurpose' => 'For use by MSL. Details how the term is used by EPOS-MSL',
            'columnDescription' => 'Indicates if a term is excluded from being detected within the abstract, title, etc. during the importing process. Pertains either to the term itself (i.e. as included under column "1, 2, 3, etc."), or to specific indicator terms (i.e. as included under column "indicator terms"). Each term starts with a “#” and match the term specified in the other fields.',
        ],
        [
            'columnName' => 'selection_group_1',
            'columnPurpose' => 'For use by MSL. Details how the term is used by EPOS-MSL',
            'columnDescription' => 'Indicates if this term should be included in the group 1 words determining if data publications are relevant to MSL (“yes”/”no”). Group 1 words indicates relevance to the Earth sciences, by matching to terms from the "material" or "geological setting" vocabularies used by EPOS-MSL. Relevant data publications have a combination of at least 1 group 1 and 1 group 2 word.',
        ],
        [
            'columnName' => 'selection_group_2',
            'columnPurpose' => 'For use by MSL. Details how the term is used by EPOS-MSL',
            'columnDescription' => 'Indicates if this term should be included in the group 2 words determining if data publications are relevant to MSL (“yes”/”no”). Group 2 indicates relevance to Laboratory studies, by matching to terms used in e.g. "apparatus", "measured property", sections of domain-specific vocabularies used in EPOS-MSL. Relevant data publications have a combination of at least 1 group 1 and 1 group 2 word.',
        ],
    ];

    public function array(): array
    {
        return $this->descriptionsMapped;
    }

    public function headings(): array
    {
        return ['field', 'purpose', 'description'];
    }

    public function title(): string
    {
        return 'Headers descriptions';
    }

    public function map($row): array
    {
        return [
            [
                $row['columnName'], $row['columnPurpose'], $row['columnDescription'],
            ],
        ];
    }
}
