<?php

namespace Database\Factories\DataPublication;

use App\Models\DataPublication\Right;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class RightFactory extends Factory
{
    protected $model = Right::class;

    public function definition(): array
    {
        return [
            'right' => $this->faker->randomElement(['public', 'restricted', 'non-public']),
            'uri' => $this->faker->url,
            'identifier' => $this->faker->optional(0.5, '')->randomNumber(5, true),
            'identifier_scheme' => $this->faker->optional(0.5,'')->word,
            'identifier_scheme_uri' => $this->faker->optional(0.5, '')->url,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
