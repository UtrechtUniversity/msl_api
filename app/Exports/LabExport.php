<?php

namespace App\Exports;

use App\Models\Laboratory;
use App\Models\MappingLog;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LabExport implements FromCollection, WithHeadings, WithMapping
{
       
    public function __construct()
    {

    }
    
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Laboratory::get();
    }
    
    public function headings(): array
    {
        return [
            'fast_id',
            'name',
            'latitude',
            'longitude'
        ];
    }
    
    public function map($laboratory): array
    {
        return [
            $laboratory->fast_id,
            $laboratory->name,
            $laboratory->latitude,
            $laboratory->longitude
        ];
    }

}
