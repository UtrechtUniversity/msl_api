<?php

namespace Tests\Feature\Models\DataPublication\DataPublications;

use App\Models\DataPublication\AlternateIdentifier;
use App\Models\DataPublication\DataPublication;
use App\Models\DataPublication\Description;
use App\Models\DataPublication\RelatedIdentifier;
use App\Models\DataPublication\Right;
use App\Models\DataRepository;
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
}
