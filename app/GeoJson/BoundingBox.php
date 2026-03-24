<?php

namespace App\GeoJson;

use App\GeoJson\Geometry\Geometry;
use App\GeoJson\Geometry\Point;
use App\GeoJson\Geometry\Polygon;

class BoundingBox
{
    public float $minX;

    public float $minY;

    public float $maxX;

    public float $maxY;

    public function __construct(float $minX, float $minY, float $maxX, float $maxY)
    {
        $this->minX = $minX;
        $this->minY = $minY;
        $this->maxX = $maxX;
        $this->maxY = $maxY;
    }

    public function contains(Geometry $geometry)
    {

        if ($geometry instanceof Point) {
            return ($geometry->x >= $this->minX) && ($geometry->x <= $this->maxX) && ($geometry->y >= $this->minY) && ($geometry->y <= $this->maxY);
        }

        if ($geometry instanceof Polygon) {
            foreach ($geometry->points as $point) {
                $contains = $this->contains($point);
                if (! $contains) {
                    return false;
                }
            }

            return true;
        }
    }
}
