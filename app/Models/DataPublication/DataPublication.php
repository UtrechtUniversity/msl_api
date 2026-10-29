<?php

namespace App\Models\DataPublication;

use App\Models\DataRepository;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataPublication extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'title_annotated',
        'source_landing_page_url',
        'resource_type',
        'resource_type_general',
        'doi',
        'publication_year',
        'language',
        'datacite_version',
        'publisher',
        'citation',
    ];

    public function dataRepository(): BelongsTo
    {
        return $this->belongsTo(DataRepository::class);
    }
}
