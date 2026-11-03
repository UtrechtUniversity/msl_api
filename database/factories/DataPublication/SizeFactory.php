<?php

namespace Database\Factories\DataPublication;

use App\Models\DataPublication\Size;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class SizeFactory extends Factory
{
    protected $model = Size::class;

    public function definition(): array
    {
        return [
            'size' => $this->faker->randomElement(["15 pages", "6 MB", "45 minutes"]),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
