<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vocabulary extends Model
{
    public $fillable = [
        'name',
        'uri',
        'display_name'
    ];
             
    public function keywords() {
        return $this->hasMany(Keyword::class);
    }
    
    public function search_keywords() {
        return $this->hasManyThrough(KeywordSearch::class, Keyword::class);
    }
    
    public function maxLevel() {
        return Keyword::where('vocabulary_id', $this->id)->max('level');
    }
}
