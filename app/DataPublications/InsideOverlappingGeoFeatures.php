<?php

namespace App\DataPublications;

use App\GeoJson\Feature\Feature;

class InsideOverlappingGeoFeatures
{
    /**
     * @param  array<int, Feature>  $overlappingFeatures
     * @param  array<int, Feature>  $insideFeatures
     * @return void
     */
    public function __construct(
        public array $overlappingFeatures,
        public array $insideFeatures
    ) {}
}
