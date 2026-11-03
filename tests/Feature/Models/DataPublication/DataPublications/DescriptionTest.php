<?php

namespace Tests\Feature\Models\DataPublication\DataPublications;

use App\Models\DataPublication\DataPublication;
use App\Models\DataPublication\Description;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DescriptionTest extends TestCase
{
    use RefreshDatabase;

    public function test_data_publication_relation()
    {
        $description = Description::factory()
            ->for(DataPublication::factory())
            ->create();

        $this->assertInstanceOf(DataPublication::class, $description->dataPublication);
    }
}
