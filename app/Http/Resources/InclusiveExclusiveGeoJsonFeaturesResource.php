<?php

namespace App\Http\Resources;

use App\GeoJson\BoundingBox;
use App\GeoJson\Geometry\Point;
use App\GeoJson\Geometry\Polygon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class InclusiveExclusiveGeoJsonFeaturesResource extends JsonResource
{
    private BoundingBox $bbox;

    public function __construct($resource, BoundingBox $bbox)
    {
        parent::__construct($resource);
        $this->bbox = $bbox;
    }

    /**
     * @param  Collection<int, DataPublication>  $dataPublications
     * @return array<GeoJsonFeatureResource>
     */
    private function getOrderedFeatures(Collection $dataPublications)
    {

        // create array of features
        $allFeatureResources = [];
        foreach ($dataPublications as $dataPublication) {
            $featuresCollection = $dataPublication->geojson_featurecollection;
            foreach ($featuresCollection->features as $feature) {
                $allFeatureResources[] = new GeoJsonFeatureResource($feature, $dataPublication);
            }
        }
        // Descending order based on the area of the feature
        usort(
            $allFeatureResources,
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

        return $allFeatureResources;
    }

    /**
     * @param   array<GeoJsonFeatureResource>
     * @return array<GeoJsonFeatureResource>
     */
    public function getInclusiveFeatures(array $geoFeatureResources): array
    {
        $inclusiveFeatures = [];
        foreach ($geoFeatureResources as $geoFeatureResource) {
            if (! $this->bbox->contains($geoFeatureResource->feature->geometry)) {
                continue;
            }
            $inclusiveFeatures[] = $geoFeatureResource;
        }

        return $inclusiveFeatures;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $orderedFeatures = $this->getOrderedFeatures($this->resource);
        $inclusiveFeatures = $this->getInclusiveFeatures($orderedFeatures);

        return [
            // Adding the array of resources on its own won't work
            // We have to use the `collection` method to use the `toArray`
            'exclusive' => GeoJsonFeatureResource::collection($orderedFeatures),
            'inclusive' => GeoJsonFeatureResource::collection($inclusiveFeatures),
        ];
    }
}
