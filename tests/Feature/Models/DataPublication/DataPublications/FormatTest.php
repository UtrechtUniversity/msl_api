<?php

namespace Tests\Feature\Models\DataPublication\DataPublications;

use App\Models\DataPublication\DataPublication;
use App\Models\DataPublication\Format;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FormatTest extends TestCase
{
    use RefreshDatabase;

    public function test_data_publication_relation()
    {
        $format = Format::factory()
            ->for(DataPublication::factory())
            ->create();

        $this->assertInstanceOf(DataPublication::class, $format->dataPublication);
    }
}
