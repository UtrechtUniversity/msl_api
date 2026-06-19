<?php

namespace Tests\Feature\Models;

use App\Models\DataRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImporterTest extends TestCase
{
    use RefreshDatabase;

    public function test_data_repository_relation(): void
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

        $this->assertInstanceOf(DataRepository::class, $importer->dataRepository);
        $this->assertSame($repository->id, $importer->dataRepository->id);
    }

    public function test_imports_relation(): void
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

        $import = $importer->imports()->create([]);

        $this->assertCount(1, $importer->fresh()->imports);
        $this->assertTrue($importer->imports->contains($import));
    }
}
