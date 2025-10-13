<?php

namespace App\Response\V1\Elements;

class Pid
{
    public $identifier = "";

    public $identifierType = "";

    public function __construct($data) {
        if(isset($data['msl_pid'])) {
            $this->identifier = $data['msl_pid'];
        }

        if(isset($data['msl_identifier_type'])) {
            $this->identifierType = $data['msl_identifier_type'];
        }
    }
}
