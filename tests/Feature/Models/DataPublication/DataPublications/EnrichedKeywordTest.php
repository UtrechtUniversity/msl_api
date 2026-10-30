<?php

namespace Tests\Feature\Models\DataPublication\DataPublications;

use App\Models\DataPublication\DataPublication;
use App\Models\DataPublication\EnrichedKeyword;
use App\Models\DataPublication\MatchLocation;
use App\Models\Keyword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnrichedKeywordTest extends TestCase
{
    use RefreshDatabase;

    public function test_data_publication_relation()
    {
        $enrichedKeyword = EnrichedKeyword::factory()
            ->for(DataPublication::factory()->create())
            ->create();

        $this->assertInstanceOf(DataPublication::class, $enrichedKeyword->dataPublication);
    }

    public function test_keyword_relation()
    {
        $enrichedKeyword = EnrichedKeyword::factory()
            ->for(Keyword::factory()->create())
            ->create();

        $this->assertInstanceOf(Keyword::class, $enrichedKeyword->keyword);
    }

    public function test_match_locations_relation()
    {
        $enrichedKeyword = EnrichedKeyword::factory()
            ->has(MatchLocation::factory()->count(5))
            ->create();

        $this->assertCount(5, $enrichedKeyword->matchLocations);
    }

    public function test_child_keywords_relation()
    {
        $enrichedKeyword = EnrichedKeyword::factory()
            ->has(Keyword::factory()->count(2), 'childKeywords')
            ->create();

        $this->assertCount(2, $enrichedKeyword->childKeywords);
    }
}
