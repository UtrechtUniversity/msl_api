<?php

namespace App\Http\Resources;

use App\GeoJson\BoundingBox;
use App\GeoJson\Geometry\Point;
use App\GeoJson\Geometry\Polygon;
use App\Models\Ckan\DataPublication;
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
     * @param  array<int, DataPublication>  $dataPublications
     * @return array<GeoJsonFeatureResource>
     */
    private function getOrderedFeatures(array $dataPublications)
    {

        // create array of features
        $allFeatureResources = [];
        foreach ($dataPublications as $dataPublication) {
            $featuresCollection = $dataPublication->geojson_featurecollection;
            foreach ($featuresCollection->features as $feature) {
                // Create a feature which includes the datapublication as it gets out of CKAN
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
     */
    public function getInclusiveFeaturesResource(array $geoFeatureResources): GeoJsonDataPublicationResource
    {
        $inclusiveFeatures = [];
        $inclusiveDataPublications = [];
        foreach ($geoFeatureResources as $geoFeatureResource) {
            if (! $this->bbox->contains($geoFeatureResource->feature->geometry)) {
                // TODO make a check if the area is too big, if yes then  break;
                continue;
            }
            $inclusiveFeatures[] = $geoFeatureResource;
            $inclusiveDataPublications[] = $geoFeatureResource->dataPublication;
        }
        // Find unique data publications based on their dois
        $uniqueDois = array_unique(array_column($inclusiveDataPublications, 'msl_doi'));
        $uniqueInclusiveDataPublications = array_values(array_intersect_key($inclusiveDataPublications, $uniqueDois));

        return new GeoJsonDataPublicationResource($uniqueInclusiveDataPublications, $inclusiveFeatures);
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $orderedFeatures = $this->getOrderedFeatures($this->resource);

        return [
            // Adding the array of resources on its own won't work
            // We have to use the `collection` method to use the `toArray`
            'exclusive' => new GeoJsonDataPublicationResource($this->resource, $orderedFeatures),
            'inclusive' => $this->getInclusiveFeaturesResource($orderedFeatures),
        ];
    }
}
