<?php

namespace App\GeoJson\Geometry;

use App\GeoJson\BoundingBox;
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

    /**
     * Calculate area of polygon
     */
    public function area(): float
    {
        $area = 0.0;

        $xCoords = [];
        $yCoords = [];

        for($i = 0; $i < count($this->points); $i++)
        {
            $xCoords[] = $this->points[$i]->x;
            $yCoords[] = $this->points[$i]->y;
        }
        
        $j = count($xCoords) - 1;
        for ($i = 0; $i < count($xCoords); $i++)
        {
            $area += ($xCoords[$j] + $xCoords[$i]) * ($yCoords[$j] - $yCoords[$i]);
                    
            $j = $i; 
        }

        return abs($area / 2.0);
    }

    /**
     * Create Polygon from BoundingBox
     * @param BoundingBox $boundingBox
     */
    public static function createFromBoundingBox(BoundingBox $boundingBox)
    {    
        return new self([
            new Point($boundingBox->minX, $boundingBox->maxY),
            new Point($boundingBox->minX, $boundingBox->minY),
            new Point($boundingBox->maxX, $boundingBox->minY),
            new Point($boundingBox->maxX, $boundingBox->maxY),
            new Point($boundingBox->minX, $boundingBox->maxY),
        ]);
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