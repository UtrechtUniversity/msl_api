<?php

namespace App\GeoJson\Feature;

use App\GeoJson\Geometry\Geometry;
use JsonSerializable;
use stdClass;

class Feature implements JsonSerializable
{
    /**
     * Geometry included in feature
     */
    public Geometry $geometry;

    /**
     * properties describing geometry
     */
    public array $properties;

    public function __construct(Geometry $geometry, array $properties = [])
    {
        $this->geometry = $geometry;
        $this->properties = $properties;
    }

    public function jsonSerialize(): array
    {
        $return['type'] = 'Feature';
        $return['geometry'] = $this->geometry->jsonSerialize();
        $return['properties'] = $this->properties;

        if (count($return['properties']) === 0) {
            $return['properties'] = new stdClass;
        }

        return $return;
    }
}
