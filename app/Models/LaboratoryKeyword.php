<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaboratoryKeyword extends Model
{
    protected $table = 'laboratory_keywords';

    protected $fillable = [
        'laboratory_id',
        'value',
        'uri',
    ];

    public function laboratory()
    {
        return $this->belongsTo(Laboratory::class);
    }
}
