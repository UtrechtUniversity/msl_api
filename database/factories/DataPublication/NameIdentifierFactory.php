<?php

namespace Database\Factories\DataPublication;

use App\Models\DataPublication\NameIdentifier;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class NameIdentifierFactory extends Factory
{
    protected $model = NameIdentifier::class;

    public function definition(): array
    {
        return [
            'identifier' => $this->faker->randomNumber(5, true),
            'scheme' => $this->faker->optional(0.5, '')->word,
            'uri' => $this->faker->optional(0.5, '')->url,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
