<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Vocabulary extends Model
{
    use HasFactory;

    public $fillable = [
        'name',
        'uri',
        'display_name',
        'version',
    ];

    public function keywords(): HasMany
    {
        return $this->hasMany(Keyword::class);
    }

    public function searchKeywords(): HasManyThrough
    {
        return $this->hasManyThrough(KeywordSearch::class, Keyword::class);
    }

    public function maxLevel()
    {
        return Keyword::where('vocabulary_id', $this->id)->max('level');
    }

    public function subDomain(): BelongsTo
    {
        return $this->belongsTo(SubDomain::class);
    }
}
