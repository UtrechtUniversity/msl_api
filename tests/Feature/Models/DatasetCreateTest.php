<?php

namespace Tests\Feature\Models;

use App\Models\DataRepository;
use App\Models\Import;
use App\Models\SourceDataset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatasetCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_source_dataset_relation(): void
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

        $this->assertInstanceOf(SourceDataset::class, $datasetCreate->sourceDataset);
        $this->assertSame($sourceDataset->id, $datasetCreate->sourceDataset->id);
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

        $datasetCreate = $import->datasetCreates()->create([
            'source_dataset_id' => $sourceDataset->id,
            'dataset_type' => 'dataset',
            'dataset' => ['title' => 'Test'],
            'response_body' => '',
        ]);

        $this->assertInstanceOf(Import::class, $datasetCreate->import);
        $this->assertSame($import->id, $datasetCreate->import->id);
    }
}
