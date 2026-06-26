<?php

namespace App\Mappers\Ckan;

use App\GeoJson\Feature\Feature;
use App\GeoJson\Geometry\Point;
use App\Models\Laboratory\Laboratory;

class LaboratoryMapper
{
    public static function fromLaboratory(Laboratory $laboratory): array
    {
        return [
            'title' => $laboratory->name,
            'type' => $laboratory->getCkanType(),
            'name' => (string)$laboratory->getScoutKey(),
            'owner_org' => 'epos-multi-scale-laboratories-thematic-core-service',
            'msl_fast_id' => $laboratory->fast_id,
            'msl_description' => $laboratory->description,
            'msl_description_html' => $laboratory->description_html,
            'msl_website' => $laboratory->website,
            'msl_address_street_1' => $laboratory->address_street_1,
            'msl_address_street_2' => $laboratory->address_street_2,
            'msl_address_postalcode' => $laboratory->address_postalcode,
            'msl_address_city' => $laboratory->address_city,
            'msl_msl_address_country_code' => $laboratory->address_country_code,
            'msl_address_country_name' => $laboratory->address_country_name,
            'msl_domain_name' => $laboratory->fast_domain_name,
            'msl_organization_name' => $laboratory->laboratoryOrganization ? $laboratory->laboratoryOrganization->name : '',
            'msl_latitude' => $laboratory->latitude,
            'msl_longitude' => $laboratory->longitude,
            'msl_altitude' => $laboratory->altitude,
            'msl_location' => self::getGeoJsonFeature($laboratory),
            'msl_has_spatial_data' => $laboratory->hasSpatialData(),
            'msl_laboratory_equipment' => self::getLimitedEquipment($laboratory),
            'extras' => [
                ['key' => 'spatial', 'value' => self::getPointGeoJson($laboratory)],
            ],
        ];
    }

    /**
     * Returns a limited set of properties for each equipment belonging to the lab.
     * This data is used in CKAN to populate the facility API endpoint working around the
     * need to combine equipment and laboratory datatypes seperately.
     */
    private static function getLimitedEquipment(Laboratory $laboratory): array
    {
        $equipment = [];

        $equipmentList = $laboratory->laboratoryEquipment;

        foreach ($equipmentList as $equipmentEntry) {
            $equipment[] = [
                'msl_laboratory_equipment_title' => $equipmentEntry->name,
                'msl_laboratory_equipment_description' => $equipmentEntry->description,
                'msl_laboratory_equipment_description_html' => $equipmentEntry->description_html,
                'msl_laboratory_equipment_domain' => $equipmentEntry->domain_name,
                'msl_laboratory_equipment_category' => $equipmentEntry->category_name,
                'msl_laboratory_equipment_type' => $equipmentEntry->type_name,
                'msl_laboratory_equipment_group' => $equipmentEntry->group_name,
                'msl_laboratory_equipment_brand' => $equipmentEntry->brand,
            ];
        }

        return $equipment;
    }

    /**
     * Create point geojson string using latitude and longitude.
     */
    public static function getPointGeoJson(Laboratory $laboratory): string
    {
        if ($laboratory->hasSpatialData()) {
            return json_encode(
                new Point((float) $laboratory->longitude, (float) $laboratory->latitude)
            );
        }

        return '';
    }

    /**
     * Get geojson feature object string.
     */
    private static function getGeoJsonFeature(Laboratory $laboratory): string
    {
        if ($laboratory->hasSpatialData()) {
            return json_encode(
                new Feature(
                    new Point((float) $laboratory->longitude, (float) $laboratory->latitude),
                    [
                        'title' => $laboratory->name,
                        'name' => (string)$laboratory->getScoutKey(),
                        'msl_id' => $laboratory->id,
                        'msl_organization_name' => $laboratory->laboratoryOrganization->name,
                        'msl_domain_name' => $laboratory->fast_domain_name,
                    ]
                )
            );
        }

        return '';
    }
}
