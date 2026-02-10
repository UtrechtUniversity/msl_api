<?php

namespace App\Http\Resources;

use App\GeoJson\BoundingBox;
use App\Http\Resources\V2\DataPublicationResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GeoJsonDataPublicationResource extends JsonResource
{
    private BoundingBox $bbox;

    public function __construct($resource, BoundingBox $bbox)
    {
        parent::__construct($resource);
        $this->bbox = $bbox;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $dataPublications = collect($this->resource);

        $geoJsonInfo = [
            'data_publications' => $dataPublications->map(fn ($dataPublication) => (new DataPublicationResource(
                resource: $dataPublication,
                includesGeoJson: false
            ))),

            'geojson' => new InclusiveExclusiveGeoJsonFeaturesResource($dataPublications, $this->bbox),
        ];

        return $geoJsonInfo;
    }
}
