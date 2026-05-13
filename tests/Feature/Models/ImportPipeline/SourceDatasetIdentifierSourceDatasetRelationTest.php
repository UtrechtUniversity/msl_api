<?php

namespace Tests\Feature\Models\ImportPipeline;

use App\Models\DataRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SourceDatasetIdentifierSourceDatasetRelationTest extends TestCase
{
    use RefreshDatabase;

    public function test_source_dataset_identifier_source_dataset_relation(): void
    {
        $importer = $this->makeImporter();
        $import = $importer->imports()->create([]);

        $identifier = $import->sourceDatasetIdentifiers()->create([
            'identifier' => 'oai:test:record-1',
            'extra_payload' => [],
        ]);

        $sourceDataset = $identifier->sourceDataset()->create([
            'import_id' => $import->id,
            'status' => 'ok',
            'source_dataset' => '{}',
        ]);

        $this->assertSame($identifier->id, $sourceDataset->sourceDatasetIdentifier->id);
        $this->assertNotNull($identifier->fresh()->sourceDataset);
        $this->assertSame($sourceDataset->id, $identifier->sourceDataset->id);
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
