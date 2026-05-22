<?php

namespace App\Http\Resources;

use App\DataPublications\InclusiveOrNotDataPublication;
use App\Http\Resources\V2\DataPublicationResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GeoJsonDataPublicationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $inclusiveOrNotDataPublications = collect($this->resource->dataPublications);

        return [
            'data_publications' => $inclusiveOrNotDataPublications->map(
                fn (InclusiveOrNotDataPublication $inclusiveOrNotDataPublication) => (
                    (new DataPublicationResource($inclusiveOrNotDataPublication->dataPublication))->setIncludesGeoJson(false)->setInclusiveInformation($inclusiveOrNotDataPublication->inclusive)
                )
            ),
            'geojson' => GeoJsonFeaturePerDataPublicationResource::collection($this->resource->features),
        ];
    }
}
