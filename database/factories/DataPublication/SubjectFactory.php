<?php

namespace Database\Factories\DataPublication;

use App\Models\DataPublication\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class SubjectFactory extends Factory
{
    protected $model = Subject::class;

    public function definition(): array
    {
        return [
            'subject' => $this->faker->word,
            'scheme_uri' => $this->faker->optional(0.5, '')->url,
            'value_uri' => $this->faker->optional(0.5, '')->url,
            'subject_scheme' => $this->faker->optional(0.5, '')->word,
            'classification_code' => $this->faker->optional(0.5, '')->word,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
