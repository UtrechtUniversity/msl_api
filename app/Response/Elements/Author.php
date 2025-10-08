<?php

namespace App\Response\Elements;

class Author
{
    public $authorName = "";
    
    public $authorOrcid = "";
    
    public $authorScopus = "";

    public $authorAffiliation = [];

    public function __construct($data) {
        if(isset($data['msl_creator_name'])) {
            if(strlen($data['msl_creator_name']) > 0) {
                $this->authorName = $data['msl_creator_name'];
            }
            else
            {
                $name = $data['msl_creator_given_name'];
                if(strlen($name) > 0) {
                    $name .= " " . $data['msl_creator_family_name'];
                } else {
                    $name = $data['msl_creator_family_name'];
                }

                $this->authorName = $name;
            }
        }
    }

}
