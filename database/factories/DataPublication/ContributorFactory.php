<?php

namespace Database\Factories\DataPublication;

use App\Models\DataPublication\Contributor;
use App\Models\DataPublication\DataPublication;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ContributorFactory extends Factory
{
    protected $model = Contributor::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'type' => $this->faker->randomElement(['ContactPerson', 'DataManager', 'RegistrationAgency']),
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'name_type' => $this->faker->randomElement(['personal', 'organizational']),
            'data_publication_id' => DataPublication::factory(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
