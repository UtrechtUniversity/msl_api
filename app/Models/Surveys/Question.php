<?php

namespace App\Models\Surveys;

use App\Casts\asQuestion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Question extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'question',
        'question_type_id',
        'answerable',
    ];

    protected function casts(): array
    {
        return [
            'question' => asQuestion::class,
        ];
    }

    public function question_type()
    {
        return $this->belongsTo(QuestionType::class, 'question_type_id');
    }

    public function surveys(): BelongsToMany
    {
        return $this->belongsToMany(Survey::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function order()
    {
        return $this->pivot->order;
    }
}
