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
            'rightsList' => $this->generateRights($this->faker->numberBetween(0, 3)),
            'publicationYear' => $this->faker->year,
            'alternateIdentifiers' => $this->generateAlternateIdentifiers($this->faker->numberBetween(0, 2)),
            'relatedIdentifiers' => $this->generateRelatedIdentifiers($this->faker->numberBetween(0, 10)),
            'url' => $this->faker->url,
            'language' => $this->faker->optional(0.5, '')->languageCode,
            'dates' => $this->generateDates($this->faker->numberBetween(0, 3)),
            'publisher' => $this->faker->company,
            'creators' => $this->generateCreators($this->faker->numberBetween(1, 10)),
            'version' => $this->faker->optional(0.5, '')->randomElement(['1.0', '2.0', 'first']),
            'types' => $this->generateResourceTypes(),
        ];

        $this->datacite['data']['attributes'] = $attributes;

        return $this;
    }

    private function generateResourceTypes(): array
    {
        $resourceTypes = [
            'resourceType' => $this->faker->word,
        ];

        if($this->faker->boolean()) {
            $resourceTypes['resourceTypeGeneral'] = $this->faker->randomElement(['Text', 'Dataset', 'Image', 'InteractiveResource', 'Software', 'Collection', 'Event', 'SoftwareApplication', 'Model', 'PhysicalObject', 'Service']);
        }

        return $resourceTypes;
    }

    private function generateCreators($amount = 0): array
    {
        $creators = [];

        for($i = 0; $i < $amount; $i++) {
            $creators[] = $this->generateCreator();
        }

        return $creators;
    }

    private function generateCreator(): array
    {
        $creator = [
            'name' => $this->faker->name,
        ];

        if($this->faker->boolean()) {
            $creator['nameType'] = $this->faker->randomElement(['Personal', 'Organizational']);
        }

        if($this->faker->boolean()) {
            $creator['givenName'] = $this->faker->firstName;
        }

        if($this->faker->boolean()) {
            $creator['familyName'] = $this->faker->lastName;
        }

        if($this->faker->boolean()) {
            $creator['nameIdentifiers'] = $this->generateNameIdentifiers($this->faker->numberBetween(1, 2));
        }

        if($this->faker->boolean()) {
            $creator['affiliation'] = $this->generateAffiliations($this->faker->numberBetween(1, 2));
        }

        return $creator;
    }

    private function generateAffiliations($amount = 0): array
    {
        $affiliations = [];

        for($i = 0; $i < $amount; $i++) {
            $affiliations[] = $this->generateAffiliation();
        }

        return $affiliations;
    }

    private function generateAffiliation(): array|string
    {
        return $this->faker->company;
    }
    private function generateNameIdentifiers($amount = 0): array
    {
        $nameIdentifiers = [];

        for($i = 0; $i < $amount; $i++) {
            $nameIdentifiers[] = $this->generateNameIdentifier();
        }

        return $nameIdentifiers;
    }

    private function generateNameIdentifier(): array|string
    {
        $nameIdentifier = [
            'nameIdentifier' => $this->faker->word,
            'nameIdentifierScheme' => $this->faker->randomElement(['ORCID', 'ISNI', 'ROR']),
        ];

        if($this->faker->boolean()) {
            $nameIdentifier['schemeURI'] = $this->faker->url;
        }

        return $nameIdentifier;
    }

    private function generateDates($amount = 0): array
    {
        $dates = [];

        for($i = 0; $i < $amount; $i++) {
            $dates[] = $this->generateDate();
        }

        return $dates;
    }

    private function generateDate(): array
    {
        $date = [
            'date' => $this->faker->date,
            'dateType' => $this->faker->randomElement(['Collected', 'Created', 'Issued', 'Submitted', 'Updated', 'Accepted', 'Published']),
        ];

        if($this->faker->boolean()) {
            $date['dateInformation'] = $this->faker->word;
        }

        return $date;
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
