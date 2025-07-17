<?php

namespace App\Models\Surveys;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'response_id',
        'question_id',
        'answer'
    ];

    public function response(){
        return $this->belongsTo(Response::class);
    }

    public function question(){
        return $this->belongsTo(Question::class);
    }
}
