<?php

namespace App\Models;

use App\Models\DataPublication\DataPublication;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SubDomain extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'short_name',
    ];

    public function dataPublicationsOriginalDomain(): BelongsToMany
    {
        return $this->belongsToMany(DataPublication::class, 'data_publication_original_sub_domain');
    }

    public function dataPublicationsInterpretedDomain(): BelongsToMany
    {
        return $this->belongsToMany(DataPublication::class, 'data_publication_interpreted_sub_domain');
    }
}
