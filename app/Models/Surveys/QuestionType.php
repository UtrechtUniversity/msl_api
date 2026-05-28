<?php

namespace App\Models\Surveys;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuestionType extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'class',
    ];

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }
}
