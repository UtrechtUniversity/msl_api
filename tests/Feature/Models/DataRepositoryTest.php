<?php

namespace Tests\Feature\Models;

use App\Models\DataRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DataRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_importers_relation(): void
    {
        $repository = DataRepository::create([
            'name' => 'Repository',
            'ckan_name' => 'repository',
        ]);

        $importer = $repository->importers()->create([
            'name' => 'Importer',
            'description' => 'Importer description',
            'type' => 'oai',
            'options' => ['identifierProcessor' => ['type' => 'oai']],
        ]);

        $this->assertCount(1, $repository->fresh()->importers);
        $this->assertTrue($repository->importers->contains($importer));
        $this->assertInstanceOf(DataRepository::class, $importer->dataRepository);
        $this->assertSame($repository->id, $importer->dataRepository->id);
    }
}
