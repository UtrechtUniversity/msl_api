<?php

namespace App\Response\Elements;

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

        if(isset($data['msl_contributor_orcid'])) {
            $this->contributorOrcid = $data['msl_contributor_orcid'];
        }
        
        if(isset($data['msl_contributor_scopus'])) {
            $this->contributorScopus = $data['msl_contributor_scopus'];
        }

        if(isset($data['msl_contributor_affiliation'])) {
            $this->contributorAffiliation = $data['msl_contributor_affiliation'];
        }

        if(isset($data['msl_contributor_role'])) {
            $this->contributorRole = $data['msl_contributor_role'];
        }
    }

}
