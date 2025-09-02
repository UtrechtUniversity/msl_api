<?php

namespace App\GeoJson\Geometry;

use App\GeoJson\Geometry\Geometry;
use Exception;
use JsonSerializable;

class Collection implements JsonSerializable
{

    /**
     * @var array<Geometry>
     */
    public array $geometries;

    /**
     * Constructs a new Collection
     * @param array<Geometry> $geometries
     */
    public function __construct(array $geometries)
    {
        foreach($geometries as $geometry) {            
            $this->addGeometry($geometry);
        }
    }

    /**
     * Add geometry to collection
     * @param Geometry $geometry
     */
    public function addGeometry(Geometry $geometry)
    {
        if(!$geometry instanceof Geometry) {
                throw new Exception('Only Geometry objects may be included in geometry collection');
        }

        $this->geometries[] = $geometry;
    }

    public function jsonSerialize(): array
    {
        $geometries = [];
        foreach($this->geometries as $geometry) {
            $geometries[] = $geometry->jsonSerialize();
        }

        return [
            "type" => "GeometryCollection",
            "geometries" => $geometries
        ];
    }
}