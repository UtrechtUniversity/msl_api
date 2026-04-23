<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaboratoryKeyword extends Model
{
    protected $table = 'laboratory_keywords';

    protected $fillable = [
        'laboratory_id',
        'value',
        'uri',
    ];

    public function laboratory(): BelongsTo
    {
        return $this->belongsTo(Laboratory::class);
    }
}
