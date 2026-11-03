<?php

namespace App\Models;

use App\Models\DataPublication\DataPublication;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DataRepository extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'ckan_name',
    ];

    public function importers(): HasMany
    {
        return $this->hasMany(Importer::class);
    }

    public function dataPublications(): HasMany
    {
        return $this->hasMany(DataPublication::class);
    }
}
