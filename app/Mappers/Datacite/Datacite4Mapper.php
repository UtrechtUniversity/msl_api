<?php
namespace App\Mappers\Datacite;

use App\Datasets\BaseDataset;
use App\Mappers\MapperInterface;
use App\Models\SourceDataset;

class Datacite4Mapper implements MapperInterface
{

    public function map(SourceDataset $sourceDataset): BaseDataset
    {
        $dataset = new BaseDataset;

        return $dataset;
    }
}