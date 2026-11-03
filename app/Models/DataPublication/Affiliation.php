<?php

namespace App\Models\DataPublication;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Affiliation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'identifier',
        'scheme',
        'uri',
    ];

    public function affiliatable(): MorphTo
    {
        return $this->morphTo();
    }
}
