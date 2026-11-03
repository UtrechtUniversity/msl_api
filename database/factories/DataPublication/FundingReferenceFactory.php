<?php

namespace Database\Factories\DataPublication;

use App\Models\DataPublication\FundingReference;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class FundingReferenceFactory extends Factory
{
    protected $model = FundingReference::class;

    public function definition(): array
    {
        return [
            'funder_name' => $this->faker->company,
            'funder_identifier' => $this->faker->optional(0.5, '')->randomNumber(5, true),
            'funder_identifier_type' => $this->faker->optional(0.5, '')->word,
            'scheme_uri' => $this->faker->optional(0.5, '')->url,
            'award_number' => $this->faker->optional(0.5, '')->randomNumber(5, true),
            'award_uri' => $this->faker->optional(0.5, '')->url,
            'award_title' => $this->faker->optional(0.5, '')->word,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
