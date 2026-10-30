<?php

namespace Database\Factories\DataPublication;

use App\Models\DataPublication\MatchLocation;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class MatchLocationFactory extends Factory
{
    protected $model = MatchLocation::class;

    public function definition(): array
    {
        return [
            'location' => $this->faker->randomElement(['parent', 'description abstract', 'keyword']),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
