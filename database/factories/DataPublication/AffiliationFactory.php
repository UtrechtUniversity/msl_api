<?php

namespace Database\Factories\DataPublication;

use App\Models\DataPublication\Affiliation;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class AffiliationFactory extends Factory
{
    protected $model = Affiliation::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'identifier' => $this->faker->randomNumber(5, true),
            'scheme' => $this->faker->optional(0.5, '')->word,
            'uri' => $this->faker->optional(0.5, '')->url,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
