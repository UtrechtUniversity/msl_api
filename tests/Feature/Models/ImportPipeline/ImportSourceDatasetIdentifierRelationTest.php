<?php

namespace Tests\Feature\Models\ImportPipeline;

use App\Models\DataRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImportSourceDatasetIdentifierRelationTest extends TestCase
{
    use RefreshDatabase;

    public function test_import_source_dataset_identifiers_relation(): void
    {
        $importer = $this->makeImporter();

        $import = $importer->imports()->create([]);

        $identifier = $import->sourceDatasetIdentifiers()->create([
            'identifier' => 'oai:test:record-1',
            'extra_payload' => [],
        ]);

        $this->assertCount(1, $import->fresh()->sourceDatasetIdentifiers);
        $this->assertTrue($import->sourceDatasetIdentifiers->contains($identifier));
        $this->assertSame($import->id, $identifier->import->id);
    }

    private function makeImporter()
    {
        $repository = DataRepository::create([
            'name' => 'Repository',
            'ckan_name' => 'repository',
        ]);

        return $repository->importers()->create([
            'name' => 'Importer',
            'description' => 'Importer description',
            'type' => 'oai',
            'options' => ['identifierProcessor' => ['type' => 'oai']],
        ]);
    }
}
