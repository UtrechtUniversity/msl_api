<?php

namespace App\Models\Surveys;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    // timestamps needed?
    public $timestamps = false;

    protected $fillable = [
        'name',
        'active'
    ];

}
