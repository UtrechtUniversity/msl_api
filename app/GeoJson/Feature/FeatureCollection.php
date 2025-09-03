<?php

namespace App\GeoJson\Feature;

use JsonSerializable;

class FeatureCollection implements JsonSerializable
{
    /**
     * @var array<Feature>
     */
    public array $features;

    /**
     * constructs a new FeatureCollection
     * @param array<Feature> $features
     */
    public function __construct(array $features= [])
    {
        foreach($features as $feature) {
            $this->addFeature($feature);
        }            
    }

    /**
     * Add feature to collection
     * @param Feature $feature
     */
    public function addFeature(Feature $feature)
    {
        $this->features[] = $feature;
    }

    public function jsonSerialize(): array
    {
        $features = [];
        foreach($this->features as $feature) {
            $features[] = $feature->jsonSerialize();
        }

        return [
            "type" => "FeatureCollection",
            "features" => $features
        ];
    }
}