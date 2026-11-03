<?php

namespace Database\Factories\DataPublication;

use App\Models\DataPublication\DataPublication;
use App\Models\DataPublication\EnrichedKeyword;
use App\Models\Keyword;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class EnrichedKeywordFactory extends Factory
{
    protected $model = EnrichedKeyword::class;

    public function definition(): array
    {
        return [
            'data_publication_id' => DataPublication::factory(),
            'keyword_id' => Keyword::factory(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
