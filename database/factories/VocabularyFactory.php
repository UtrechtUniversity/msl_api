<?php

namespace Database\Factories;

use App\Models\Vocabulary;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class VocabularyFactory extends Factory
{
    protected $model = Vocabulary::class;

    public function definition(): array
    {
        return [
            'uri' => $this->faker->url(),
            'name' => $this->faker->name(),
            'display_name' => $this->faker->name(),
            'version' => $this->faker->randomElement(['1.0', '1.1', '2.0']),
            'updated_at' => Carbon::now(),
            'created_at' => Carbon::now(),
        ];
    }
}
