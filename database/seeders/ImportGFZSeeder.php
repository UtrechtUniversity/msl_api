<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DataRepository;
use App\Models\Importer;

class ImportGFZSeeder extends Seeder
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
                'name' => 'GFZ importer'
            ],
            [
                'name' => 'GFZ importer',
                'description' => 'import GFZ Potsdam data using OAI',
                'type' => 'OAI',
                'options' => [
                    'importProcessor' => [
                        'type' => 'oaiListing',
                        'options' => [
                            'oaiEndpoint' => 'https://doidb.wdc-terra.org/oaip/oai',
                            'metadataPrefix' => 'iso19139',
                            'setDefinition' => '~P3E9c3ViamVjdCUzQSUyMm11bHRpLXNjYWxlK2xhYm9yYXRvcmllcyUyMg'
                        ]
                    ],
                    'identifierProcessor' => [
                        'type' => 'oaiRetrieval',
                        'options' => [
                            'oaiEndpoint' => 'https://doidb.wdc-terra.org/oaip/oai',
                            'metadataPrefix' => 'iso19139',
                        ]
                    ],
                    'sourceDatasetProcessor' => [
                        'type' => 'gfzMapper',
                        'options' => []
                    ]      
                ],
                'data_repository_id' => $gfz->id
            ]
        );
    }
}
