<?php

namespace Tests\Feature;

use App\Mappers\MappingService;
use App\Models\Importer;
use App\Models\SourceDataset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MappingServiceTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        // source dataset
        $sourceDataset = new SourceDataset([
            'source_dataset_identifier_id'=> 1,
            'import_id' => 1,
            'source_dataset' => file_get_contents(base_path('/tests/MockData/DataCiteResponses/repositories/yoda/UU01-LN58S4.txt'))
        ]);

        // importer
        $importer = new Importer([
            'name' => 'YoDa importer',
                'description' => 'imports yoda data using fixed JSON list and datacite',
                'type' => 'datacite',
                'options' => [
                    'importProcessor' => [
                        'type' => 'jsonListing',
                        'options' => [
                            'filePath' => '/import-data/yoda/converted.json',
                            'identifierKey' => 'DOI'
                        ]
                    ],
                    'identifierProcessor' => [
                        'type' => 'dataciteXmlRetrieval',
                        'options' => []
                    ],
                    'sourceDatasetProcessor' => [
                        'type' => 'datacite',
                        'options' => []
                    ]
                ],
                'data_repository_id' => 1
        ]);

        $mappingService = new MappingService();
        $mappedData = $mappingService->map($sourceDataset, $importer);

        dd($mappedData);
    }
}
