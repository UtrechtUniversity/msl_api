<?php

namespace App\Services;

use App\DataPublications\GeoJsonFeatureDataPublication;
use App\DataPublications\GeoJsonFeaturePerDataPublication;
use App\DataPublications\InclusiveExclusiveGeoJsonFeatureDataPublication;
use App\GeoJson\BoundingBox;
use App\GeoJson\Geometry\Point;
use App\GeoJson\Geometry\Polygon;
use App\Models\Ckan\DataPublication;
use Exception;

class InclusiveExclusiveGeoJsonFeatureService
{
    /**
     * @param  array<int, DataPublication>  $dataPublications
     */
    public function createInclusiveExclusiveGeoJson(array $dataPublications, BoundingBox $bbox): InclusiveExclusiveGeoJsonFeatureDataPublication
    {

        //  split + sort
        $sortedFeatures = $this->sortFeatures($dataPublications);
        // inclusive
        [$inclusivePublications, $inclusiveFeatures] = $this->filterInclusive($sortedFeatures, $bbox);

        return new InclusiveExclusiveGeoJsonFeatureDataPublication(
            exclusiveFeaturesWithDataPublications: new GeoJsonFeatureDataPublication(
                dataPublications: $dataPublications,
                features: $sortedFeatures
            ),
            inclusiveFeaturesWithDataPublications: new GeoJsonFeatureDataPublication(
                dataPublications: $inclusivePublications,
                features: $inclusiveFeatures
            )
        );
    }

    /**
     * @param  array<int, DataPublication>  $dataPublications
     * @return GeoJsonFeaturePerDataPublication[]
     */
    private function sortFeatures($dataPublications): array
    {

        // split
        $features = $this->extractFeatures($dataPublications);
        // sort
        usort(
            $features,
            function (GeoJsonFeaturePerDataPublication $a, GeoJsonFeaturePerDataPublication $b) {
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

        return $features;
    }

    /**
     * @param  DataPublication[]  $dataPublications
     * @return GeoJsonFeaturePerDataPublication[]
     */
    private function extractFeatures(array $dataPublications)
    {
        // create array of features
        $features = [];
        foreach ($dataPublications as $dataPublication) {
            $featuresCollection = $dataPublication->geojson_featurecollection;
            foreach ($featuresCollection->features as $feature) {
                // Create a feature which includes the datapublication as it gets out of CKAN
                $features[] = new GeoJsonFeaturePerDataPublication($feature, $dataPublication);
            }
        }

        return $features;
    }

    /**
     * @param  GeoJsonFeaturePerDataPublication[]  $features
     * @return array{0: DataPublication[], 1: GeoJsonFeaturePerDataPublication[]
     */
    private function filterInclusive(array $features, BoundingBox $bbox): array
    {
        $inclusiveFeatures = [];
        $inclusivePublications = [];

        foreach ($features as $feature) {
            if (! $bbox->contains($feature->feature->geometry)) {
                continue;
            }

            $inclusiveFeatures[] = $feature;
            $inclusivePublications[$feature->dataPublication->msl_doi] = $feature->dataPublication;
        }

        return [array_values($inclusivePublications), $inclusiveFeatures];
    }
}
