<?php

namespace Database\Seeders;

use App\Models\SubDomain;
use Illuminate\Database\Seeder;

class SubDomainSeeder extends Seeder
{
    public function run(): void
    {
        SubDomain::updateOrCreate([
            'full_name' => 'rock and melt physics',
            'short_name' => 'rockphysics',
        ]);

        SubDomain::updateOrCreate([
            'full_name' => 'analogue modelling of geologic processes',
            'short_name' => 'analogue',
        ]);

        SubDomain::updateOrCreate([
            'full_name' => 'microscopy and tomography',
            'short_name' => 'microscopy',
        ]);

        SubDomain::updateOrCreate([
            'full_name' => 'paleomagnetism',
            'short_name' => 'paleomagnetism',
        ]);

        SubDomain::updateOrCreate([
            'full_name' => 'geochemistry',
            'short_name' => 'geochemistry',
        ]);

        SubDomain::updateOrCreate([
            'full_name' => 'field-scale laboratories',
            'short_name' => 'fieldscale',
        ]);
    }
}
