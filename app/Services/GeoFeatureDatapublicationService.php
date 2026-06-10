<?php

namespace App\Services;

use App\DataPublications\GeoFeatureDataPublication;
use App\DataPublications\GeoFeaturePerDataPublication;
use App\DataPublications\InsideOverlappingGeoFeatures;
use App\DataPublications\IsInclusiveDataPublication;
use App\GeoJson\BoundingBox;
use App\GeoJson\Geometry\Point;
use App\GeoJson\Geometry\Polygon;
use App\Models\Ckan\DataPublication;
use Exception;

class GeoFeatureDatapublicationService
{
    /**
     * @param  array<int, DataPublication>  $dataPublications
     */
    public function createGeoFeatureDataPublication(array $dataPublications, BoundingBox $bbox): GeoFeatureDataPublication
    {

        //  split + sort features
        $sortedFeatures = $this->sortFeatures($dataPublications);
        // Filter and get dictionary with inclusive datapublications and the inclusive features
        // We are going to use the dictionary in order to add more information about exclusivity/inclusivity
        // in the next step in an easier and more performant way
        [$inclusiveDataPublicationsWithDois, $insideFeatures] = $this->filterInside($sortedFeatures, $bbox);
        // Get exclusive list of datapublications including information about their inclusivity (or not)
        // Reminder: the exclusive list of publications is a superset of the inclusive list.
        $exclusiveDataPublications = $this->getDataPublicationsWithInclusiveInformation($dataPublications, $inclusiveDataPublicationsWithDois);

        return new GeoFeatureDataPublication(dataPublications: $exclusiveDataPublications, features: new InsideOverlappingGeoFeatures(overlappingFeatures: $sortedFeatures, insideFeatures: $insideFeatures));
    }

    /**
     * @param  array<int, DataPublication>  $dataPublications
     * @return array<int,GeoFeaturePerDataPublication>
     */
    private function sortFeatures($dataPublications): array
    {

        // split
        $features = $this->extractFeatures($dataPublications);
        // sort
        usort(
            $features,
            function (GeoFeaturePerDataPublication $a, GeoFeaturePerDataPublication $b) {
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
     * @return array<int,GeoFeaturePerDataPublication>
     */
    private function extractFeatures(array $dataPublications)
    {
        // create array of features
        $features = [];
        foreach ($dataPublications as $dataPublication) {
            $featuresCollection = $dataPublication->geojson_featurecollection;
            foreach ($featuresCollection->features as $feature) {
                // Create a feature which includes the datapublication as it gets out of CKAN
                $features[] = new GeoFeaturePerDataPublication($feature, $dataPublication);
            }
        }

        return $features;
    }

    /**
     * Filter and get back
     * 1. array with inside the bbox geoFeatures
     * 2. dictionary with dois as keys and inclusive datapublications as values
     *
     * @param  array<int,GeoFeaturePerDataPublication>  $features
     * @return array{0: array<string,DataPublication>, 1: array<int,GeoFeaturePerDataPublication>}
     */
    private function filterInside(array $features, BoundingBox $bbox): array
    {
        $insideFeatures = [];
        $inclusiveDataPublicationsWithDois = [];

        foreach ($features as $feature) {
            if (! $bbox->contains($feature->feature->geometry)) {
                continue;
            }

            $insideFeatures[] = $feature;
            if (! isset($inclusiveDataPublicationsWithDois[$feature->dataPublication->msl_doi])) {
                $inclusiveDataPublicationsWithDois[$feature->dataPublication->msl_doi] = $feature->dataPublication;
            }
        }

        return [$inclusiveDataPublicationsWithDois, $insideFeatures];
    }

    /**
     * @param  array<int,DataPublication>  $dataPublications
     * @param  array<string,DataPublication>  $inclusiveDataPublications
     * @return array<int, isInclusiveDataPublication>
     */
    private function getDataPublicationsWithInclusiveInformation(array $dataPublications, array $inclusiveDataPublications): array
    {
        $dataPublicationsToReturn = [];
        foreach ($dataPublications as $dataPublication) {
            array_push($dataPublicationsToReturn, new isInclusiveDataPublication(
                $dataPublication,
                isInclusive: isset($inclusiveDataPublications[$dataPublication->msl_doi])
            ));
        }

        return $dataPublicationsToReturn;
    }
}
