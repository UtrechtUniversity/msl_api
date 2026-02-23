<?php

namespace App\DataPublications;

use App\Models\Ckan\DataPublication;

class GeoJsonFeatureDataPublication
{
    /**
     * @param  array<int, DataPublication>  $dataPublications
     * @param  array<int, GeoJsonFeaturePerDataPublication>  $features
     * @return void
     */
    public function __construct(
        public readonly array $dataPublications,
        public readonly array $features
    ) {}
}
