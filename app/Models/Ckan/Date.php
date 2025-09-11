<?php
namespace App\Models\Ckan;

class Date implements CkanArrayInterface
{

    public string $msl_date_date;

    public string $msl_date_type;

    public string $msl_date_information;

    public function __construct($date, $type, $information = "")
    {
        $this->msl_date_date = $date;
        $this->msl_date_type = $type;
        $this->msl_date_information = $information;
    }

    public function toCkanArray(): array
    {        
        return (array) $this;
    }
}