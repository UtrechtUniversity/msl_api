<?php

namespace App\Models\DataPublication;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Right extends Model
{
    use HasFactory;

    protected $fillable = [
        'right',
        'uri',
        'identifier',
        'identifier_scheme',
        'identifier_scheme_uri',
    ];

    public function dataPublication(): BelongsTo
    {
        return $this->belongsTo(DataPublication::class);
    }
}
