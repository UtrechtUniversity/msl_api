<?php

namespace Tests\Feature\Models\DataPublication\DataPublications;

use App\Models\DataPublication\DataPublication;
use App\Models\DataPublication\Description;
use App\Models\DataRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DataPublicationTest extends TestCase
{
    use refreshDatabase;

    public function test_data_repository_relation()
    {
        $dataPublication = DataPublication::factory()->create();

        $this->assertInstanceOf(DataRepository::class, $dataPublication->dataRepository);
    }

    public function test_description_relation()
    {
        $dataPublication = DataPublication::factory()
            ->has(Description::factory()->count(5))
            ->create();

        $this->assertCount(5, $dataPublication->descriptions);
    }
}
