<?php

namespace App\DataPublications;

class GeoJsonFeatureDataPublication
{
    /**
     * @param  array<int, InclusiveOrNotDataPublication>  $dataPublications
     * @param  array<int, GeoJsonFeaturePerDataPublication>  $features
     * @return void
     */
    public function __construct(
        public readonly array $dataPublications,
        public readonly array $features
    ) {}
}
