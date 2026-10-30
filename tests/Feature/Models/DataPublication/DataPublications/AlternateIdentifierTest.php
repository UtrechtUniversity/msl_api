<?php

namespace Tests\Feature\Models\DataPublication\DataPublications;

use App\Models\DataPublication\AlternateIdentifier;
use App\Models\DataPublication\DataPublication;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AlternateIdentifierTest extends TestCase
{
    use RefreshDatabase;

    public function test_data_publication_relation()
    {
        $alternateIdentifier = AlternateIdentifier::factory()
            ->for(DataPublication::factory())
            ->create();

        $this->assertInstanceOf(DataPublication::class, $alternateIdentifier->dataPublication);
    }
}
