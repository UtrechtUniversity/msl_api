<?php

namespace App\Http\Resources;

use App\DataPublications\isInclusiveDataPublication;
use App\Http\Resources\V2\DataPublicationResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GeoFeatureDataPublicationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $isInclusiveDataPublications = collect($this->resource->dataPublications);

        return [
            'data_publications' => $isInclusiveDataPublications->map(
                fn (isInclusiveDataPublication $isInclusiveDataPublication) => (
                    (new DataPublicationResource($isInclusiveDataPublication->dataPublication))->setIncludesGeoJson(false)->setInclusiveInformation($isInclusiveDataPublication->isInclusive)
                )
            ),
            'geo_features' => new InsideOverlappingGeoFeaturesResource($this->resource->features),
        ];
    }
}
