<?php

namespace App\Exports;

use App\Models\MappingLog;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MappingLogsExport implements FromCollection, WithHeadings, WithMapping
{
    public $importId;
    
    
    public function __construct($importId)
    {
        $this->importId = $importId;
    }
    
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return MappingLog::where('import_id', $this->importId)->get();
    }
    
    public function headings(): array
    {
        return [
            'identifier',
            'doi',
            'type',
            'message',
            'created_at'
        ];
    }
    
    public function map($mappingLog): array
    {
        return [
            $mappingLog->source_dataset->source_dataset_identifier->identifier,
            $mappingLog->getDatasetDOI(),
            $mappingLog->type,
            $mappingLog->message,
            $mappingLog->created_at
        ];
    }

}
