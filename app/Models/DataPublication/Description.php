<?php

namespace App\Models\DataPublication;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Description extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'annotated',
        'language',
        'type',
    ];

    public function dataPublication(): BelongsTo
    {
        return $this->belongsTo(DataPublication::class);
    }
}
