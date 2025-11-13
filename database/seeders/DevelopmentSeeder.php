<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder as dbSeeder;

class DevelopmentSeeder extends dbSeeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $this->call([
            DevelopmentOrganizationSeeder::class,
            ImportDevelopmentSeeder::class,
            AdminUserSeeder::class,
        ]);
    }
}
