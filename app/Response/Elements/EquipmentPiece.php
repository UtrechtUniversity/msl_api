<?php

namespace App\Response\Elements;

class EquipmentPiece
{
    public $title = '';
    public $description= '';
    public $descriptionHtml ='';
    public $domain ='';
    public $category = '';
    public $type = '';
    public $group = '';
    public $brand = '';

    public function __construct($data) {
        if(isset($data['msl_laboratory_equipment_title'])) {
            $this->title= $data['msl_laboratory_equipment_title'];
        }
        if(isset($data['msl_laboratory_equipment_description'])) {
            $this->description= $data['msl_laboratory_equipment_description'];
        }
        if(isset($data['msl_laboratory_equipment_description_html'])) {
            $this->descriptionHtml= $data['msl_laboratory_equipment_description_html'];
        }
        if(isset($data['msl_laboratory_equipment_domain'])) {
            $this->domain= $data['msl_laboratory_equipment_domain'];
        }
        if(isset($data['msl_laboratory_equipment_category'])) {
            $this->category= $data['msl_laboratory_equipment_category'];
        }
        if(isset($data['msl_laboratory_equipment_type'])) {
            $this->type= $data['msl_laboratory_equipment_type'];
        }
        if(isset($data['msl_laboratory_equipment_group'])) {
            $this->group= $data['msl_laboratory_equipment_group'];
        }
        if(isset($data['msl_laboratory_equipment_brand'])) {
            $this->brand= $data['msl_laboratory_equipment_brand'];
        }
    }

}