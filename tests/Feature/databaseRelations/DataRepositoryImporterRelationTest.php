<?php

namespace Tests\Feature\databaseRelations;

use App\Models\DataRepository;
use App\Models\Importer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DataRepositoryImporterRelationTest extends TestCase
{
    use RefreshDatabase;

    public function test_data_repository_and_importer_relation(): void
    {
        $repository = DataRepository::create([
            'name' => 'Repository',
            'ckan_name' => 'repository',
        ]);

        $importer = Importer::create([
            'name' => 'Importer',
            'description' => 'Importer description',
            'type' => 'oai',
            'options' => ['identifierProcessor' => ['type' => 'oai']],
            'data_repository_id' => $repository->id,
        ]);

        $this->assertCount(1, $repository->importers);
        $this->assertTrue($repository->importers->contains($importer));
        $this->assertSame($repository->id, $importer->data_repository->id);
    }
}
