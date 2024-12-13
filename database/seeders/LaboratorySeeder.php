<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Laboratory;
use App\Models\LaboratoryOrganization;
use App\Models\LaboratoryContactPerson;
use App\Models\LaboratoryManager;
use App\Models\LaboratoryEquipment;
use App\Models\LaboratoryKeyword;

class LaboratorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Laboratory::truncate();
        LaboratoryOrganization::truncate();
        LaboratoryContactPerson::truncate();
        LaboratoryManager::truncate();
        LaboratoryEquipment::truncate();
        LaboratoryKeyword::truncate();
        
        //load jsonData from file
        $fileString = file_get_contents(base_path('database/seeders/datafiles/labs/converted.json'));
        $labData = json_decode($fileString);
        
        //load labs from json
        foreach ($labData as $labJson) {         
            if((int)$labJson->{'FAST ID'} > 0) {   
                $lab = Laboratory::create([
                    'fast_id' => $labJson->{'FAST ID'},
                    'msl_identifier' => $labJson->id,
                    'lab_portal_name' => $labJson->lab_portal_name,
                    'lab_editor_name' => $labJson->lab_editor_name,
                    'msl_identifier_inputstring' => $labJson->id_inputstring,
                    'original_domain' => $labJson->Discipline, 
                    'name' => '',
                    'description' => '',
                    'description_html' => '',
                    'website' => '',
                    'address_street_1' => '',
                    'address_street_2' => '',
                    'address_postalcode' => '',
                    'address_city' => '',
                    'address_country_code' => '',
                    'latitude' => '',
                    'longitude' => '',
                    'altitude' => '',
                    'external_identifier' => '',
                    'fast_domain_name' => ''
                ]);
            }
        }
        
        
    }
}
