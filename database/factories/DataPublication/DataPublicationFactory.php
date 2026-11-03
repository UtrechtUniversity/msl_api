<?php

namespace Database\Factories\DataPublication;

use App\Models\DataPublication\DataPublication;
use App\Models\DataRepository;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class DataPublicationFactory extends Factory
{
    protected $model = DataPublication::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'title' => $this->faker->sentence,
            'title_annotated' => $this->faker->sentence,
            'source_landing_page_url' => $this->faker->url,
            'resource_type' => $this->faker->optional(0.5,'')->word,
            'resource_type_general' => $this->faker->optional(0.5,'')->word,
            'doi' => '10.' . $this->faker->randomNumber(5) . '/' . $this->faker->randomNumber(4) . '.' . $this->faker->word,
            'publication_year' => $this->faker->year,
            'language' => $this->faker->optional(0.5,'')->languageCode,
            'datacite_version' => $this->faker->randomElement(['1.0', '']),
            'publisher' => $this->faker->company,
            'citation' => $this->faker->sentence,
            'geojson_feature_collection' => $this->faker->randomElement([null, '{"type":"FeatureCollection","features":[{"type":"Feature","geometry":{"type":"Point","coordinates":[6.69136,53.3235]},"properties":{"name":""}}]}']),
            'data_repository_id' => DataRepository::factory(),
        ];
    }
}
