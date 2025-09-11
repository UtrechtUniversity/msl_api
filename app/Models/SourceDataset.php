<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SourceDataset extends Model
{
    protected $fillable = [
        'source_dataset_identifier_id',
        'import_id',
        'status',
        'source_dataset'
    ];
    
    public function source_dataset_identifier()
    {
        return $this->belongsTo(SourceDatasetIdentifier::class);
    }
    
    public function dataset_create()
    {
        return $this->hasOne(DatasetCreate::class);
    }
    
    public function mapping_logs()
    {
        return $this->hasMany(MappingLog::class);
    }
}
