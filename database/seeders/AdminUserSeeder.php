<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::updateOrCreate(
            [
                'name' => 'Laurens'
            ],
            [
                'name' => 'Laurens',
                'email' => 'l.samshuijzen@uu.nl',
                'password' => bcrypt('testtest')
            ]
        );

        User::updateOrCreate(
            [
                'name' => 'Maik'
            ],
            [
                'name' => 'Maik',
                'email' => 'm.nothbaum@uu.nl',
                'password' => bcrypt('testtest')
            ]
        );   
    }
}
