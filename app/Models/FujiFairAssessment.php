<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FujiFairAssessment extends Model
{
    protected $fillable = [
        'group_identifier',
        'doi',
    ];    
    
    protected $table = 'fuji_fair_assessments';
}
