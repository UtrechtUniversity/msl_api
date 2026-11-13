<?php

namespace App\Faker;

use Faker\Factory;
use Faker\Generator;

class DataCite3Faker
{
    private array $datacite;

    private Generator $faker;


    public function __construct()
    {
        $this->faker = Factory::create();
        $this->setupBase();
    }

    private function setupBase()
    {
        $this->datacite = [
            "data" =>  [
                "id" => '10.' . $this->faker->randomNumber(5) . '/' . $this->faker->randomNumber(4) . '.' . $this->faker->word,
                "type" => "dois",
                "attributes" => [

                ]
            ]
        ];
    }

    public function populate(): static
    {
        $attributes = [
            'doi' => $this->datacite['data']['id'],
            'titles' => $this->generateTitles($this->faker->numberBetween(1, 3)),
            'descriptions' => $this->generateDescriptions($this->faker->numberBetween(0, 6)),
        ];

        $this->datacite['data']['attributes'] = $attributes;

        return $this;
    }

    private function generateDescriptions($amount = 0): array
    {
        $descriptions = [];

        for($i = 0; $i < $amount; $i++) {
            $descriptions[] = $this->generateDescription();
        }

        return $descriptions;
    }

    private function generateDescription(): array
    {
        $title = [
            'description' => $this->faker->text,
            'descriptionType' => $this->faker->randomElement(['Abstract', 'Methods', 'SeriesInformation', 'TableOfContents', 'TechnicalInfo', 'Other']),
        ];

        if($this->faker->boolean()) {
            $title['lang'] = $this->faker->optional(0.5, '')->languageCode;
        }

        return $title;
    }

    private function generateTitles($amount = 1): array
    {
        $titles = [];

        for($i = 0; $i < $amount; $i++) {
            $titles[] = $this->generateTitle();
        }

        return $titles;
    }
    private function generateTitle(): array
    {
        $title = [
            'title' => $this->faker->sentence,
        ];

        if($this->faker->boolean()) {
            $title['lang'] = $this->faker->optional(0.5, '')->languageCode;
        }

        if($this->faker->boolean()) {
            $title['titleType'] = $this->faker->optional(0.5, '')->randomElement(['AlternativeTitle', 'Subtitle', 'TranslatedTitle', 'Other']);
        }

        return $title;
    }

    public function toString(): false|string
    {
        return json_encode($this->datacite);
    }

    public function toArray(): array
    {
        return $this->datacite;
    }

}
