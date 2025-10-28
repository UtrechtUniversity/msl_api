<?php

namespace Database\Seeders;

use App\Models\Seeder;
use Illuminate\Database\Seeder as dbSeeder;

class DevelopmentOrganizationSeeder extends dbSeeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Development organization seeder
        Seeder::updateOrCreate(
            [
                'name' => 'Development seeder',
            ],
            [
                'name' => 'Development seeder',
                'description' => 'create/update development data in ckan',
                'type' => 'organization',
                'options' => [
                    'type' => 'fileSeeder',
                    'filePath' => '/seed-data/development.json',
                ],
            ]
        );
    }
}
