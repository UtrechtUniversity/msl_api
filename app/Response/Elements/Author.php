<?php

namespace App\Response\Elements;

class Author
{
    public $authorName = "";
    
    public $authorOrcid = "";
    
    public $authorScopus = "";

    public $authorAffiliation = [];

    public function __construct($data) {
        if(isset($data['msl_author_name'])) {
            $this->authorName = $data['msl_author_name'];
        }        

        if(isset($data['msl_author_orcid'])) {
            $this->authorOrcid = $data['msl_author_orcid'];
        }
        
        if(isset($data['msl_author_scopus'])) {
            $this->authorScopus = $data['msl_author_scopus'];
        }
        
        if(isset($data['msl_author_affiliation'])) {
            $this->authorAffiliation = $data['msl_author_affiliation'];
        }
    }

}
