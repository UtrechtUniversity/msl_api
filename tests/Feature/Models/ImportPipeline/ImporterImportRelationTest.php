<?php

namespace Tests\Feature\Models\ImportPipeline;

use App\Models\DataRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImporterImportRelationTest extends TestCase
{
    use RefreshDatabase;

    public function test_importer_imports_relation(): void
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

        $this->assertCount(1, $importer->fresh()->imports);
        $this->assertTrue($importer->imports->contains($import));
        $this->assertSame($importer->id, $import->importer->id);
    }
}
