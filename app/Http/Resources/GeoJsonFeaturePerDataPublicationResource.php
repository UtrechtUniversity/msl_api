<?php

namespace App\Http\Resources;

use App\DataPublications\GeoJsonFeaturePerDataPublication;
use App\GeoJson\Feature\Feature;
use App\Models\Ckan\DataPublication;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GeoJsonFeaturePerDataPublicationResource extends JsonResource
{
    public Feature $feature;

    public DataPublication $dataPublication;

    public function __construct(GeoJsonFeaturePerDataPublication $geoJsonFeature)
    {
        $this->feature = $geoJsonFeature->feature;
        $this->dataPublication = $geoJsonFeature->dataPublication;
    }

    public function toArray(Request $request): array
    {
        return [
            'feature' => $this->feature,
            'title' => $this->dataPublication->title,
            'data_publication_doi' => $this->dataPublication->msl_doi,
            'portalLink' => route('data-publication-detail', ['id' => $this->dataPublication->name]),
        ];
    }
}
