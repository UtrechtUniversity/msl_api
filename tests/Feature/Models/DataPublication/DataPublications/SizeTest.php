<?php

namespace Tests\Feature\Models\DataPublication\DataPublications;

use App\Models\DataPublication\DataPublication;
use App\Models\DataPublication\Size;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SizeTest extends TestCase
{
    use RefreshDatabase;

    public function test_data_publication_relation()
    {
        $size = Size::factory()
            ->for(DataPublication::factory())
            ->create();

        $this->assertInstanceOf(DataPublication::class, $size->dataPublication);
    }
}
