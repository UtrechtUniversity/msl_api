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
        $geoJsonInfo = ['geojson' => json_decode($this->msl_geojson_featurecollection)];

        $geoJsonInfo += [
            'data_publication' => new DataPublicationResource(
                resource: $this,
                includesGeoJson: false
            ),
        ];

        return $geoJsonInfo;
    }
}
