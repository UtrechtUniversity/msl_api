<?php

namespace App\DataPublications;

class InclusiveExclusiveGeoJsonFeatureDataPublication
{
    public function __construct(
        public readonly GeoJsonFeatureDataPublication $exclusiveFeaturesWithDataPublications,
        public readonly GeoJsonFeatureDataPublication $inclusiveFeaturesWithDataPublications
    ) {}
}
