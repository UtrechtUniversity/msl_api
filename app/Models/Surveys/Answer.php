<?php

namespace App\Models\Surveys;

use App\Casts\asAnswer;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'response_id',
        'question_id',
        'answer',
    ];

    protected function casts(): array
    {
        return [
            'answer' => asAnswer::class,
        ];
    }

    public function response()
    {
        return $this->belongsTo(Response::class, 'response_id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }

}
