<?php

namespace App\DataPublications;

class GeoFeatureDataPublication
{
    /**
     * @param  array<int, isInclusiveDataPublication>  $dataPublications
     * @return void
     */
    public function __construct(
        public readonly array $dataPublications,
        public readonly InsideOverlappingGeoFeatures $features
    ) {}
}
