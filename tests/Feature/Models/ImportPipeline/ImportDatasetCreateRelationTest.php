<?php

namespace Tests\Feature\Models\ImportPipeline;

use App\Models\DataRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImportDatasetCreateRelationTest extends TestCase
{
    use RefreshDatabase;

    public function test_import_dataset_creates_relation(): void
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

        $datasetCreate = $import->datasetCreates()->create([
            'source_dataset_id' => $sourceDataset->id,
            'dataset_type' => 'dataset',
            'dataset' => ['title' => 'Test'],
            'response_body' => '',
        ]);

        $this->assertCount(1, $import->fresh()->datasetCreates);
        $this->assertTrue($import->datasetCreates->contains($datasetCreate));
        $this->assertSame($import->id, $datasetCreate->import->id);
        $this->assertSame($sourceDataset->id, $datasetCreate->sourceDataset->id);
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
