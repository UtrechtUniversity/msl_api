<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Vocabulary extends Model
{
    public $fillable = [
        'name',
        'uri',
        'display_name',
    ];

    public function keywords(): HasMany
    {
        return $this->hasMany(Keyword::class);
    }

    public function search_keywords(): HasManyThrough
    {
        return $this->hasManyThrough(KeywordSearch::class, Keyword::class);
    }

    public function maxLevel()
    {
        return Keyword::where('vocabulary_id', $this->id)->max('level');
    }
}
