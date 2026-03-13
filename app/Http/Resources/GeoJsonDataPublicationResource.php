<?php

namespace App\Http\Resources;

use App\Http\Resources\V2\DataPublicationResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GeoJsonDataPublicationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $dataPublications = collect($this->resource->dataPublications);

        return [
            'data_publications' => $dataPublications->map(
                fn ($dataPublication) => (
                    new DataPublicationResource(
                        resource: $dataPublication,
                        includesGeoJson: false
                    ))
            ),
            'geojson' => GeoJsonFeaturePerDataPublicationResource::collection($this->resource->features),
        ];
    }
}
