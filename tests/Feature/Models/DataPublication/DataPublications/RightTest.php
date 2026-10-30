<?php

namespace Tests\Feature\Models\DataPublication\DataPublications;

use App\Models\DataPublication\DataPublication;
use App\Models\DataPublication\Right;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RightTest extends TestCase
{
    use RefreshDatabase;

    public function test_data_publication_relation()
    {
        $right = Right::factory()
            ->for(DataPublication::factory())
            ->create();

        $this->assertInstanceOf(DataPublication::class, $right->dataPublication);
    }
}
