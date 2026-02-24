<?php

namespace App\DataPublications;

use App\GeoJson\Feature\Feature;
use App\Models\Ckan\DataPublication;

class GeoJsonFeaturePerDataPublication
{
    public function __construct(
        public readonly Feature $feature,
        public readonly DataPublication $dataPublication
    ) {}
}
