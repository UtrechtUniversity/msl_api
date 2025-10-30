<?php

namespace Database\Seeders;

use App\Mappers\Additional\YodaFileMapper;
use App\Models\DataRepository;
use App\Models\Importer;
use Illuminate\Database\Seeder;

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
                'name' => 'YoDa',
            ],
            [
                'name' => 'YoDa',
                'ckan_name' => 'yoda-repository',
            ]
        );

        Importer::updateOrCreate(
            [
                'name' => 'YoDa importer',
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
                            'identifierKey' => 'DOI',
                        ],
                    ],
                    'identifierProcessor' => [
                        'type' => 'dataciteJsonRetrieval',
                        'options' => [],
                    ],
                    'sourceDatasetProcessor' => [
                        'type' => 'datacite',
                        'options' => [
                            'additionalMappers' => [
                                YodaFileMapper::class,
                            ],
                        ],
                    ],
                ],
                'data_repository_id' => $yoda->id,
            ]
        );
    }
}
