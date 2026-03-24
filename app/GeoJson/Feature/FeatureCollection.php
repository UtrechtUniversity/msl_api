<?php

namespace App\GeoJson\Feature;

use Exception;
use JsonSerializable;

class FeatureCollection implements JsonSerializable
{
    /**
     * @var array<Feature>
     */
    public array $features = [];

    /**
     * constructs a new FeatureCollection
     *
     * @param  array<Feature>  $features
     */
    public function __construct(array $features = [])
    {
        foreach ($features as $feature) {
            $this->addFeature($feature);
        }
    }

    public static function fromString(string $geoJsonString)
    {
        $json = json_decode($geoJsonString, associative: true);
        $features = [];

        if (! $json) {
            return new self($features);
        }
        // Expected json:
        // {
        //   "type": "FeatureCollection",
        //   "features": [...]
        // }
        $type = $json['type'];
        if ($type !== 'FeatureCollection') {
            throw new Exception(
                "The string to convert to feature collection contains the type '$type', which is incorrect. This is a bug."
            );
        }
        $featuresFromJson = $json['features'];
        if (! is_array($featuresFromJson)) {
            throw new Exception("The 'features' property in the feature collection string is not an array. This is a bug.");
        }

        foreach ($featuresFromJson as $featureFromJson) {
            $features[] = Feature::fromJson($featureFromJson);
        }

        return new self($features);
    }

    /**
     * Add feature to collection
     */
    public function addFeature(Feature $feature)
    {
        $this->features[] = $feature;
    }

    public function jsonSerialize(): array
    {
        $features = [];
        foreach ($this->features as $feature) {
            $features[] = $feature->jsonSerialize();
        }

        return [
            'type' => 'FeatureCollection',
            'features' => $features,
        ];
    }
}
