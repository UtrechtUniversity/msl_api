<?php

namespace App\GeoJson\Feature;

use App\GeoJson\Geometry\Geometry;
use App\GeoJson\Geometry\Point;
use App\GeoJson\Geometry\Polygon;
use Exception;
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

    public static function fromJson($featureFromJson)
    {
        $geometry = null;
        $properties = ['geometry']['properties'] ?? [];
        if (! is_array($properties)) {
            throw new Exception('The geometry properties should have been an array. This is a bug.');
        }
        // TODO throw?
        $geometryFromJson = $featureFromJson['geometry'];

        switch ($geometryFromJson['type']) {
            case 'Point':
                $geometry = Point::fromJson($geometryFromJson);
                break;
            case 'Polygon':
                $geometry = Polygon::fromJson($geometryFromJson);
                break;
            default:
                throw new Exception('The geometry is incorrect. This is a bug.');
        }

        return new self($geometry, $properties);
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
