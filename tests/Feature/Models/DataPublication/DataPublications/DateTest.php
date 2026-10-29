<?php

namespace Tests\Feature\Models\DataPublication\DataPublications;

use App\Models\DataPublication\DataPublication;
use App\Models\DataPublication\Date;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DateTest extends TestCase
{
    use RefreshDatabase;

    public function test_data_publication_relation()
    {
        $date = Date::factory()
            ->for(DataPublication::factory())
            ->create();

        $this->assertInstanceOf(DataPublication::class, $date->dataPublication);
    }
}
