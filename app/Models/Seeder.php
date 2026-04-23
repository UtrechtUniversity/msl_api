<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Seeder extends Model
{
    protected $fillable = [
        'name',
        'description',
        'type',
        'options',
    ];

    protected $casts = [
        'options' => 'array',
    ];

    public function seeds(): HasMany
    {
        return $this->hasMany(Seed::class);
    }
}
