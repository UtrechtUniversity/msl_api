<?php

namespace Database\Factories\DataPublication;

use App\Models\DataPublication\format;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class formatFactory extends Factory
{
    protected $model = format::class;

    public function definition(): array
    {
        return [
            'format' => $this->faker->randomElement(["PDF", "XML", "application/pdf", "video/mpeg"]),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
