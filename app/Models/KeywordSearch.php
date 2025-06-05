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
        'exclude_selection_group_1',
        'exclude_selection_group_2',
    ];

    protected $casts = [
        'is_synonym' => 'boolean',
        'exclude_abstract_mapping' => 'boolean',
        'exclude_selection_group_1' => 'boolean',
        'exclude_selection_group_2' => 'boolean',
    ];

    protected $table = 'keywords_search';

    public function keyword()
    {
        return $this->belongsTo(Keyword::class);
    }
}
