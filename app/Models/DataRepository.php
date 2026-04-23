<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DataRepository extends Model
{
    protected $fillable = [
        'name',
        'ckan_name',
    ];

    public function importers(): HasMany
    {
        return $this->hasMany(Importer::class);
    }
}
