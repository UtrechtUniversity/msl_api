<?php

namespace App\GeoJson\Geometry;

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
     * z coordinate, altitude for geographic coordinate
     */
    public ?float $z;

    /**
     * Constructs new Point object
     *
     * @param  int|float  $x
     * @param  int|float  $y
     * @param  int|float  $z
     */
    public function __construct($x, $y, $z = null)
    {
        if (! is_int($x) && ! is_float($x)) {
            throw new Exception('x coordinate must be integer or float');
        }

        if (! is_int($y) && ! is_float($y)) {
            throw new Exception('y coordinate must be integer or float');
        }

        if ($z !== null && (! is_int($z) && ! is_float($z))) {
            throw new Exception('y coordinate must be integer or float');
        }

        $this->x = $x;
        $this->y = $y;
        $this->z = $z;
    }

    /**
     * returns distance between this and provided point
     */
    public function distanceToPoint(Point $point)
    {
        $dX = $this->x - $point->x;
        $dY = $this->y - $point->y;

        return sqrt($dX * $dX + $dY * $dY);
    }

    public function jsonSerialize(): array
    {

        $coordinates = [$this->x, $this->y];
        if ($this->z !== null) {
            $coordinates[] = $this->z;
        }

        return [
            'type' => 'Point',
            'coordinates' => $coordinates,
        ];
    }
}
