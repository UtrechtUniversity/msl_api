<?php

namespace App\Http\Resources;

use App\GeoJson\Feature\FeatureCollection;
use App\GeoJson\Geometry\Point;
use App\GeoJson\Geometry\Polygon;
use App\Http\Resources\V2\DataPublicationResource;
use App\Models\Ckan\DataPublication;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class GeoJsonDataPublicationResource extends JsonResource
{
    /**
     * @param  Collection<int, DataPublication>  $dataPublications
     * @return Collection<int, Feature>
     */
    private function getOrderedFeatures(Collection $dataPublications)
    {
        // create array of features
        $allFeatures = [];
        foreach ($dataPublications as $dataPublication) {
            $featuresCollection = $dataPublication->geojson_featurecollection;
            if (! ($featuresCollection instanceof FeatureCollection)) {
                throw new Exception("Collection is not an instance of the 'FeatureCollection' class. This is a bug.");
            }
            if (count($featuresCollection->features) < 1) {
                continue;
            }

            foreach ($featuresCollection->features as $feature) {
                // TODO can I do this a resource?
                array_push($allFeatures, new GeoJsonFeatureResource($feature, $dataPublication));
            }
        }
        // order

        usort(
            $allFeatures,
            function (GeoJsonFeatureResource $a, GeoJsonFeatureResource $b) {
                // If first argument is a point
                if ($a->feature->geometry instanceof Point) {
                    return 1;
                }
                // If second argument is a point
                if ($b->feature->geometry instanceof Point) {
                    return -1;
                }
                if (
                    ! ($b->feature->geometry instanceof Polygon &&
                        $a->feature->geometry instanceof Polygon
                    )
                ) {
                    throw new Exception('Both features for sorting are expected to be polygons by now. This is a bug.');
                }
                if ($a->feature->geometry->area() < $b->feature->geometry->area()) {
                    return 1;
                }

                return -1;
            }
        );

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
        // Adding the array of resources on its own won't work
        // We have to use the `collection` method to use the `toArray`
        $geoJsonInfo = ['geojson' => GeoJsonFeatureResource::collection($geo)];
        $geoJsonInfo += [
            'data_publications' => $dataPublications->map(fn ($resource) => (new DataPublicationResource(
                resource: $resource,
                includesGeoJson: false
            ))),
        ];

        return $geoJsonInfo;
    }
}
