<?php

namespace App\Models\Surveys;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Survey extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'active',
    ];

    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class)->withPivot('order')->orderByPivot('order');
    }

    public function responses(): HasMany
    {
        return $this->hasMany(Response::class);
    }

    public function getValidationRules()
    {

        $validationFields = [];

        foreach ($this->questions as $question) {
            if (! empty($question->question->validation)) {
                $validationFields[$question->question->sectionName] = $question->question->validation;
            }
        }

        return $validationFields;
    }
}
