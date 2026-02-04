<?php

namespace App\Http\Resources;

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

            foreach ($featuresCollection->features as $feature) {
                $allFeatures[] = new GeoJsonFeatureResource($feature, $dataPublication);
            }
        }
        // Descending order based on the area of the feature
        usort(
            $allFeatures,
            function (GeoJsonFeatureResource $a, GeoJsonFeatureResource $b) {
                // If first argument is a point
                if ($a->feature->geometry instanceof Point) {
                    return 1;
                }
                // If first argument is a polygon and the second argument is a point
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

        $geoJsonInfo = [
            'data_publications' => $dataPublications->map(fn ($dataPublication) => (new DataPublicationResource(
                resource: $dataPublication,
                includesGeoJson: false
            ))),
            // Adding the array of resources on its own won't work
            // We have to use the `collection` method to use the `toArray`
            'geojson' => GeoJsonFeatureResource::collection($geo),
        ];

        return $geoJsonInfo;
    }
}
