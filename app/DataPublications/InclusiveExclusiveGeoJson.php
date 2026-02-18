<?php

namespace App\DataPublications;

class InclusiveExclusiveGeoJson
{
    public function __construct(
        public readonly GeoJsonFeatureDataPublication $exclusiveFeaturesWithDataPublications,
        public readonly GeoJsonFeatureDataPublication $inclusiveFeaturesWithDataPublications
    ) {}
}
