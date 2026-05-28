<?php

namespace Tests\Feature\Models;

use App\Models\DataRepository;
use App\Models\DatasetCreate;
use App\Models\Import;
use App\Models\SourceDatasetIdentifier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SourceDatasetTest extends TestCase
{
    use RefreshDatabase;

    public function test_source_dataset_identifier_relation(): void
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

        $identifier = $import->sourceDatasetIdentifiers()->create([
            'identifier' => 'oai:test:record-1',
            'extra_payload' => [],
        ]);

        $sourceDataset = $import->sourceDatasets()->create([
            'source_dataset_identifier_id' => $identifier->id,
            'status' => 'ok',
            'source_dataset' => '{}',
        ]);

        $this->assertInstanceOf(SourceDatasetIdentifier::class, $sourceDataset->sourceDatasetIdentifier);
        $this->assertSame($identifier->id, $sourceDataset->sourceDatasetIdentifier->id);
    }

    public function test_import_relation(): void
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

        $identifier = $import->sourceDatasetIdentifiers()->create([
            'identifier' => 'oai:test:record-1',
            'extra_payload' => [],
        ]);

        $sourceDataset = $import->sourceDatasets()->create([
            'source_dataset_identifier_id' => $identifier->id,
            'status' => 'ok',
            'source_dataset' => '{}',
        ]);

        $this->assertInstanceOf(Import::class, $sourceDataset->import);
        $this->assertSame($import->id, $sourceDataset->import->id);
    }

    public function test_dataset_create_relation(): void
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
        $this->assertInstanceOf(DatasetCreate::class, $sourceDataset->datasetCreate);
        $this->assertSame($datasetCreate->id, $sourceDataset->datasetCreate->id);
    }
}
