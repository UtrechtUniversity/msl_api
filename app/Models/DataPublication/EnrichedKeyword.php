<?php

namespace App\Models\DataPublication;

use App\Models\Keyword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EnrichedKeyword extends Model
{
    use HasFactory;

    public function dataPublication(): BelongsTo
    {
        return $this->belongsTo(DataPublication::class);
    }

    public function keyword(): BelongsTo
    {
        return $this->belongsTo(Keyword::class);
    }

    public function matchLocations(): hasMany
    {
        return $this->hasMany(MatchLocation::class);
    }

    public function childKeywords(): BelongsToMany
    {
        return $this->BelongsToMany(Keyword::class, 'enriched_keyword_child_keywords');
    }
}
