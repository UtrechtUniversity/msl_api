<?php

namespace App\Models\Surveys;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionSurvey extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'survey_id',
        'question_id',
        'order' //how to prevent multi slotting an order?
    ];

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }
    
    public function question()
    {
        return $this->hasOne(Question::class, 'id', 'question_id');
    }
}
