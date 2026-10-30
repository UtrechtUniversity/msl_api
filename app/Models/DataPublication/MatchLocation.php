<?php

namespace App\Models\DataPublication;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MatchLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'location',
    ];

    public function enrichedKeyword(): BelongsTo
    {
        return $this->belongsTo(EnrichedKeyword::class);
    }
}
