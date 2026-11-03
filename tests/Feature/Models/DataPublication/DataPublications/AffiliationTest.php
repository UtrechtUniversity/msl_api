<?php

namespace Tests\Feature\Models\DataPublication\DataPublications;

use App\Models\DataPublication\Affiliation;
use App\Models\DataPublication\Contributor;
use App\Models\DataPublication\Creator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AffiliationTest extends TestCase
{
    use RefreshDatabase;

    public function test_affiliatable_relation()
    {
        $affiliations[] = Affiliation::factory()
            ->for(Creator::factory(), 'affiliatable')
            ->create();

        $affiliations[] = Affiliation::factory()
            ->for(Contributor::factory(), 'affiliatable')
            ->create();

        $this->assertInstanceOf(Creator::class, $affiliations[0]->affiliatable);
        $this->assertInstanceOf(Contributor::class, $affiliations[1]->affiliatable);
    }
}
