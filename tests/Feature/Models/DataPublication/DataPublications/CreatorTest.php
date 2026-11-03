<?php

namespace Tests\Feature\Models\DataPublication\DataPublications;

use App\Models\DataPublication\Creator;
use App\Models\DataPublication\DataPublication;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreatorTest extends TestCase
{
    use RefreshDatabase;

    public function test_data_publication_relation()
    {
        $creator = Creator::factory()
            ->for(DataPublication::factory())
            ->create();

        $this->assertInstanceOf(DataPublication::class, $creator->dataPublication);
    }

    public function test_name_identifiers_relation()
    {
        $creator = Creator::factory()
            ->for(DataPublication::factory())
            ->hasNameIdentifiers(3)
            ->create();

        $this->assertCount(3, $creator->nameIdentifiers);
    }

    public function test_affiliations_relation()
    {
        $creator = Creator::factory()
            ->for(DataPublication::factory())
            ->hasAffiliations(2)
            ->create();

        $this->assertCount(2, $creator->affiliations);
    }
}
