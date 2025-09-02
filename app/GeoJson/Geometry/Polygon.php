<?php

namespace App\GeoJson\Geometry;

use App\GeoJson\Geometry\Geometry;
use Exception;

class Polygon extends Geometry
{
    /**
     * @var array<Point>
     */
    public array $points;

    /**
     * @param array<Point>
     */
    public function __construct(array $points)
    {        
        foreach($points as $point) {
            if(!$point instanceof Point) {
                throw new Exception('Invalid argument(s) provided, only point values allowed');
            }

            $this->points[] = $point;
        }

        // a valid polygon requires at least 4 points
        if(count($points) < 4) {
            throw new Exception('Polygon requires at least 4 points');
        }

        // The first and lasts point coordinates must match
        $firstPoint = $points[0];
        $lastPoint = $points[count($points) - 1];

        if(($firstPoint->x !== $lastPoint->x )|| ($firstPoint->y !== $lastPoint->y )) {
            throw new Exception('First and last point must be equal for valid polygon');
        }
    }

    public function jsonSerialize(): array
    {
        $coordinates = [];
        foreach($this->points as $point) {
            $coordinates[] = [$point->x, $point->y];
        }

        return [
            'type' => 'Polygon',
            'coordinates' => [$coordinates]
        ];
    }
}