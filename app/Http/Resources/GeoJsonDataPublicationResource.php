<?php

namespace App\Http\Resources;

use App\Http\Resources\V2\DataPublicationResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class GeoJsonDataPublicationResource extends JsonResource
{
    private function getOrderedFeatures(Collection $dataPublications)
    {
        // create array of features
        $allFeatures = [];
        foreach ($dataPublications as $dataPublication) {
            $featuresCollection = $dataPublication->msl_geojson_featurecollection;

            if (! $featuresCollection) {
                continue;
            }
            // TODO i don't like this at all
            $features = json_decode($featuresCollection, true)['features'];

            foreach ($features as $feature) {
                array_push($allFeatures, ['feature' => $feature, 'title' => $dataPublication->title, 'data_publication_doi' => $dataPublication->msl_doi]);
            }
        }
        // order

        return $allFeatures;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $dataPublications = collect($this->resource);
        $geo = $this->getOrderedFeatures($dataPublications);
        $geoJsonInfo = ['geojson' => $geo];
        $geoJsonInfo += [
            'data_publications' => $dataPublications->map(fn ($resource) => (new DataPublicationResource(
                resource: $resource,
                includesGeoJson: false
            ))),
        ];

        return $geoJsonInfo;
    }
}
