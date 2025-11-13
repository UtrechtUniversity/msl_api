<?php

namespace Database\Seeders;

use App\Mappers\Additional\GfzFileMapper;
use App\Models\DataRepository;
use App\Models\Importer;
use Illuminate\Database\Seeder;

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
                'name' => 'GFZ Potsdam',
            ],
            [
                'name' => 'GFZ Potsdam',
                'ckan_name' => 'gfz-potsdam',
            ]
        );

        Importer::updateOrCreate(
            [
                'name' => 'GFZ importer',
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
                            'metadataPrefix' => 'datacite',
                            'setDefinition' => '~P3E9c3ViamVjdCUzQSUyMm11bHRpLXNjYWxlK2xhYm9yYXRvcmllcyUyMg',
                        ],
                    ],
                    'identifierProcessor' => [
                        'type' => 'oaiToDataciteRetrieval',
                        'options' => [
                            'oaiEndpoint' => 'https://doidb.wdc-terra.org/oaip/oai',
                            'metadataPrefix' => 'datacite',
                        ],
                    ],
                    'sourceDatasetProcessor' => [
                        'type' => 'datacite',
                        'options' => [
                            'additionalMappers' => [
                                GfzFileMapper::class,
                            ],
                        ],
                    ],
                ],
                'data_repository_id' => $gfz->id,
            ]
        );
    }
}
