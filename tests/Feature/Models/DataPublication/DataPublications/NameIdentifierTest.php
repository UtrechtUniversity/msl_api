<?php

namespace Tests\Feature\Models\DataPublication\DataPublications;

use App\Models\DataPublication\Contributor;
use App\Models\DataPublication\Creator;
use App\Models\DataPublication\NameIdentifier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NameIdentifierTest extends TestCase
{
    use RefreshDatabase;

    public function test_identifiable_relation()
    {
        $nameIdentifiers[] = NameIdentifier::factory()
            ->for(Creator::factory(), 'identifiable')
            ->create();

        $nameIdentifiers[] = NameIdentifier::factory()
            ->for(Contributor::factory(), 'identifiable')
            ->create();

        $this->assertInstanceOf(Creator::class, $nameIdentifiers[0]->identifiable);
        $this->assertInstanceOf(Contributor::class, $nameIdentifiers[1]->identifiable);
    }
}
