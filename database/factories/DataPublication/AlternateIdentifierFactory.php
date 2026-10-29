<?php

namespace Database\Factories\DataPublication;

use App\Models\DataPublication\AlternateIdentifier;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class AlternateIdentifierFactory extends Factory
{
    protected $model = AlternateIdentifier::class;

    public function definition(): array
    {
        return [
            'identifier' => $this->faker->word,
            'type' => $this->faker->randomElement(['doi', 'handle', 'url', 'other', '']),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
