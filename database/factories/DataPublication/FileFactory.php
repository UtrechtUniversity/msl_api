<?php

namespace Database\Factories\DataPublication;

use App\Models\DataPublication\DataPublication;
use App\Models\DataPublication\File;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class FileFactory extends Factory
{
    protected $model = File::class;

    public function definition(): array
    {
        return [
            'file_name' => $this->faker->word,
            'download_url' => $this->faker->url,
            'extension' => $this->faker->fileExtension(),
            'type' => $this->faker->randomElement(['file', 'folder']),
            'origin_timestamp_created' => $this->faker->optional()->dateTimeBetween('-1 year', 'now'),
            'origin_timestamp_updated' => $this->faker->optional()->dateTimeBetween('-1 year', 'now'),
            'origin_full_path' => $this->faker->optional(0.5, '')->filePath(),
            'checksum' => $this->faker->optional()->md5,
            'size' => $this->faker->optional()->randomNumber(5, true),
            'data_publication_id' => DataPublication::factory(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
