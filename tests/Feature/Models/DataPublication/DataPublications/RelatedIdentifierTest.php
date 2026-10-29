<?php

namespace Tests\Feature\Models\DataPublication\DataPublications;

use App\Models\DataPublication\DataPublication;
use App\Models\DataPublication\RelatedIdentifier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RelatedIdentifierTest extends TestCase
{
    use RefreshDatabase;

    public function test_data_publication_relation()
    {
        $relatedIdentifier = RelatedIdentifier::factory()
            ->for(DataPublication::factory())
            ->create();

        $this->assertInstanceOf(DataPublication::class, $relatedIdentifier->dataPublication);
    }
}
