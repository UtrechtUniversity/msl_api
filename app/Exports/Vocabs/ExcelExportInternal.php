<?php

namespace App\Exports\Vocabs;

use App\Models\Vocabulary;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ExcelExportInternal implements WithMultipleSheets
{
    public $vocabulary;

    public function __construct(Vocabulary $vocabulary)
    {
        $this->vocabulary = $vocabulary;
    }

    public function sheets(): array
    {
        $sheets = [
            new ExcelSheetInternal($this->vocabulary),
            new ExcelSheetColumnDescriptions,
        ];

        return $sheets;
    }
}
