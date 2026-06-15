<?php

namespace App\DataPublications;

class GeoFeatureDataPublication
{
    /**
     * @param  array<int, IsInclusiveDataPublication>  $dataPublications
     * @return void
     */
    public function __construct(
        public array $dataPublications,
        public InsideOverlappingGeoFeatures $features
    ) {}
}
