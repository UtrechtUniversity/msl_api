<?php

namespace App\Http\Resources;

use App\Http\Resources\V2\DataPublicationResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GeoJsonDataPublicationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // $geoJsonInfo = ['geojson' => json_decode($this->msl_geojson_featurecollection)];

        $geoJsonInfo = [
            'data_publications' => $this->collection->map(fn ($resource) => (new DataPublicationResource(
                resource: $resource,
                includesGeoJson: false
            ))),
        ];

        return $geoJsonInfo;
    }
}
