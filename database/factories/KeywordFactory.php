<?php

namespace Database\Factories;

use App\Models\Keyword;
use App\Models\Vocabulary;
use Illuminate\Database\Eloquent\Factories\Factory;

class KeywordFactory extends Factory
{
    protected $model = Keyword::class;

    public function definition(): array
    {
        return [
            'exclude_domain_mapping' => $this->faker->boolean(),
            'label' => $this->faker->word(),
            'external_uri' => $this->faker->url(),
            'level' => 1,
            'uri' => $this->faker->url(),
            'value' => $this->faker->word(),
            'selection_group_1' => $this->faker->boolean(),
            'selection_group_2' => $this->faker->boolean(),
            'selection_group_3' => $this->faker->boolean(),

            // replace with vocab factory
            'vocabulary_id' => 1,
        ];
    }
}
