<?php

namespace App\Models\DataPublication;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RelatedIdentifier extends Model
{
    use HasFactory;

    protected $fillable = [
        'identifier',
        'type',
        'relation_type',
        'metadata_scheme',
        'metadata_scheme_uri',
        'metadata_scheme_type',
        'resource_type_general',
    ];

    public function dataPublication(): BelongsTo
    {
        return $this->belongsTo(DataPublication::class);
    }
}
