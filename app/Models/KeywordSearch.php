<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KeywordSearch extends Model
{
    public $fillable = [
        'keyword_id',
        'value',
        'is_synonym',
        'exclude_abstract_mapping',
        'version'
    ];
    
    protected $table = 'keywords_search';
 
    public function keyword()
    {
        return $this->belongsTo(Keyword::class);
    }
}
