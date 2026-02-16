<?php

namespace App\DataPublications;

use App\GeoJson\Feature\Feature;
use App\Models\Ckan\DataPublication;

class GeoJsonFeature
{
    public function __construct(public readonly Feature $feature, public readonly DataPublication $dataPublication) {}
}
