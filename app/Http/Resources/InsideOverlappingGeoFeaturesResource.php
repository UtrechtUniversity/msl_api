<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InsideOverlappingGeoFeaturesResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'overlapping' => GeoFeatureResource::collection(
                $this->resource->overlappingFeatures
            ),
            'inside' => GeoFeatureResource::collection(
                $this->resource->insideFeatures
            ),
        ];
    }
}
