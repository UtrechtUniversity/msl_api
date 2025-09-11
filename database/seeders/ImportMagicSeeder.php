<?php

namespace Database\Seeders;

use App\Models\DataRepository;
use App\Models\Importer;
use Illuminate\Database\Seeder;

class ImportMagicSeeder extends Seeder
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
                'name' => 'MagIC'
            ],
            [
                'name' => 'MagIC',
                'ckan_name' => 'magic'
            ]
        );
        
        Importer::updateOrCreate(
            [
                'name' => 'MagIC'
            ],
            [
                'name' => 'MagIC',
                'description' => 'imports MagIC data using fixed JSON list and datacite',
                'type' => 'datacite',
                'options' => [
                    'importProcessor' => [
                        'type' => 'jsonListing',
                        'options' => [
                            'filePath' => '/import-data/magic/converted.json',
                            'identifierKey' => 'identifier'
                        ],
                        'extra_data_loader' => [
                            'type' => 'jsonLoader',
                            'options' => [
                                'filePath' => '/import-data/magic/converted.json',
                                'dataKeyMapping' => [
                                    'contentUrl' => 'contentUrl',
                                    'description' => 'description'
                                ]
                            ]
                        ]
                    ],
                    'identifierProcessor' => [
                        'type' => 'dataciteXmlRetrieval',
                        'options' => []
                    ],
                    'sourceDatasetProcessor' => [
                        'type' => 'MagicMapper',
                        'options' => []
                    ]
                ],
                'data_repository_id' => $repo->id
            ]
            );
    }
}
