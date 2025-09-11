<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DataRepository;
use App\Models\Importer;

class ImportYodaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $yoda = DataRepository::updateOrCreate(
            [
                'name' => 'YoDa'
            ],
            [
                'name' => 'YoDa',
                'ckan_name' => 'yoda-repository'
            ]
        );
        
        Importer::updateOrCreate(
            [
                'name' => 'YoDa importer'
            ],
            [
                'name' => 'YoDa importer',
                'description' => 'imports yoda data using fixed JSON list and datacite',
                'type' => 'datacite',
                'options' => [
                    'importProcessor' => [
                        'type' => 'jsonListing',
                        'options' => [
                            'filePath' => '/import-data/yoda/converted.json',
                            'identifierKey' => 'DOI'
                        ],
                        'extra_data_loader' => [
                            'type' => 'jsonLoader',
                            'options' => [
                                'filePath' => '/import-data/yoda/converted.json',
                                'dataKeyMapping' => [
                                    'Subdomain' => 'subDomain',
                                    'Data documentation' => 'dataDocumentation',
                                    'Data' => 'data',
                                    'LabIdentifier' => 'labIdentifier',
                                    'LabName' => 'LabName'
                                ]                            
                            ]
                        ]
                    ],
                    'identifierProcessor' => [
                        'type' => 'dataciteXmlRetrieval',
                        'options' => []
                    ],
                    'sourceDatasetProcessor' => [
                        'type' => 'yodaMapper',
                        'options' => []
                    ]
                ],
                'data_repository_id' => $yoda->id
            ]
        );
    }
}
