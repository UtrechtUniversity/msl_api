<?php
namespace App\Mappers\Datacite;

use App\Mappers\MapperInterface;
use App\Models\Ckan\DataPublication;
use App\Models\SourceDataset;

class DataciteMapper implements MapperInterface
{

    public function map(SourceDataset $sourceDataset): DataPublication
    {
        $mapper = new Datacite4Mapper();

        return $mapper->map($sourceDataset);        
    }
}