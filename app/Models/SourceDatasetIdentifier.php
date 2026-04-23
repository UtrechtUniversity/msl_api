<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SourceDatasetIdentifier extends Model
{
    protected $fillable = [
        'import_id',
        'identifier',
        'extra_payload',
    ];

    protected $casts = [
        'extra_payload' => 'array',
    ];

    public function import(): BelongsTo
    {
        return $this->belongsTo(Import::class);
    }

    public function source_dataset(): HasOne
    {
        return $this->hasOne(SourceDataset::class);
    }
}
