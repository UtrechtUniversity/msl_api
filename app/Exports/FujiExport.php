<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use App\Models\FujiFairAssessment;

class FujiExport implements FromCollection, WithHeadings, WithMapping, WithStrictNullComparison
{   
    
    public function __construct()
    {

    }
    
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $collection = DB::table('fuji_fair_assessments')
        ->selectRaw('group_identifier,
            avg(score_F) as score_F,
            avg(score_F1) as score_F1,
            avg(score_F2) as score_F2,
            avg(score_F3) as score_F3,
            avg(score_F4) as score_F4,
            avg(score_A) as score_A,
            avg(score_A1) as score_A1,
            avg(score_A2) as score_A2,
            avg(score_I) as score_I,
            avg(score_I1) as score_I1,
            avg(score_I2) as score_I2,
            avg(score_I3) as score_I3,
            avg(score_R) as score_R,
            avg(score_R1) as score_R1,
            avg(score_R1_1) as score_R1_1,
            avg(score_R1_2) as score_R1_2,
            avg(score_R1_3) as score_R1_3,
            avg(score_percent) as score_FAIR
            ')
            ->where('export_identifier', 'WP-5 AD-03')
            ->groupBy('group_identifier')
            ->get();
        
        
        /*
        $collection = FujiFairAssessment::where('export_identifier', 'WP-5 AD-03')->get();
        */
        
        return $collection;
    }
    
    public function headings(): array
    {
        
        return [
            'group_identifier',
            'score_F',
            'score_F1',
            'score_F2',
            'score_F3',
            'score_F4',
            'score_A',
            'score_A1',
            'score_A2',
            'score_I',
            'score_I1',
            'score_I2',
            'score_I3',
            'score_R',
            'score_R1',
            'score_R1_1',
            'score_R1_2',
            'score_R1_3',
            'score_FAIR',
        ];
        
        
        /*
        return [
            'group_identifier',
            'uid',
            'score_F',
            'score_F1',
            'score_F2',
            'score_F3',
            'score_F4',
            'score_A',
            'score_A1',
            'score_A2',
            'score_I',
            'score_I1',
            'score_I2',
            'score_I3',
            'score_R',
            'score_R1',
            'score_R1_1',
            'score_R1_2',
            'score_R1_3',
            'score_FAIR',
        ];
        */
    }
    
    public function map($groupings): array
    {       
        
        return [
            $groupings->group_identifier,
            $groupings->score_F,
            $groupings->score_F1,
            $groupings->score_F2,
            $groupings->score_F3,
            $groupings->score_F4,
            $groupings->score_A,
            $groupings->score_A1,
            $groupings->score_A2,
            $groupings->score_I,
            $groupings->score_I1,
            $groupings->score_I2,
            $groupings->score_I3,
            $groupings->score_R,
            $groupings->score_R1,
            $groupings->score_R1_1,
            $groupings->score_R1_2,
            $groupings->score_R1_3,
            $groupings->score_FAIR
        ];
        
        /*
        return [
            $groupings->group_identifier,
            $groupings->doi,
            $groupings->score_F,
            $groupings->score_F1,
            $groupings->score_F2,
            $groupings->score_F3,
            $groupings->score_F4,
            $groupings->score_A,
            $groupings->score_A1,
            $groupings->score_A2,
            $groupings->score_I,
            $groupings->score_I1,
            $groupings->score_I2,
            $groupings->score_I3,
            $groupings->score_R,
            $groupings->score_R1,
            $groupings->score_R1_1,
            $groupings->score_R1_2,
            $groupings->score_R1_3,
            $groupings->score_percent
        ];
        */
    }

}
