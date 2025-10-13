<?php

namespace App\Response\V1\Elements;

class CoveredPeriod
{
    public $startDate = "";

    public $endDate = "";

    public function __construct($data) {
        if(isset($data['msl_covered_start_date'])) {
            $this->startDate = $data['msl_covered_start_date'];
        }

        if(isset($data['msl_covered_end_date'])) {
            $this->endDate = $data['msl_covered_end_date'];
        }
    }
}
