<?php

namespace App\Faker;

use Faker\Factory;
use Faker\Generator;

class DataCite4Faker
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
            'rightsList' => $this->generateRights($this->faker->numberBetween(0, 3)),
            'publicationYear' => $this->faker->year,
            'alternateIdentifiers' => $this->generateAlternateIdentifiers($this->faker->numberBetween(0, 2)),
            'relatedIdentifiers' => $this->generateRelatedIdentifiers($this->faker->numberBetween(0, 10)),
        ];

        $this->datacite['data']['attributes'] = $attributes;

        return $this;
    }

    private function generateRelatedIdentifiers($amount = 0): array
    {
        $relatedIdentifiers = [];

        for($i = 0; $i < $amount; $i++) {
            $relatedIdentifiers[] = $this->generateRelatedIdentifier();
        }

        return $relatedIdentifiers;
    }

    private function generateRelatedIdentifier(): array
    {
        $relatedIdentifier = [
            'relatedIdentifier' => $this->faker->word,
            'relationType' => $this->faker->randomElement(['IsCitedBy', 'Cites', 'IsSupplementTo', 'HasVersion']),
            'relatedIdentifierType' => $this->faker->randomElement(['ARK', 'DOI', 'Handle', 'URL']),
        ];

        if($this->faker->boolean()) {
            $relatedIdentifier['relatedMetadataScheme'] = $this->faker->word;
        }

        if($this->faker->boolean()) {
            $relatedIdentifier['metadata_scheme_uri'] = $this->faker->url();
        }

        if($this->faker->boolean()) {
            $relatedIdentifier['metadata_scheme_type'] = $this->faker->word;
        }

        if($this->faker->boolean()) {
            $relatedIdentifier['resource_type_general'] = $this->faker->randomElement(['Text', 'Dataset', 'Image', 'InteractiveResource', 'Software', 'Collection', 'Event', 'SoftwareApplication', 'Model', 'PhysicalObject', 'Service']);
        }

        return $relatedIdentifier;
    }

    private function generateAlternateIdentifiers($amount = 0): array
    {
        $alternateIdentifiers = [];

        for($i = 0; $i < $amount; $i++) {
            $alternateIdentifiers[] = $this->generateAlternativeIdentifier();
        }

        return $alternateIdentifiers;
    }

    private function generateAlternativeIdentifier(): array
    {
        return [
            'alternateIdentifier' => $this->faker->word,
            'alternateIdentifierType' => $this->faker->randomElement(['ARK', 'DOI', 'Handle', 'URL', 'Other']),
        ];
    }

    private function generateRights($amount = 0): array
    {
        $rights = [];

        for($i = 0; $i < $amount; $i++) {
            $rights[] = $this->generateRight();
        }

        return $rights;
    }

    private function generateRight(): array
    {
        $right = [
            'rights' => $this->faker->randomElement(['CreativeCommonsAttribution', 'CreativeCommonsNonCommercial', 'CreativeCommonsNonDerivative', 'CreativeCommonsNoDerivatives', 'CreativeCommonsShareAlike', 'Other']),
        ];

        if($this->faker->boolean()) {
            $right['rightsUri'] = $this->faker->url;
        }

        if($this->faker->boolean()) {
            $right['rightsIdentifier'] = $this->faker->word;
        }

        if($this->faker->boolean()) {
            $right['rightsIdentifierScheme'] = $this->faker->word;
        }

        if($this->faker->boolean()) {
            $right['schemeURI'] = $this->faker->url;
        }

        if($this->faker->boolean()) {
            $right['lang'] = $this->faker->languageCode;
        }

        return $right;
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
