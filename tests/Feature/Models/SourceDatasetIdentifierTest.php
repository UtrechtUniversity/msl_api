<?php

namespace Tests\Feature\Models;

use App\Models\DataRepository;
use App\Models\Import;
use App\Models\SourceDataset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SourceDatasetIdentifierTest extends TestCase
{
    use RefreshDatabase;

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

        $this->assertInstanceOf(Import::class, $identifier->import);
        $this->assertSame($import->id, $identifier->import->id);
    }

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

        $sourceDataset = $identifier->sourceDataset()->create([
            'import_id' => $import->id,
            'status' => 'ok',
            'source_dataset' => '{}',
        ]);

        $this->assertNotNull($identifier->fresh()->sourceDataset);
        $this->assertInstanceOf(SourceDataset::class, $identifier->sourceDataset);
        $this->assertSame($sourceDataset->id, $identifier->sourceDataset->id);
    }
}
