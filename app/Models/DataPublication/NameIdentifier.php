<?php

namespace App\Models\DataPublication;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class NameIdentifier extends Model
{
    use HasFactory;

    protected $fillable = [
        'identifier',
        'scheme',
        'uri',
    ];

    public function identifiable(): MorphTo
    {
        return $this->morphTo();
    }
}
