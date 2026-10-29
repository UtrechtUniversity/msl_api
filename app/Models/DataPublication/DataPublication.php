<?php

namespace App\Models\DataPublication;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'year',
        'language',
        'datacite_version',
        'publisher',
        'citation',
    ];
}
