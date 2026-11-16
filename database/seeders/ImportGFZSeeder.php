<?php

namespace Database\Seeders;

use App\Mappers\DataPublicationImport\Additional\GfzFileMapper;
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
                            'oaiEndpoint' => 'https://dataservices.gfz.de/oai-pmh',
                            'metadataPrefix' => 'oai_datacite',
                            'setDefinition' => 'epos-msl',
                        ],
                    ],
                    'identifierProcessor' => [
                        'type' => 'oaiToDataciteRetrieval',
                        'options' => [
                            'oaiEndpoint' => 'https://dataservices.gfz.de/oai-pmh',
                            'metadataPrefix' => 'oai_datacite',
                        ],
                    ],
                    'sourceDatasetProcessor' => [
                        'type' => 'datacite',
                        'options' => [
                            'additionalMappers' => [
                                [
                                    'type' => GfzFileMapper::class,
                                    'options' => [],
                                ],

                            ],
                        ],
                    ],
                ],
                'data_repository_id' => $gfz->id,
            ]
        );
    }
}
