<?php

namespace App\DataPublications;

use App\Models\Ckan\DataPublication;

class GeoJsonFeaturesWithDataPublications
{
    /**
     * @param  array<int, DataPublication>  $dataPublications
     * @param  array<int, GeoJsonFeature>  $features
     */
    public function __construct(public readonly array $dataPublications, public readonly array $features) {}
}
