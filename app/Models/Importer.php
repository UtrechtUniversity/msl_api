<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Importer extends Model
{
    protected $fillable = [
        'name',
        'description',
        'type',
        'options',
        'data_repository_id',
    ];

    protected $casts = [
        'options' => 'array',
    ];

    public function data_repository()
    {
        return $this->belongsTo(DataRepository::class);
    }

    public function imports()
    {
        return $this->hasMany(Import::class);
    }
}
