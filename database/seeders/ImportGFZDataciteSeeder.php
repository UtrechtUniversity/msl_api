<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DataRepository;
use App\Models\Importer;

class ImportGFZDataciteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $gfz = DataRepository::updateOrCreate(
            [
                'name' => 'GFZ Potsdam'
            ],
            [
                'name' => 'GFZ Potsdam',
                'ckan_name' => 'gfz-potsdam'
            ]
        );
        
        Importer::updateOrCreate(
            [
                'name' => 'GFZ Datacite importer'
            ],
            [
                'name' => 'GFZ Datacite importer',
                'description' => 'import extra GFZ Potsdam data using Datacite',
                'type' => 'datacite',
                'options' => [
                    'importProcessor' => [
                        'type' => 'jsonListing',
                        'options' => [
                            'filePath' => '/import-data/gfz/converted.json',
                            'identifierKey' => 'doi'
                        ]
                    ],
                    'identifierProcessor' => [
                        'type' => 'dataciteXmlRetrieval',
                        'options' => []
                    ],
                    'sourceDatasetProcessor' => [
                        'type' => 'GFZDataciteMapper',
                        'options' => []
                    ]
                ],
                'data_repository_id' => $gfz->id
            ]
        );
    }
}
