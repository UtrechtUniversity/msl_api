<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DatasetCreate extends Model
{
    
    protected $fillable = [
        'dataset_type',
        'dataset',
        'source_dataset_id',
        'import_id',
        'response_body'
    ];
    
    protected $casts = [
        'dataset' => 'array'
    ];

    public function source_dataset()
    {
        return $this->belongsTo(SourceDataset::class);
    }
    
    public function getDatasetAsJson($pretty = false)
    {
        if($pretty) {
            return json_encode($this->dataset, JSON_PRETTY_PRINT);
        } else {
            return json_encode($this->dataset);
        }
    }
    
    public function getResponseBodyAsJson($pretty = false)
    {
        if($pretty) {
            return json_encode(json_decode($this->response_body), JSON_PRETTY_PRINT);
        } else {
            return json_encode(json_decode($this->response_body));
        }
    }
    
}
