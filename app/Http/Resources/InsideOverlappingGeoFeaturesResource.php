<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InsideOverlappingGeoFeaturesResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'overlapping' => GeoFeaturePerDataPublicationResource::collection(
                $this->resource->overlappingFeatures
            ),
            'inside' => GeoFeaturePerDataPublicationResource::collection(
                $this->resource->insideFeatures
            ),
        ];
    }
}
