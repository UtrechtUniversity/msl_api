<?php
namespace App\Mappers\Datacite;

use App\Datasets\BaseDataset;
use App\Mappers\MapperInterface;
use App\Models\SourceDataset;

class DataciteMapper implements MapperInterface
{

    public function map(SourceDataset $sourceDataset): BaseDataset
    {
        $mapper = new Datacite4Mapper();

        return $mapper->map($sourceDataset);        
    }
}