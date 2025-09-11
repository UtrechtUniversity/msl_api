<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MappingLog extends Model
{
    protected $fillable = [
        'type',
        'message',
        'source_dataset_id',
        'import_id'
    ];
    
    public function importer()
    {
        return $this->belongsTo(Importer::class);
    }
    
    public function source_dataset()
    {
        return $this->belongsTo(SourceDataset::class);
    }
    
    public function getDatasetDOI()
    {
        if($this->source_dataset->dataset_create) {
            $dataset = $this->source_dataset->dataset_create->dataset;
            
            if(isset($dataset['msl_pids'])) {
                foreach ($dataset['msl_pids'] as $pid) {
                    if($pid['msl_identifier_type'] == 'doi') {
                        return $pid['msl_pid'];
                    }
                }
            }
            
        }
        
        return '';
    }
}
