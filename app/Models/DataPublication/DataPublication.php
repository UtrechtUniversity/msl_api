<?php

namespace App\Models\DataPublication;

use App\Models\DataRepository;
use App\Models\Keyword;
use App\Models\SubDomain;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function descriptions(): hasMany
    {
        return $this->hasMany(Description::class);
    }

    public function rights(): hasMany
    {
        return $this->hasMany(Right::class);
    }

    public function alternateIdentifiers(): hasMany
    {
        return $this->hasMany(AlternateIdentifier::class);
    }

    public function relatedIdentifiers(): hasMany
    {
        return $this->hasMany(RelatedIdentifier::class);
    }

    public function fundingReferences(): hasMany
    {
        return $this->hasMany(FundingReference::class);
    }

    public function dates(): hasMany
    {
        return $this->hasMany(Date::class);
    }

    public function creators(): hasMany
    {
        return $this->hasMany(Creator::class);
    }

    public function contributors(): hasMany
    {
        return $this->hasMany(Contributor::class);
    }

    public function sizes(): hasMany
    {
        return $this->hasMany(Size::class);
    }

    public function formats(): hasMany
    {
        return $this->hasMany(Format::class);
    }

    public function originalSubDomains(): BelongsToMany
    {
        return $this->belongsToMany(SubDomain::class, 'data_publication_original_sub_domain' );
    }

    public function interpretedSubDomains(): BelongsToMany
    {
        return $this->belongsToMany(SubDomain::class, 'data_publication_interpreted_sub_domain' );
    }

    public function subjects(): hasMany
    {
        return $this->hasMany(Subject::class);
    }

    public function originalKeywords(): BelongsToMany
    {
        return $this->belongsToMany(Keyword::class, 'data_publication_original_keyword' );
    }
}
