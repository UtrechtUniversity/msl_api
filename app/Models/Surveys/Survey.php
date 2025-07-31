<?php

namespace App\Models\Surveys;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Survey extends Model
{
    // timestamps needed?
    public $timestamps = false;

    protected $fillable = [
        'name',
        'active'
    ];

    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class)->withPivot('order');
    }

    public function reponses(){
        return $this->hasMany(Response::class);
    }



}
