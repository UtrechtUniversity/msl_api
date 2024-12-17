<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\fast\Fast;
use App\Models\Keyword;
use App\Models\LaboratoryUpdateFast;
use App\Models\LaboratoryOrganization;
use App\Models\LaboratoryContactPerson;
use App\Models\LaboratoryManager;
use App\Models\LaboratoryEquipment;
use App\Models\LaboratoryEquipmentAddon;
use App\Models\Vocabulary;

class ProcessLaboratoryUpdateFast implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    protected $laboratoryUpdateFast;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(LaboratoryUpdateFast $laboratoryUpdateFast)
    {
        $this->laboratoryUpdateFast = $laboratoryUpdateFast;
    }
    
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {        
        $lab = $this->laboratoryUpdateFast->laboratory;
        
        $fast = new Fast();        
        $result = $fast->facilityRequest($lab->fast_id);
        
        if($result->response_code == 200) {
            $data = $result->response_body['data'];
                        
            $lab->name = $data['name'];
            $lab->description = $data['description'];
            if(isset($data['description_html'])) {
                $lab->description_html = $data['description_html'];
            }
            
            $lab->website = $data['website'];
            $lab->address_street_1 = $data['address_street_1'];
            $lab->address_street_2 = $data['address_street_2'];
            $lab->address_postalcode = $data['address_postcode'];
            $lab->address_city = $data['address_city'];
            $lab->address_country_code = $data['address_country_code'];
            $lab->address_country_name = $data['address_country']['name'];
            $lab->latitude = $data['gps_latitude'];
            $lab->longitude = $data['gps_longitude'];
            $lab->altitude = $data['gps_altitude'];
            $lab->external_identifier = $data['external_identifier'];
            $lab->fast_domain_id = $data['domain']['id'];
            $lab->fast_domain_name = $data['domain']['name'];
            
            // include affiliation
            if(isset($data['affiliation'])) {
                $fastAffiliationId = $data['affiliation']['id'];
                $organization = LaboratoryOrganization::where('fast_id', $fastAffiliationId)->first();
                
                if(!$organization) {
                    $organization = new LaboratoryOrganization();
                    $organization->fast_id = $data['affiliation']['id'];
                }
                                                    
                $organization->name = $data['affiliation']['name'];
                
                $organization->external_identifier = '';
                if(isset($data['affiliation']['external_identifier'])) {
                    $organization->external_identifier = $data['affiliation']['external_identifier'];
                }
                
                $organization->save();
                
                $lab->laboratory_organization_id = $organization->id;
            }
            
            // include contact person
            if(isset($data['contact_person'])) {
                $fastContactPersonId = $data['contact_person']['id'];
                $contactPerson = LaboratoryContactPerson::where('fast_id', $fastContactPersonId)->first();
                
                if(!$contactPerson) {
                    $contactPerson = new LaboratoryContactPerson();
                    $contactPerson->fast_id = $data['contact_person']['id'];
                }
                
                $contactPerson->email = $data['contact_person']['email'];
                $contactPerson->first_name = $data['contact_person']['first_name'];
                $contactPerson->last_name = $data['contact_person']['last_name'];
                $contactPerson->orcid = $data['contact_person']['orcid'];
                $contactPerson->address_street_1 = $data['contact_person']['address_street_1'];
                $contactPerson->address_street_2 = $data['contact_person']['address_street_2'];
                $contactPerson->address_postalcode = $data['contact_person']['address_postcode'];
                $contactPerson->address_city = $data['contact_person']['address_city'];
                $contactPerson->address_country_code = $data['contact_person']['address_country']['code'];
                $contactPerson->address_country_name = $data['contact_person']['address_country']['name'];
                $contactPerson->affiliation_fast_id = $data['contact_person']['affiliation_id'];
                $contactPerson->nationality_code = $data['contact_person']['nationality']['code'];
                $contactPerson->nationality_name = $data['contact_person']['nationality']['name'];
                
                $contactPerson->save();
                $lab->laboratory_contact_person_id = $contactPerson->id;                
            }
            
            // include manager
            if(isset($data['manager'])) {
                $fastManagerId = $data['manager']['id'];
                $manager = LaboratoryManager::where('fast_id', $fastManagerId)->first();
                
                if(!$manager) {
                    $manager = new LaboratoryManager();
                    $manager->fast_id = $data['manager']['id'];
                }
                
                $manager->email = $data['manager']['email'];
                $manager->first_name = $data['manager']['first_name'];
                $manager->last_name = $data['manager']['last_name'];
                $manager->orcid = $data['manager']['orcid'];
                $manager->address_street_1 = $data['manager']['address_street_1'];
                $manager->address_street_2 = $data['manager']['address_street_2'];
                $manager->address_postalcode = $data['manager']['address_postcode'];
                $manager->address_city = $data['manager']['address_city'];
                $manager->address_country_code = $data['manager']['address_country']['code'];
                $manager->address_country_name = $data['manager']['address_country']['name'];
                $manager->affiliation_fast_id = $data['manager']['affiliation_id'];
                $manager->nationality_code = $data['manager']['nationality']['code'];
                $manager->nationality_name = $data['manager']['nationality']['name'];
                
                $manager->save();
                $lab->laboratory_manager_id = $manager->id;
            }
            
            // include equipment
            if(isset($data['equipment'])) {
                foreach ($data['equipment'] as $fastEquipment) {
                    $equipment = new LaboratoryEquipment();
                    
                    $equipment->fast_id = $fastEquipment['id'];
                    $equipment->laboratory_id = $lab->id;
                    $equipment->description = $fastEquipment['description'];
                    
                    $equipment->description_html = '';
                    if(isset($fastEquipment['description_html'])) {
                        $equipment->description_html = $fastEquipment['description_html'];
                    }
                    
                    $equipment->category_name = $fastEquipment['category']['name'];
                    $equipment->type_name = $fastEquipment['type']['name'];
                    $equipment->domain_name = $fastEquipment['type']['domain']['name'];
                    $equipment->group_name = $fastEquipment['group']['name'];
                    $equipment->brand = $fastEquipment['brand'];
                    $equipment->website = $fastEquipment['website'];
                    $equipment->latitude = $fastEquipment['gps_latitude'];
                    $equipment->longitude = $fastEquipment['gps_longitude'];
                    $equipment->altitude = $fastEquipment['gps_altitude'];
                    
                    $equipment->external_identifier = '';
                    if(isset($fastEquipment['external_identifier'])) {
                        $equipment->external_identifier = $fastEquipment['external_identifier'];
                    }

                    $equipment->name = $fastEquipment['name']['name'];

                    // create reference to keyword
                    $equipment->keyword_id = $this->getEquipmentKeyword($equipment);                
                    
                    $equipment->save();

                    // add addons
                    foreach($fastEquipment['addons'] as $addon) {
                        $laboratoryEquipmentAddon = new LaboratoryEquipmentAddon();
                        if(isset($addon['description'])) {
                            $laboratoryEquipmentAddon->description = $addon['description'];
                            $laboratoryEquipmentAddon->laboratory_equipment_id = $equipment->id;
                            $laboratoryEquipmentAddon->type = $addon['type']['name'];
                            $laboratoryEquipmentAddon->group = $addon['group']['name'];
                            $laboratoryEquipmentAddon->keyword_id = $this->getAddonKeyword($laboratoryEquipmentAddon, $equipment);

                            $laboratoryEquipmentAddon->save();
                        }
                    }
                }
            }

            $lab->save();
            
            $this->laboratoryUpdateFast->response_code = $result->response_code;
            $this->laboratoryUpdateFast->source_laboratory_data = $data;
            $this->laboratoryUpdateFast->save();
        } else {
            $this->laboratoryUpdateFast->response_code = $result->response_code;
            $this->laboratoryUpdateFast->save();
        }
        

    }

    /**
     * Attempt to locate keyword based upon equipment group, type and name
     * 
     * @return int|null
     */
    private function getEquipmentKeyword($equipment)    
    {
        $vocabulary = Vocabulary::where('name', 'fast')->where('version', '1.0')->first();

        // Get keywords that match based on the name value of the equipment
        $nameKeywords = Keyword::where('vocabulary_id', $vocabulary->id)->where('value', $equipment->name)->get();

        /**
         * If we find 1 match we can assume the correct keyword is found. Otherwise traverse the vocabulary up 
         * and check parent keywords to see if we found the correct one.
        */
        if($nameKeywords->count() == 0) {
            return null;
        } elseif($nameKeywords->count() == 1) {
            return $nameKeywords->first()->id;
        } else {
            foreach($nameKeywords as $nameKeyword) {
                $GroupKeyword = $nameKeyword->parent;
                if($GroupKeyword->value == $equipment->group_name) {
                    $typeKeyword = $GroupKeyword->parent;
                    if($typeKeyword->value == $equipment->type_name) {
                        $nodeKeyword = $typeKeyword->parent;
                        if($nodeKeyword->value == 'Equipment') {
                            $domainKeyword = $nodeKeyword->parent;
                            if($domainKeyword->value == $equipment->domain_name) {
                                return $nameKeyword->id;
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Attempt to locate keyword based on received add-on information
     * 
     * @return int|null
     */
    private function getAddonKeyword($addon, $equipment) 
    {
        $vocabulary = Vocabulary::where('name', 'fast')->where('version', '1.0')->first();

        // Get keywords that match based on the group value of the equipment
        $groupKeywords = Keyword::where('vocabulary_id', $vocabulary->id)->where('value', $addon->group)->get();

        /**
         * If we find 1 match we can assume the correct keyword is found. Otherwise traverse the vocabulary up 
         * and check parent keywords to see if we found the correct one.
        */
        if($groupKeywords->count() == 0) {
            return null;
        } elseif($groupKeywords->count() == 1) {
            return $groupKeywords->first()->id;
        } else {
            foreach($groupKeywords as $groupKeyword) {                
                $typeKeyword = $groupKeyword->parent;
                if($typeKeyword->value == $addon->type) {
                    $nodeKeyword = $typeKeyword->parent;
                    if($nodeKeyword->value == 'Add-ons') {
                        $domainKeyword = $nodeKeyword->parent;
                        if($domainKeyword->value == $equipment->domain_name) {
                            return $groupKeyword->id;
                        }
                    }
                }
                
            }
        }
    }
}
