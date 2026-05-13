<?php

namespace Tests\Feature\Models\ImportPipeline;

use App\Models\DataRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImportSourceDatasetRelationTest extends TestCase
{
    use RefreshDatabase;

    public function test_import_source_datasets_relation(): void
    {
        $importer = $this->makeImporter();
        $import = $importer->imports()->create([]);

        $identifier = $import->sourceDatasetIdentifiers()->create([
            'identifier' => 'oai:test:record-1',
            'extra_payload' => [],
        ]);

        $sourceDataset = $import->sourceDatasets()->create([
            'source_dataset_identifier_id' => $identifier->id,
            'status' => 'ok',
            'source_dataset' => '{}',
        ]);

        $this->assertCount(1, $import->fresh()->sourceDatasets);
        $this->assertTrue($import->sourceDatasets->contains($sourceDataset));
        $this->assertSame($import->id, $sourceDataset->import->id);
        $this->assertSame($identifier->id, $sourceDataset->sourceDatasetIdentifier->id);
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
