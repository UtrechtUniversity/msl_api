<?php

namespace App\Response;

use App\Response\Elements\EquipmentPiece;

class FacilitiesResult
{
    public $name = '';

    public $description = '';

    public $descriptionHtml = '';

    public $domain = '';

    public $latitude = '';

    public $longitude = '';

    public $altitude = '';

    public $portalLink = '';

    public $organization = '';

    public $equipment = [];

    public function __construct($data)
    {

        if (isset($data['title'])) {
            $this->name = $data['title'];
        }

        if (isset($data['msl_description'])) {
            $this->description = $data['msl_description'];
        }

        if (isset($data['msl_description_html'])) {
            $this->descriptionHtml = $data['msl_description_html'];
        }

        if (isset($data['msl_domain_name'])) {
            $this->domain = $data['msl_domain_name'];
        }

        if (isset($data['msl_latitude'])) {
            $this->latitude = $data['msl_latitude'];
        }

        if (isset($data['msl_longitude'])) {
            $this->longitude = $data['msl_longitude'];
        }

        if (isset($data['msl_altitude'])) {
            $this->altitude = $data['msl_altitude'];
        }

        $this->portalLink = route('lab-detail', ['id' => $data['name']]);

        if (isset($data['msl_organization_name'])) {
            $this->organization = $data['msl_organization_name'];
        }

        if (isset($data['msl_laboratory_equipment'])) {
            foreach ($data['msl_laboratory_equipment'] as $equipmentEntry) {
                $equipmentInstance = new EquipmentPiece($equipmentEntry);
                $this->equipment[] = $equipmentInstance;
            }
        }

    }
}
