<?php

namespace Database\Factories;

use App\Models\DataRepository;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class DataRepositoryFactory extends Factory
{
    protected $model = DataRepository::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'ckan_name' => $this->faker->slug,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
