<?php

namespace App\Models\DataPublication;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Date extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'type',
        'information',
    ];

    public function dataPublication(): BelongsTo
    {
        return $this->belongsTo(DataPublication::class);
    }
}
