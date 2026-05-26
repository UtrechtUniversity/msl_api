<?php

namespace App\Services;

use App\DataPublications\GeoJsonFeatureDataPublication;
use App\DataPublications\GeoJsonFeaturePerDataPublication;
use App\DataPublications\InclusiveExclusiveGeoJsonFeatureDataPublication;
use App\DataPublications\InclusiveOrNotDataPublication;
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

        //  split + sort features
        $sortedFeatures = $this->sortFeatures($dataPublications);
        // Filter and get dictionary with inclusive datapublications and the inclusive features
        // We are going to use the dictionary in order to add more information about exclusivity/inclusivity
        // in the next step in an easier and more performant way
        [$inclusiveDataPublicationsWithDois, $inclusiveFeatures] = $this->filterInclusive($sortedFeatures, $bbox);

        // Get exclusive list of datapublications including information about their inclusivity (or not)
        // Reminder: the exclusive list of publications is a superset of the inclusive list.
        $exclusiveDataPublications = $this->getDataPublicationsWithInclusiveInformation($dataPublications, $inclusiveDataPublicationsWithDois);
        // Get the inclusive datapublications including information about their inclusivity.
        $inclusivePublications = array_map(function (DataPublication $dataPublication) {
            return new InclusiveOrNotDataPublication($dataPublication, inclusive: true);
        }, array_values($inclusiveDataPublicationsWithDois));

        return new InclusiveExclusiveGeoJsonFeatureDataPublication(
            exclusiveFeaturesWithDataPublications: new GeoJsonFeatureDataPublication(
                dataPublications: $exclusiveDataPublications,
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
     * @return array<int,GeoJsonFeaturePerDataPublication>
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
     * Extract features from geo collections in datapublications
     *
     * @param  array<int,DataPublication>  $dataPublications
     * @return array<int,GeoJsonFeaturePerDataPublication>
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
     * Filter and get back
     * 1. array with inclusive geoFeatures
     * 2. dictionary with dois as keys and inclusive datapublications as values
     *
     * @param  array<int,GeoJsonFeaturePerDataPublication>  $features
     * @return array{0: array<string,DataPublication>, 1: array<int,GeoJsonFeaturePerDataPublication>}
     */
    private function filterInclusive(array $features, BoundingBox $bbox): array
    {
        $inclusiveFeatures = [];
        $inclusiveDataPublicationsWithDois = [];

        foreach ($features as $feature) {
            if (! $bbox->contains($feature->feature->geometry)) {
                continue;
            }

            $inclusiveFeatures[] = $feature;
            $inclusiveDataPublicationsWithDois[$feature->dataPublication->msl_doi] = $feature->dataPublication;
        }

        return [$inclusiveDataPublicationsWithDois, $inclusiveFeatures];
    }

    /**
     * @param  array<int,DataPublication>  $dataPublications
     * @param  array<string,DataPublication>  $inclusiveDataPublications
     * @return array<int, InclusiveOrNotDataPublication>
     */
    private function getDataPublicationsWithInclusiveInformation(array $dataPublications, array $inclusiveDataPublications): array
    {
        $dataPublicationsToReturn = [];
        foreach ($dataPublications as $dataPublication) {
            array_push($dataPublicationsToReturn, new InclusiveOrNotDataPublication(
                $dataPublication,
                inclusive: array_key_exists($dataPublication->msl_doi, $inclusiveDataPublications)
            ));
        }

        return $dataPublicationsToReturn;
    }
}
