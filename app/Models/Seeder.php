<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    public function seeds()
    {
        return $this->hasMany(Seed::class);
    }
}
