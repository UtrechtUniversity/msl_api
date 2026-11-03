<?php

namespace Tests\Feature\Models\DataPublication\DataPublications;

use App\Models\DataPublication\Contributor;
use App\Models\DataPublication\DataPublication;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContributorTest extends TestCase
{
    use RefreshDatabase;

    public function test_data_publication_relation()
    {
        $contributor = Contributor::factory()
            ->for(DataPublication::factory())
            ->create();

        $this->assertInstanceOf(DataPublication::class, $contributor->dataPublication);
    }

    public function test_name_identifiers_relation()
    {
        $contributor = Contributor::factory()
            ->for(DataPublication::factory())
            ->hasNameIdentifiers(4)
            ->create();

        $this->assertCount(4, $contributor->nameIdentifiers);
    }

    public function test_affiliations_relation()
    {
        $contributor = Contributor::factory()
            ->for(DataPublication::factory())
            ->hasAffiliations(3)
            ->create();

        $this->assertCount(3, $contributor->affiliations);
    }
}
