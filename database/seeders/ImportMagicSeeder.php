<?php

namespace Database\Seeders;

use App\Mappers\Additional\MagicFileMapper;
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
                'name' => 'MagIC',
            ],
            [
                'name' => 'MagIC',
                'ckan_name' => 'magic',
            ]
        );

        Importer::updateOrCreate(
            [
                'name' => 'MagIC',
            ],
            [
                'name' => 'MagIC',
                'description' => 'imports MagIC data using fixed JSON list and datacite',
                'type' => 'datacite',
                'options' => [
                    'importProcessor' => [
                        'type' => 'dataciteQuery',
                        'options' => [
                            'query' => 'NOT (relatedIdentifiers.relationType:IsPreviousVersionOf) AND types.resourceTypeGeneral:"Dataset"',
                            'prefix' => '10.7288',
                            'pageSize' => 1000,
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
                                MagicFileMapper::class,
                            ],
                        ],
                    ],
                ],
                'data_repository_id' => $repo->id,
            ]
        );
    }
}
