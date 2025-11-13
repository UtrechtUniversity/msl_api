<?php

namespace Database\Seeders;

use App\Models\DataRepository;
use App\Models\Importer;
use Illuminate\Database\Seeder;

class ImportCsicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csic = DataRepository::updateOrCreate(
            [
                'name' => 'CSIC',
            ],
            [
                'name' => 'CSIC',
                'ckan_name' => 'csic',
            ]
        );

        Importer::updateOrCreate(
            [
                'name' => 'CSIC importer',
            ],
            [
                'name' => 'CSIC importer',
                'description' => 'imports CSIC data using fixed JSON list and datacite',
                'type' => 'datacite',
                'options' => [
                    'importProcessor' => [
                        'type' => 'jsonListing',
                        'options' => [
                            'filePath' => '/import-data/csic/converted.json',
                            'identifierKey' => 'doi',
                        ],
                    ],
                    'identifierProcessor' => [
                        'type' => 'dataciteJsonRetrieval',
                        'options' => [],
                    ],
                    'sourceDatasetProcessor' => [
                        'type' => 'datacite',
                        'options' => [],
                    ],
                ],
                'data_repository_id' => $csic->id,
            ]
        );
    }
}
