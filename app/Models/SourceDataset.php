<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SourceDataset extends Model
{
    protected $fillable = [
        'source_dataset_identifier_id',
        'import_id',
        'status',
        'source_dataset',
    ];

    public function sourceDatasetIdentifier(): BelongsTo
    {
        return $this->belongsTo(SourceDatasetIdentifier::class);
    }

    public function import(): BelongsTo
    {
        return $this->belongsTo(Import::class);
    }

    public function datasetCreate(): HasOne
    {
        return $this->hasOne(DatasetCreate::class);
    }
}
