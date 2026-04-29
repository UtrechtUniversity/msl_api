<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DatasetCreate extends Model
{
    protected $fillable = [
        'dataset_type',
        'dataset',
        'source_dataset_id',
        'import_id',
        'response_body',
    ];

    protected $casts = [
        'dataset' => 'array',
    ];

    public function sourceDataset(): BelongsTo
    {
        return $this->belongsTo(SourceDataset::class);
    }

    public function import(): BelongsTo
    {
        return $this->belongsTo(Import::class);
    }

    public function getDatasetAsJson($pretty = false)
    {
        if ($pretty) {
            return json_encode($this->dataset, JSON_PRETTY_PRINT);
        } else {
            return json_encode($this->dataset);
        }
    }

    public function getResponseBodyAsJson($pretty = false)
    {
        if ($pretty) {
            return json_encode(json_decode($this->response_body), JSON_PRETTY_PRINT);
        } else {
            return json_encode(json_decode($this->response_body));
        }
    }
}
