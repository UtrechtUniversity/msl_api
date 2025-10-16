<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DataRepository;
use App\Models\Importer;

class ImportDevelopmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dev = DataRepository::updateOrCreate(
            [
                'name' => 'development',
            ],
            [
                'name' => 'development',
                'ckan_name' => 'development',
            ]
        );
        Importer::updateOrCreate(
            [
                'name' => 'Development importer',
            ],
            [
                'name' => 'Development importer',
                'description' => 'imports test data using fixed directory list and DataCite files',
                'type' => 'Development',
                'options' => [
                    'importProcessor' => [
                        'type' => 'directoryListing',
                        'options' => [
                            'directoryPath' => '/import-data/development/',
                            'recursive' => 'true',
                        ],
                    ],
                    'identifierProcessor' => [
                        'type' => 'fileRetrieval',
                        'options' => [],
                    ],
                    'sourceDatasetProcessor' => [
                        'type' => 'datacite',
                        'options' => [],
                    ],
                ],
                'data_repository_id' => $dev->id,
            ]
        );

    }
}
