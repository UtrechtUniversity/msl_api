<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Importer extends Model
{
    protected $fillable = [
        'name',
        'description',
        'type',
        'options',
        'data_repository_id',
    ];

    protected $casts = [
        'options' => 'array',
    ];

    public function data_repository(): BelongsTo
    {
        return $this->belongsTo(DataRepository::class);
    }

    public function imports(): HasMany
    {
        return $this->hasMany(Import::class);
    }
}
