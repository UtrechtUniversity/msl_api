<?php

namespace Tests\Feature\Models;

use App\Models\DataPublication\DataPublication;
use App\Models\SubDomain;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubDomainTest extends TestCase
{
    use RefreshDatabase;

    public function test_data_publications_original_domain_relation()
    {
        $subDomain = SubDomain::factory()
            ->has(DataPublication::factory()->count(3), 'dataPublicationsOriginalDomain')
            ->create();

        $this->assertCount(3, $subDomain->dataPublicationsOriginalDomain);
    }

    public function test_data_publications_interpreted_domain_relation()
    {
        $subDomain = SubDomain::factory()
            ->has(DataPublication::factory()->count(9), 'dataPublicationsInterpretedDomain')
            ->create();

        $this->assertCount(9, $subDomain->dataPublicationsInterpretedDomain);
    }
}
