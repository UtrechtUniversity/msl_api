<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $filePath = base_path('admin-passwd.csv');
        $this->command->line('reading' . $filePath);

        if(file_exists($filePath)) {
            $csvFile = fopen($filePath, "r");
            $firstline = true;

            while (($data = fgetcsv($csvFile, 2000, ";")) !== FALSE) {
                if (!$firstline) {    
                    User::updateOrCreate(
                        [
                            'email' => $data[1]
                        ],
                        [
                            'name' => $data[0],
                            'email' => $data[1],
                            'password' => $data[2]
                        ]
                    );
                }
                $firstline = false;
            }

            fclose($csvFile);
        } else {
            $this->command->error('file not found');
        }

    }
}
