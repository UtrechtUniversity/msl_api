<?php

namespace Database\Seeders;

use App\Models\DataRepository;
use App\Models\Importer;
use Illuminate\Database\Seeder;

class Import4TUSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $repo = DataRepository::updateOrCreate(
            [
                'name' => '4TU'
            ],
            [
                'name' => '4TU',
                'ckan_name' => '4tu'
            ]
        );
        
        Importer::updateOrCreate(
            [
                'name' => '4TU'
            ],
            [
                'name' => '4TU',
                'description' => 'imports 4TU data using fixed JSON list and datacite',
                'type' => 'datacite',
                'options' => [
                    'importProcessor' => [
                        'type' => 'jsonListing',
                        'options' => [
                            'filePath' => '/import-data/4tu/converted.json',
                            'identifierKey' => 'doi'
                        ]                        
                    ],
                    'identifierProcessor' => [
                        'type' => 'dataciteXmlRetrieval',
                        'options' => []
                    ],
                    'sourceDatasetProcessor' => [
                        'type' => 'FourTUMapper',
                        'options' => []
                    ]
                ],
                'data_repository_id' => $repo->id
            ]
            );
    }
}
