<?php

namespace App\GeoJson;

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
}
