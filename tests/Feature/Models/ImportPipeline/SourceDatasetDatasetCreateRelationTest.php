<?php

namespace Tests\Feature\Models\ImportPipeline;

use App\Models\DataRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SourceDatasetDatasetCreateRelationTest extends TestCase
{
    use RefreshDatabase;

    public function test_source_dataset_dataset_create_relation(): void
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

        $datasetCreate = $sourceDataset->datasetCreate()->create([
            'import_id' => $import->id,
            'dataset_type' => 'dataset',
            'dataset' => ['title' => 'Test'],
            'response_body' => '',
        ]);

        $this->assertNotNull($sourceDataset->fresh()->datasetCreate);
        $this->assertSame($datasetCreate->id, $sourceDataset->datasetCreate->id);
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
