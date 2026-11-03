<?php

namespace App\Models\DataPublication;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlternateIdentifier extends Model
{
    use HasFactory;

    protected $fillable = [
        'identifier',
        'type',
    ];

    public function dataPublication(): BelongsTo
    {
        return $this->belongsTo(DataPublication::class);
    }
}
