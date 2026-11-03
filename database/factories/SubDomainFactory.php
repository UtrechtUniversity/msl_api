<?php

namespace Database\Factories;

use App\Models\SubDomain;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class SubDomainFactory extends Factory
{
    protected $model = SubDomain::class;

    public function definition(): array
    {
        return [
            'full_name' => $this->faker->word,
            'short_name' => $this->faker->word,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
