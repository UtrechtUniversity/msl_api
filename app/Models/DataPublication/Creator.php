<?php

namespace App\Models\DataPublication;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Creator extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'name_type',
    ];

    public function dataPublication(): BelongsTo
    {
        return $this->belongsTo(DataPublication::class);
    }

    public function nameIdentifiers(): MorphMany
    {
        return $this->morphMany(NameIdentifier::class, 'identifiable');
    }

    public function affiliations(): MorphMany
    {
        return $this->morphMany(Affiliation::class, 'affiliatable');
    }
}
