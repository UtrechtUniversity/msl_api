<?php

namespace Tests\Feature\Models\DataPublication\DataPublications;

use App\Models\DataPublication\EnrichedKeyword;
use App\Models\DataPublication\MatchLocation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MatchLocationTest extends TestCase
{
    use RefreshDatabase;

    public function test_data_publication_relation()
    {
        $matchLocation = MatchLocation::factory()
            ->for(EnrichedKeyword::factory()->create())
            ->create();

        $this->assertInstanceOf(EnrichedKeyword::class, $matchLocation->enrichedKeyword);
    }
}
