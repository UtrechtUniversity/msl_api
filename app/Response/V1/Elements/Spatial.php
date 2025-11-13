<?php

namespace App\Response\V1\Elements;

class Spatial
{
    public $eLong = '';

    public $nLat = '';

    public $sLate = '';

    public $wLong = '';

    public function __construct($data)
    {
        if (isset($data['msl_elong'])) {
            $this->eLong = $data['msl_elong'];
        }

        if (isset($data['msl_nLat'])) {
            $this->nLat = $data['msl_nLat'];
        }

        if (isset($data['msl_sLat'])) {
            $this->sLate = $data['msl_sLat'];
        }

        if (isset($data['msl_wLong'])) {
            $this->wLong = $data['msl_wLong'];
        }
    }
}
