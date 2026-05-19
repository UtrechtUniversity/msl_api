<?php

namespace Tests\Feature\Models;

use App\Models\DataRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImportTest extends TestCase
{
    use RefreshDatabase;

    public function test_importer_relation(): void
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

        $this->assertSame($importer->id, $import->importer->id);
    }

    public function test_source_dataset_identifiers_relation(): void
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

        $this->assertCount(1, $import->fresh()->sourceDatasetIdentifiers);
        $this->assertTrue($import->sourceDatasetIdentifiers->contains($identifier));
        $this->assertSame($import->id, $identifier->import->id);
    }

    public function test_source_datasets_relation(): void
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

        $this->assertCount(1, $import->fresh()->sourceDatasets);
        $this->assertTrue($import->sourceDatasets->contains($sourceDataset));
        $this->assertSame($import->id, $sourceDataset->import->id);
        $this->assertSame($identifier->id, $sourceDataset->sourceDatasetIdentifier->id);
    }

    public function test_dataset_creates_relation(): void
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
}
