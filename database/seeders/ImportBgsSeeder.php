<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DataRepository;
use App\Models\Importer;

class ImportBgsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bgs = DataRepository::updateOrCreate(
            [
                'name' => 'bgs'
            ],
            [
                'name' => 'bgs',
                'ckan_name' => 'bgs'
            ]
        );
        
        Importer::updateOrCreate(
            [
                'name' => 'bgs importer'
            ],
            [
                'name' => 'bgs importer',
                'description' => 'imports bgs data using fixed JSON list and DataCite',
                'type' => 'bgs',
                'options' => [
                    'importProcessor' => [
                        'type' => 'jsonListing',
                        'options' => [
                            'filePath' => '/import-data/bgs/converted.json',
                            'identifierKey' => 'doi'
                        ]
                    ],
                    'identifierProcessor' => [
                        'type' => 'dataciteXmlRetrieval',
                        'options' => []
                    ],
                    'sourceDatasetProcessor' => [
                        'type' => 'BgsMapper',
                        'options' => []
                    ]
                ],
                'data_repository_id' => $bgs->id
            ]
        );
    }
}
