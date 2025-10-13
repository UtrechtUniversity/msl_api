<?php

namespace App\Response\V1\Elements;

class Contributor
{   
    public $contributorName = "";

    public $contributorOrcid = "";
    
    public $contributorScopus = "";

    public $contributorAffiliation = [];

    public $contributorRole = "";

    public function __construct($data) {
        if(isset($data['msl_contributor_name'])) {
            $this->contributorName = $data['msl_contributor_name'];
        }        

        if(isset($data['msl_contributor_type'])) {
            $this->contributorRole = $data['msl_contributor_type'];
        }
    }

}
