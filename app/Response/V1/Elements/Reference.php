<?php

namespace App\Response\V1\Elements;

class Reference
{
    public $referenceDoi = '';

    public $referenceHandle = '';

    public $referenceTitle = '';

    public $referenceType = '';

    public function __construct($data)
    {
        if (isset($data['msl_reference_doi'])) {
            $this->referenceDoi = $data['msl_reference_doi'];
        }

        if (isset($data['msl_reference_handle'])) {
            $this->referenceHandle = $data['msl_reference_handle'];
        }

        if (isset($data['msl_reference_title'])) {
            $this->referenceTitle = $data['msl_reference_title'];
        }

        if (isset($data['msl_reference_type'])) {
            $this->referenceType = $data['msl_reference_type'];
        }
    }
}
