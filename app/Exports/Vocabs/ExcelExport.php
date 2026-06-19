<?php

namespace App\Exports\Vocabs;

use App\Models\Keyword;
use App\Models\Vocabulary;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExcelExport implements FromCollection, WithHeadings, WithMapping
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
                'synonyms',
                'uri',
                'external_uri',
                'external_vocab_scheme',
            ]
        );
    }

    public function map($keyword): array
    {
        return array_merge(
            $this->getLevels($keyword),
            [
                $keyword->getSynonymString(),
                $keyword->uri,
                $keyword->external_uri,
                $keyword->external_vocab_scheme,
            ]
        );
    }

    private function getLevels(Keyword $keyword)
    {
        $return = [];
        for ($i = 1; $i <= count($this->levels); $i++) {
            if ($i === $keyword->level) {
                $return[] = $keyword->value;
            } else {
                $return[] = '';
            }
        }

        return $return;
    }
}
