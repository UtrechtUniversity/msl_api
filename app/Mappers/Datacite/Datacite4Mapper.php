<?php
namespace App\Mappers\Datacite;

use App\Datasets\BaseDataset;
use App\Mappers\MapperInterface;
use App\Models\SourceDataset;

class Datacite4Mapper implements MapperInterface
{

    public function map(SourceDataset $sourceDataset): BaseDataset
    {
        // create empty BaseDataset
        $dataset = new BaseDataset;

        // read json text
        $metadata = json_decode($sourceDataset->source_dataset, true);

        // set title
        $dataset->title = $metadata['data']['attributes']['titles'][0]['title'];

        return $dataset;
    }
}