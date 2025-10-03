<?php

namespace App\Response\V1\Elements;

class CollectionPeriod
{
    public $startDate = "";

    public $endDate = "";

    public function __construct($data) {
        if(isset($data['msl_collection_start_date'])) {
            $this->startDate = $data['msl_collection_start_date'];
        }

        if(isset($data['msl_collection_end_date'])) {
            $this->endDate = $data['msl_collection_end_date'];
        }
    }
}
