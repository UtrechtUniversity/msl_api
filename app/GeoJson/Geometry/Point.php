<?php

namespace App\GeoJson\Geometry;

use App\GeoJson\Geometry\Geometry;
use Exception;

class Point extends Geometry
{
    /**
     * x coordinate, longitude for geographic coordinate
     */
    public float $x;

    /**
     * y coordinate, latitude for geographic coordinate
     */
    public float $y;

    /**
     * Constructs new Point object
     * @param int|float $x
     * @param int|float $y
     */
    public function __construct($x, $y)
    {
        if (! is_int($x) && ! is_float($x)) {
            throw new Exception('x coordinate must be integer or float');
        }

        if (! is_int($y) && ! is_float($y)) {
            throw new Exception('y coordinate must be integer or float');
        }

        $this->x = $x;
        $this->y = $y;
    }

    /**
     * returns distance between this and provided point
     * @param Point $point
     */
    public function distanceToPoint(Point $point)
    {
        $dX = $this->x - $point->x;
        $dY = $this->y - $point->y;
        return sqrt($dX * $dX + $dY * $dY);
    }
    
    public function jsonSerialize(): array
    {        
        return [
            'type'=> 'Point',
            'coordinates' => [$this->x, $this->y]
        ];
    }
}