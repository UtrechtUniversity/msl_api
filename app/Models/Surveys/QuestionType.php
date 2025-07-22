<?php

namespace App\Models\Surveys;

use Illuminate\Database\Eloquent\Model;

class QuestionType extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'class',
    ];

    // protected function question()
    // {
    //     $this->hasMany(Question::class);
    // }
}
