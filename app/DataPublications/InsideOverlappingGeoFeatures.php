<?php

namespace App\DataPublications;

class InsideOverlappingGeoFeatures
{
    /**
     * @param  array<int, GeoFeaturePerDataPublication>  $overlappingFeatures
     * @param  array<int, GeoFeaturePerDataPublication>  $insideFeatures
     * @return void
     */
    public function __construct(
        public readonly array $overlappingFeatures,
        public readonly array $insideFeatures
    ) {}
}
