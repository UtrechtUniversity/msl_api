<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DataRepository;
use App\Models\Importer;

class ImportingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            ImportGFZSeeder::class,
            ImportYodaSeeder::class,
            ImportCsicSeeder::class,
            Import4TUSeeder::class,
            ImportMagicSeeder::class
        ]);
    }
}
