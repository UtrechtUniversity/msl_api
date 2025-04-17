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
        'version',
        'selection_group_1',
        'selection_group_2'
    ];
    
    protected $casts = [
        'is_synonym' => 'boolean',
        'exclude_abstract_mapping' => 'boolean',
        'selection_group_1' => 'boolean',
        'selection_group_2' => 'boolean'
    ];

    protected $table = 'keywords_search';
 
    public function keyword()
    {
        return $this->belongsTo(Keyword::class);
    }
}
