<?php

namespace Tests\Feature\Models\DataPublication\DataPublications;

use App\Models\DataPublication\AlternateIdentifier;
use App\Models\DataPublication\Contributor;
use App\Models\DataPublication\Creator;
use App\Models\DataPublication\DataPublication;
use App\Models\DataPublication\Date;
use App\Models\DataPublication\Description;
use App\Models\DataPublication\Format;
use App\Models\DataPublication\FundingReference;
use App\Models\DataPublication\RelatedIdentifier;
use App\Models\DataPublication\Right;
use App\Models\DataPublication\Size;
use App\Models\DataRepository;
use App\Models\SubDomain;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DataPublicationTest extends TestCase
{
    use refreshDatabase;

    public function test_data_repository_relation()
    {
        $dataPublication = DataPublication::factory()->create();

        $this->assertInstanceOf(DataRepository::class, $dataPublication->dataRepository);
    }

    public function test_description_relation()
    {
        $dataPublication = DataPublication::factory()
            ->has(Description::factory()->count(5))
            ->create();

        $this->assertCount(5, $dataPublication->descriptions);
    }

    public function test_rights_relation()
    {
        $dataPublication = DataPublication::factory()
            ->has(Right::factory()->count(2))
            ->create();

        $this->assertCount(2, $dataPublication->rights);
    }

    public function test_alternate_identifiers_relation()
    {
        $dataPublication = DataPublication::factory()
            ->has(AlternateIdentifier::factory()->count(3))
            ->create();

        $this->assertCount(3, $dataPublication->alternateIdentifiers);
    }

    public function test_related_identifiers_relation()
    {
        $dataPublication = DataPublication::factory()
            ->has(RelatedIdentifier::factory()->count(4))
            ->create();

        $this->assertCount(4, $dataPublication->relatedIdentifiers);
    }

    public function test_funding_references_relation()
    {
        $dataPublication = DataPublication::factory()
            ->has(FundingReference::factory()->count(6))
            ->create();

        $this->assertCount(6,$dataPublication->fundingReferences);
    }

    public function test_dates_relation()
    {
        $dataPublication = DataPublication::factory()
            ->has(Date::factory()->count(2))
            ->create();

        $this->assertCount(2, $dataPublication->dates);
    }

    public function test_creators_relation()
    {
        $dataPublication = DataPublication::factory()
            ->has(Creator::factory()->count(3))
            ->create();

        $this->assertCount(3, $dataPublication->creators);
    }

    public function test_contributors_relation()
    {
        $dataPublication = DataPublication::factory()
            ->has(Contributor::factory()->count(4))
            ->create();

        $this->assertCount(4, $dataPublication->contributors);
    }

    public function test_sizes_relation()
    {
        $dataPublication = DataPublication::factory()
            ->has(Size::factory()->count(8))
            ->create();

        $this->assertCount(8, $dataPublication->sizes);
    }

    public function test_formats_relation()
    {
        $dataPublication = DataPublication::factory()
            ->has(Format::factory()->count(2))
            ->create();

        $this->assertCount(2, $dataPublication->formats);
    }

    public function test_original_sub_domains_relation()
    {
        $dataPublication = DataPublication::factory()
            ->has(SubDomain::factory()->count(3), 'originalSubDomains')
            ->create();

        $this->assertCount(3, $dataPublication->originalSubDomains);
    }

    public function test_interpreted_sub_domains_relation()
    {
        $dataPublication = DataPublication::factory()
            ->has(SubDomain::factory()->count(10), 'interpretedSubDomains')
            ->create();

        $this->assertCount(10, $dataPublication->interpretedSubDomains);
    }
}
