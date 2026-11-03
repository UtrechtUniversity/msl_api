<?php

namespace Database\Factories\DataPublication;

use App\Models\DataPublication\Date;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class DateFactory extends Factory
{
    protected $model = Date::class;

    public function definition(): array
    {
        return [
            'date' => $this->faker->randomElement(['2022-01-01', '2004-03-02/2005-06-02', '']),
            'type' => $this->faker->randomElement(['accepted', 'available', 'collected', 'created', 'issued', 'submitted', 'updated', '']),
            'information' => $this->faker->optional(0.5, '')->word,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
