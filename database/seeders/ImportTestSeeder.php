<?php

namespace Database\Seeders;

use App\Models\DataRepository;
use App\Models\Importer;
use Illuminate\Database\Seeder;

class ImportTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $eposMsl = DataRepository::updateOrCreate(
            [
                'name' => 'epos-msl',
            ],
            [
                'name' => 'epos-msl',
                'ckan_name' => 'epos-multi-scale-laboratories-thematic-core-service',
            ]
        );

        Importer::updateOrCreate(
            [
                'name' => 'test importer',
            ],
            [
                'name' => 'test importer',
                'description' => 'Imports datacite records using fixed JSON list and datacite. Just used for easy testing purposes during development.',
                'type' => 'datacite',
                'options' => [
                    'importProcessor' => [
                        'type' => 'jsonListing',
                        'options' => [
                            'filePath' => '/import-data/test/converted.json',
                            'identifierKey' => 'doi',
                        ],
                    ],
                    'identifierProcessor' => [
                        'type' => 'dataciteXmlRetrieval',
                        'options' => [],
                    ],
                    'sourceDatasetProcessor' => [
                        'type' => 'TestMapper',
                        'options' => [],
                    ],
                ],
                'data_repository_id' => $eposMsl->id,
            ]
        );
    }
}
