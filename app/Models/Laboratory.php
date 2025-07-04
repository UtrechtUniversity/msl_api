<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laboratory extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fast_id',
        'msl_identifier',
        'lab_portal_name',
        'lab_editor_name',
        'msl_identifier_inputstring',
        'original_domain',
        'name',
        'description',
        'description_html',
        'website',
        'address_street_1',
        'address_street_2',
        'address_postalcode',
        'address_city',
        'address_country_code',
        'address_country_name',
        'latitude',
        'longitude',
        'altitude',
        'external_identifier',
        'fast_domain_id',
        'fast_domain_name',
        'laboratory_organization_id',
        'laboratory_manager_id'
    ];
    
    public function laboratoryOrganization()
    {
        return $this->belongsTo(LaboratoryOrganization::class, 'laboratory_organization_id');
    }
    
    public function laboratoryContactPersons()
    {
        return $this->hasMany(LaboratoryContactPerson::class, 'laboratory_id');
    }
    
    public function laboratoryManager()
    {
        return $this->belongsTo(LaboratoryManager::class, 'laboratory_manager_id');
    }
    
    public function laboratoryEquipment()
    {
        return $this->hasMany(LaboratoryEquipment::class, 'laboratory_id');
    }
    
    public function laboratoryKeywords()
    {
        return $this->hasMany(LaboratoryKeyword::class, 'laboratory_id');
    }

    /**
     * Convert object to CKAN representation
     * 
     * @return array
     */
    public function toCkanArray()
    {
        return [
            'title' => $this->name,
            'type' => 'lab',
            'name' => $this->msl_identifier,
            'owner_org' => 'epos-multi-scale-laboratories-thematic-core-service',
            'msl_id' => $this->id,
            'msl_fast_id' => $this->fast_id,
            'msl_description' => $this->description,
            'msl_description_html' => $this->description_html,
            'msl_website' => $this->website,
            'msl_address_street_1' => $this->address_street_1,
            'msl_address_street_2' => $this->address_street_2,
            'msl_address_postalcode' => $this->address_postalcode,
            'msl_address_city' => $this->address_city,
            'msl_msl_address_country_code' => $this->address_country_code,
            'msl_address_country_name' => $this->address_country_name,
            'msl_domain_name' => $this->fast_domain_name,
            'msl_organization_name' => $this->laboratoryOrganization->name,
            'msl_latitude' => $this->latitude,
            'msl_longitude' => $this->longitude,
            'msl_altitude' => $this->altitude,
            'msl_location' => $this->getGeoJsonFeature(),
            'msl_has_spatial_data' => $this->hasSpatialData(),
            'msl_laboratory_equipment' => $this->getLimitedEquipment(),
            'extras' => [
                ["key" => "spatial", "value" => $this->getPointGeoJson()]
            ]
        ];
    }

    /**
     * Returns a limited set of properties for each equipment belonging to the lab.
     * This data is used in CKAN to populate the facility API endpoint working around the 
     * need to combine equipment and laboratory datatypes seperately. 
     * 
     * @return array
     */
    private function getLimitedEquipment(): array
    {
        $equipment = [];

        $equipmentList = $this->laboratoryEquipment;

        foreach($equipmentList as $equipmentEntry) {
            $equipment[] = [
                'msl_laboratory_equipment_title' => $equipmentEntry->name,
                'msl_laboratory_equipment_description' => $equipmentEntry->description,
                'msl_laboratory_equipment_description_html' => $equipmentEntry->description_html,
                'msl_laboratory_equipment_domain' => $equipmentEntry->domain_name,
                'msl_laboratory_equipment_category' => $equipmentEntry->category_name,
                'msl_laboratory_equipment_type' => $equipmentEntry->type_name,
                'msl_laboratory_equipment_group' => $equipmentEntry->group_name,
                'msl_laboratory_equipment_brand' => $equipmentEntry->brand
            ];
        }

        return $equipment;
    }

    /**
     * Create point geojson string using latitude and longitude.
     * 
     * @return string
     */
    public function getPointGeoJson()
    {
        if($this->hasSpatialData()) {
            return json_encode([
                'type' => 'Point',
                'coordinates' => [(float)$this->longitude, (float)$this->latitude]
            ]);
        }

        return '';
    }

    /**
     * Get geojson feature object string. 
     * 
     * @return string
     */
    private function getGeoJsonFeature()
    {
        if($this->hasSpatialData()) {
            return json_encode([
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [(float)$this->longitude, (float)$this->latitude]
                ],
                'properties' => [
                    'title' => $this->name,
                    'name' => $this->msl_identifier,
                    'msl_id' => $this->id,
                    'msl_organization_name' => $this->laboratoryOrganization->name,
                    'msl_domain_name' => $this->fast_domain_name
                ]
            ]);
        }

        return '';
    }

    /**
     * check if laboratory has spatial data
     * 
     * @return bool
     */
    public function hasSpatialData()
    {
        if((strlen($this->latitude) > 0) && (strlen($this->longitude) > 0)) {
            return true;
        }

        return false;
    }
}
