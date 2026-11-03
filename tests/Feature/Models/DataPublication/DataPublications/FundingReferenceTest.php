<?php

namespace Tests\Feature\Models\DataPublication\DataPublications;

use App\Models\DataPublication\DataPublication;
use App\Models\DataPublication\FundingReference;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FundingReferenceTest extends TestCase
{
    use RefreshDatabase;

    public function test_data_publication_relation()
    {
        $fundingReference = FundingReference::factory()
            ->for(DataPublication::factory())
            ->create();

        $this->assertInstanceOf(DataPublication::class, $fundingReference->dataPublication);
    }
}
