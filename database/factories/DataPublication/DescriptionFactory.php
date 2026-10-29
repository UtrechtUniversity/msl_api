<?php

namespace Database\Factories\DataPublication;

use App\Models\DataPublication\Description;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class DescriptionFactory extends Factory
{
    protected $model = Description::class;

    public function definition(): array
    {
        return [
            'description' => $this->faker->text,
            'annotated' => $this->faker->text,
            'language' => $this->faker->optional(0.5, '')->languageCode,
            'type' => $this->faker->randomElement(['abstract', 'methods', 'series information', 'table of contents', 'other']),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
