<?php

namespace App\Mappers\Ckan;

use App\GeoJson\Feature\Feature;
use App\GeoJson\Geometry\Point;
use App\Models\Laboratory\LaboratoryEquipment;

class EquipmentMapper
{
    public static function fromEquipment(LaboratoryEquipment $equipment): array
    {
        return [
            'title' => $equipment->name,
            'type' => $equipment->getCkanType(),
            'name' => (string)$equipment->getScoutKey(),
            'owner_org' => 'epos-multi-scale-laboratories-thematic-core-service',
            'msl_description' => $equipment->description,
            'msl_description_html' => $equipment->description_html,
            'msl_category_name' => $equipment->category_name,
            'msl_type_name' => $equipment->type_name,
            'msl_domain_name' => $equipment->domain_name,
            'msl_group_name' => $equipment->group_name,
            'msl_brand' => $equipment->brand,
            'msl_organization_name' => $equipment->laboratory ? $equipment->laboratory->laboratoryOrganization->name : '',
            'msl_lab_id' => $equipment->laboratory_id,
            'msl_lab_ckan_name' => $equipment->laboratory ? $equipment->laboratory->msl_identifier : '',
            'msl_website' => $equipment->website,
            'msl_original_keywords' => self::getCkanKeywords($equipment),
            'msl_equipment_keyword_uri' => $equipment->keyword ? $equipment->keyword->uri : '',
            'msl_equipment_keyword_label' => $equipment->keyword ? $equipment->keyword->label : '',
            'msl_equipment_addons' => self::getCkanAddons($equipment),
            'msl_location' => self::getGeoJsonFeature($equipment),
            'msl_has_spatial_data' => $equipment->hasSpatialData(),
            'extras' => [
                ['key' => 'spatial', 'value' => self::getPointGeoJson($equipment)],
            ],
        ];
    }

    /**
     * Convert addons to ckan representation
     */
    private static function getCkanAddons(LaboratoryEquipment $equipment): array
    {
        $addons = [];
        foreach ($equipment->laboratoryEquipmentAddons as $addon) {
            $addons[] = [
                'msl_equipment_addon_description' => $addon->description,
                'msl_equipment_addon_type' => $addon->type,
                'msl_equipment_addon_group' => $addon->group,
                'msl_equipment_addon_keyword_uri' => $addon->keyword->uri,
                'msl_equipment_addon_keyword_label' => $addon->keyword->label,
            ];
        }

        return $addons;
    }

    /**
     * Convert keywords to ckan representation. Includes equipment and addon keywords
     * including keyword ancestors/parents.
     */
    private static function getCkanKeywords(LaboratoryEquipment $equipment): array
    {
        $originalKeywords = [];
        $keyword = $equipment->keyword;

        if ($keyword) {
            $originalKeywords[] = [
                'msl_original_keyword_label' => $keyword->label,
                'msl_original_keyword_uri' => $keyword->uri,
                'msl_original_keyword_vocab_uri' => $keyword->vocabulary->uri,
            ];

            $parents = $keyword->getAncestors();
            foreach ($parents as $parent) {
                $originalKeywords[] = [
                    'msl_original_keyword_label' => $parent->label,
                    'msl_original_keyword_uri' => $parent->uri,
                    'msl_original_keyword_vocab_uri' => $parent->vocabulary->uri,
                ];
            }
        }

        $addons = $equipment->laboratoryEquipmentAddons;

        foreach ($addons as $addon) {
            $keyword = $addon->keyword;

            if ($keyword) {
                $originalKeywords[] = [
                    'msl_original_keyword_label' => $keyword->label,
                    'msl_original_keyword_uri' => $keyword->uri,
                    'msl_original_keyword_vocab_uri' => $keyword->vocabulary->uri,
                ];

                $parents = $keyword->getAncestors();
                foreach ($parents as $parent) {
                    $originalKeywords[] = [
                        'msl_original_keyword_label' => $parent->label,
                        'msl_original_keyword_uri' => $parent->uri,
                        'msl_original_keyword_vocab_uri' => $parent->vocabulary->uri,
                    ];
                }
            }
        }

        // remove duplicates
        $keywords = [];
        $uris = [];
        foreach ($originalKeywords as $originalKeyword) {
            if (! in_array($originalKeyword['msl_original_keyword_uri'], $uris)) {
                $uris[] = $originalKeyword['msl_original_keyword_uri'];
                $keywords[] = $originalKeyword;
            }
        }

        return $keywords;
    }

    /**
     * Get geojson feature object string. Uses laboratory location if no equipment
     * location is set.
     */
    public static function getGeoJsonFeature(LaboratoryEquipment $equipment): string
    {
        if ($equipment->hasSpatialData()) {
            if ((strlen($equipment->latitude) > 0) && (strlen($equipment->longitude) > 0)) {
                return json_encode(
                    new Feature(
                        new Point((float) $equipment->longitude, (float) $equipment->latitude),
                        [
                            'title' => $equipment->name,
                            'name' => (string)$equipment->getScoutKey(),
                            'msl_id' => $equipment->id,
                            'msl_lab_ckan_name' => $equipment->laboratory->msl_identifier,
                            'msl_lab_name' => $equipment->laboratory->name,
                            'msl_domain_name' => $equipment->domain_name,
                            'msl_group_name' => $equipment->group_name,
                            'msl_type_name' => $equipment->type_name,
                        ]
                    )
                );
            } else {
                return json_encode(
                    new Feature(
                        new Point((float) $equipment->laboratory->longitude, (float) $equipment->laboratory->latitude),
                        [
                            'title' => $equipment->name,
                            'name' => (string)$equipment->getScoutKey(),
                            'msl_id' => $equipment->id,
                            'msl_lab_ckan_name' => $equipment->laboratory->msl_identifier,
                            'msl_lab_name' => $equipment->laboratory->name,
                            'msl_domain_name' => $equipment->domain_name,
                            'msl_group_name' => $equipment->group_name,
                            'msl_type_name' => $equipment->type_name,
                        ]
                    )
                );
            }
        }

        return '';
    }

    /**
     * Create point geojson string using latitude and longitude. Uses laboratory
     * location if none is set for equipment.
     */
    private static function getPointGeoJson(LaboratoryEquipment $equipment): string
    {
        if ($equipment->hasSpatialData()) {
            if ((strlen($equipment->latitude) > 0) && (strlen($equipment->longitude) > 0)) {
                return json_encode(
                    new Point((float) $equipment->longitude, (float) $equipment->latitude)
                );
            } else {
                return LaboratoryMapper::getPointGeoJson($equipment->laboratory);
            }
        }

        return '';
    }

}
