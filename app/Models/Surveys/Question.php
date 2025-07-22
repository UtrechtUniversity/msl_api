<?php

namespace App\Models\Surveys;

use App\Casts\asQuestion;
use Illuminate\Database\Eloquent\Model;
use App\Casts\asTextQuestion;

class Question extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'question',
        'question_type_id',
    ];

    protected function casts(): array
    {   
        return [
            'question' => asQuestion::class,
        ];
    }

    public function question_type()
    {
        return $this->belongsTo(QuestionType::class);
    }

    public function question_survey(){
        return $this->belongsToMany(QuestionSurvey::class);
    }
}
