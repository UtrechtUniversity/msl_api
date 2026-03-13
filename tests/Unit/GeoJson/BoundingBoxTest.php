<?php

namespace Tests\Unit\GeoJson;

use App\GeoJson\BoundingBox;
use App\GeoJson\Geometry\Point;
use App\GeoJson\Geometry\Polygon;
use Tests\TestCase;

class BoundingBoxTest extends TestCase
{
    public function test_contains_point(): void
    {
        $bbox = new BoundingBox(minX: 1, maxX: 4, minY: 1, maxY: 4);
        $pointContained = new Point(x: 2, y: 3);
        $this->assertTrue($bbox->contains($pointContained));
    }

    public function test_does_not_contain_point(): void
    {
        $bbox = new BoundingBox(minX: 1, maxX: 4, minY: 1, maxY: 4);
        $pointNotContained = new Point(x: 2, y: 5);
        $this->assertFalse($bbox->contains($pointNotContained));
    }

    public function test_contains_polygon(): void
    {
        $bbox = new BoundingBox(minX: 0, maxX: 10, minY: 0, maxY: 10);
        $pointContained = new Polygon(points: [new Point(3, 3), new Point(7, 3), new Point(5, 7), new Point(3, 3)]);
        $this->assertTrue($bbox->contains($pointContained));
    }

    public function test_does_not_contain_polygon(): void
    {
        $bbox = new BoundingBox(minX: 1, maxX: 4, minY: 1, maxY: 4);
        $pointNotContained = new Polygon(points: [new Point(3, 3), new Point(11, 3), new Point(5, 7), new Point(3, 3)]);
        $this->assertFalse($bbox->contains($pointNotContained));
    }
}
