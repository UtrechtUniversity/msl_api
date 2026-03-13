<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InclusiveExclusiveGeoJsonDataPublicationsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'exclusive' => new GeoJsonDataPublicationResource(
                $this->resource->exclusiveFeaturesWithDataPublications
            ),
            'inclusive' => new GeoJsonDataPublicationResource(
                $this->resource->inclusiveFeaturesWithDataPublications
            ),
        ];
    }
}
