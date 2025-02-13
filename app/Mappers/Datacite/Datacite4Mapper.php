<?php
namespace App\Mappers\Datacite;

use App\Mappers\MapperInterface;
use App\Models\Ckan\DataPublication;
use App\Models\SourceDataset;

class Datacite4Mapper implements MapperInterface
{

    public function map(SourceDataset $sourceDataset): DataPublication
    {
        // create empty data publication
        $dataset = new DataPublication;

        // read json text
        $metadata = json_decode($sourceDataset->source_dataset, true);

        // set title
        $dataset->title = $metadata['data']['attributes']['titles'][0]['title'];

        return $dataset;
    }
}