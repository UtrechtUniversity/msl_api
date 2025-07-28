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

    public function questions(){
        return $this->hasMany(Question::class);
    }
}
