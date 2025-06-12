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
            'columnDescription' => 'Indicates the position of the term in the vocabulary taxonomy, where lower and higher numbers incidate broader and narrower terms, respectively.',
        ],
        [
            'columnName' => 'indicator terms',
            'columnDescription' => 'Words that are synonymous to the term, or serve to indicate that the term they are associated to is relevant to the dataset.',
        ],
        [
            'columnName' => 'uri',
            'columnDescription' => 'The unique identifier for the term',
        ],
        [
            'columnName' => 'hyperlink',
            'columnDescription' => 'The link to an external resource. This is not a definition, but may contain more information about the term. For instance a link to a page which provides more context',
        ],
        [
            'columnName' => 'external_uri',
            'columnDescription' => 'A reference to a term in an external vocabulary, which is equivalent to the term',
        ],
        [
            'columnName' => 'external_vocab_scheme',
            'columnDescription' => 'Name of the vocuabulary in which the column value "external_uri" exists. For example "GeoSciML"',
        ],
        [
            'columnName' => 'external_description',
            'columnDescription' => 'The description found on the "external_uri" of external vocabulary',
        ],
        [
            'columnName' => 'contributor_definition',
            'columnDescription' => 'Definition or other notes about the term given by a contributor like you working in this Excel sheet. Structured information should be transferred to other columns, like "external_uri" or "hyperlink"',
        ],
        [
            'columnName' => 'contributor_definition_link',
            'columnDescription' => 'External links used by a contributor like you working in this Excel sheet. Structured information should be transferred to other columns, like "external_uri" or "hyperlink"',
        ],
        [
            'columnName' => 'exclude_domain_mapping',
            'columnDescription' => 'Indicates if the term is being used in mapping the associated data publication to one or more scientific domain(s) in MSL ("yes"/"no")',
        ],
        [
            'columnName' => 'terms_exclude_abstract_mapping',
            'columnDescription' => 'Enter terms that should be excluded from being detected within the abstract, title, etc. during the importing process. Either the term itself (set in 1, 2, 3, etc.) or specific indicator terms (set in indicator terms) may be entered. Each term should start with a “#” and match the term specified in the other fields.',
        ],
        [
            'columnName' => 'selection_group_1',
            'columnDescription' => 'Indicate if this term should be included in the group 1 words determining if data publications are relevant to MSL (“yes”/”no”). Relevant data publications have a combination of at least 1 group 1 and 1 group 2 word. Group 1 words indicate that an Earth scientific material or setting was studied.',
        ],
        [
            'columnName' => 'selection_group_2',
            'columnDescription' => 'Indicate if this term should be included in the group 2 words determining if data publications are relevant to MSL (“yes”/”no”). Relevant data publications have a combination of at least 1 group 1 and 1 group 2 word. Group 2 words indicate lab use, notably "apparatus", "measured property", etc.',
        ],
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
