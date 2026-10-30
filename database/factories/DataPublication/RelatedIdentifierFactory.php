<?php

namespace Database\Factories\DataPublication;

use App\Models\DataPublication\RelatedIdentifier;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class RelatedIdentifierFactory extends Factory
{
    protected $model = RelatedIdentifier::class;

    public function definition(): array
    {
        return [
            'identifier' => $this->faker->word,
            'type' => $this->faker->randomElement(['doi', 'handle', 'url', 'other', '']),
            'relation_type' => $this->faker->optional(0.5, '')->word,
            'metadata_scheme' => $this->faker->optional(0.5, '')->word,
            'metadata_scheme_uri' => $this->faker->optional(0.5, '')->word,
            'metadata_scheme_type' => $this->faker->optional(0.5, '')->word,
            'resource_type_general' => $this->faker->optional(0.5, '')->word,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
