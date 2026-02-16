<?php

namespace App\DataPublications;

class InclusiveExclusiveGeoJson
{
    public function __construct(
        public readonly GeoJsonFeaturesWithDataPublications $exclusiveFeaturesWithDataPublications,
        public readonly GeoJsonFeaturesWithDataPublications $inclusiveFeaturesWithDataPublications
    ) {}
}
