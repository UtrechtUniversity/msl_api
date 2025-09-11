<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaboratoryEquipment extends Model
{
    protected $table = 'laboratory_equipment';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fast_id',
        'laboratory_id',
        'description',
        'description_html',
        'category_name',
        'type_name',
        'domain_name',
        'group_name',
        'brand',
        'website',
        'latitude',
        'longitude',
        'altitude',
        'external_identifier',
        'name',
        'keyword_id'                
    ];
    
    public function laboratory()
    {
        return $this->belongsTo(Laboratory::class);
    }

    public function keyword()
    {
        return $this->belongsTo(Keyword::class);
    }

    public function laboratory_equipment_addons()
    {
        return $this->hasMany(LaboratoryEquipmentAddon::class, 'laboratory_equipment_id', 'id');
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
            'type' => 'equipment',
            'name' => md5($this->fast_id . '-' . $this->laboratory_id),
            'owner_org' => 'epos-multi-scale-laboratories-thematic-core-service',
            'msl_description' => $this->description,
            'msl_description_html' => $this->description_html,
            'msl_category_name' => $this->category_name,
            'msl_type_name' => $this->type_name,
            'msl_domain_name' => $this->domain_name,
            'msl_group_name' => $this->group_name,
            'msl_brand' => $this->brand,
            'msl_organization_name' => $this->laboratory->laboratoryOrganization->name,
            'msl_lab_id' => $this->laboratory_id,
            'msl_lab_ckan_name' => $this->laboratory->msl_identifier,
            'msl_website' => $this->website,
            'msl_original_keywords' => $this->getCkanKeywords(),
            'msl_equipment_keyword_uri' => $this->keyword->uri,
            'msl_equipment_keyword_label' => $this->keyword->label,
            'msl_equipment_addons' => $this->getCkanAddons(),
            'msl_location' => $this->getGeoJsonFeature(),
            'msl_has_spatial_data' => $this->hasSpatialData(),
            'extras' => [
                ["key" => "spatial", "value" => $this->getPointGeoJson()]
            ]
        ];
    }

    /**
     * Convert addons to ckan representation
     * 
     * @return array
     */
    private function getCkanAddons() {
        $addons = [];
        foreach($this->laboratory_equipment_addons as $addon) {          
            $addons[] = [
                'msl_equipment_addon_description' => $addon->description,
                'msl_equipment_addon_type' => $addon->type,
                'msl_equipment_addon_group' => $addon->group,
                'msl_equipment_addon_keyword_uri' => $addon->keyword->uri,
                'msl_equipment_addon_keyword_label' => $addon->keyword->label
            ];
        }

        return $addons;
    }

    /**
     * Convert keywords to ckan representation. Includes equipment and addon keywords
     * including keyword ancestors/parents.
     * 
     * @return array
     */
    private function getCkanKeywords() {
        $originalKeywords = [];
        $keyword = $this->keyword;

        if($keyword) {
            $originalKeywords[] = [
                'msl_original_keyword_label' => $keyword->label,
                'msl_original_keyword_uri' => $keyword->uri,
                'msl_original_keyword_vocab_uri' => $keyword->vocabulary->uri
            ];

            $parents = $keyword->getAncestors();
            foreach($parents as $parent) {
                $originalKeywords[] = [
                    'msl_original_keyword_label' => $parent->label,
                    'msl_original_keyword_uri' => $parent->uri,
                    'msl_original_keyword_vocab_uri' => $parent->vocabulary->uri
                ];
            }            
        }

        $addons = $this->laboratory_equipment_addons;
        
        foreach($addons as $addon) {
            $keyword = $addon->keyword;

            if($keyword) {
                $originalKeywords[] = [
                    'msl_original_keyword_label' => $keyword->label,
                    'msl_original_keyword_uri' => $keyword->uri,
                    'msl_original_keyword_vocab_uri' => $keyword->vocabulary->uri
                ];

                $parents = $keyword->getAncestors();
                foreach($parents as $parent) {
                    $originalKeywords[] = [
                        'msl_original_keyword_label' => $parent->label,
                        'msl_original_keyword_uri' => $parent->uri,
                        'msl_original_keyword_vocab_uri' => $parent->vocabulary->uri
                    ];
                }
            }
        }

        // remove duplicates
        $keywords = [];
        $uris = [];
        foreach($originalKeywords as $originalKeyword) {
            if(!in_array($originalKeyword['msl_original_keyword_uri'], $uris)) {
                $uris[] = $originalKeyword['msl_original_keyword_uri'];
                $keywords[] = $originalKeyword;
            }
        }

        return $keywords;
    }

    /**
     * Create point geojson string using latitude and longitude. Uses laboratory 
     * location if none is set for equipment.
     * 
     * @return string
     */
    private function getPointGeoJson()
    {
        if($this->hasSpatialData()) {
            if((strlen($this->latitude) > 0) && (strlen($this->longitude) > 0)) {
                return json_encode([
                    'type' => 'Point',
                    'coordinates' => [(float)$this->longitude, (float)$this->latitude]
                ]);
            } else {
                return $this->laboratory->getPointGeoJson();
            }            
        }

        return '';
    }

    /**
     * Get geojson feature object string. Uses laboratory location if no equipment 
     * location is set. 
     * 
     * @return string
     */
    public function getGeoJsonFeature()
    {
        if($this->hasSpatialData()) {
            if((strlen($this->latitude) > 0) && (strlen($this->longitude) > 0)) {
                return json_encode([
                    'type' => 'Feature',
                    'geometry' => [
                        'type' => 'Point',
                        'coordinates' => [(float)$this->longitude, (float)$this->latitude]
                    ],
                    'properties' => [
                        'title' => $this->name,
                        'name' => md5($this->fast_id . '-' . $this->laboratory_id),
                        'msl_id' => $this->id,
                        'msl_lab_ckan_name' => $this->laboratory->msl_identifier,
                        'msl_lab_name' => $this->laboratory->name,
                        'msl_domain_name' => $this->domain_name,
                        'msl_group_name' => $this->group_name,
                        'msl_type_name' => $this->type_name
                    ]
                ]);
            } else {
                return json_encode([
                    'type' => 'Feature',
                    'geometry' => [
                        'type' => 'Point',
                        'coordinates' => [(float)$this->laboratory->longitude, (float)$this->laboratory->latitude]
                    ],
                    'properties' => [
                        'title' => $this->name,
                        'name' => md5($this->fast_id . '-' . $this->laboratory_id),
                        'msl_id' => $this->id,
                        'msl_lab_ckan_name' => $this->laboratory->msl_identifier,
                        'msl_lab_name' => $this->laboratory->name,
                        'msl_domain_name' => $this->domain_name,
                        'msl_group_name' => $this->group_name,
                        'msl_type_name' => $this->type_name
                    ]
                ]);
            }
        }

        return '';
    }

    /**
     * check if equipment or laboratory has spatial data
     * 
     * @return bool
     */
    public function hasSpatialData()
    {
        if((strlen($this->latitude) > 0) && (strlen($this->longitude) > 0)) {
            return true;
        } else if($this->laboratory->hasSpatialData()) {
            return true;
        }

        return false;
    }
   
}
