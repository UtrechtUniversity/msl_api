<?php
namespace App\Mappers\Datacite;

use App\Mappers\MapperInterface;
use App\Models\Ckan\DataPublication;
use App\Models\SourceDataset;

class DataciteMapper implements MapperInterface
{

    public function map(SourceDataset $sourceDataset): DataPublication
    {
        // create empty data publication
        $dataset = new DataPublication;

        // read json text
        $metadata = json_decode($sourceDataset->source_dataset, true);

        // map somelthing
        $this->mapPlaceholder($metadata, $dataset);

        dd($dataset->title);

        return $dataset;
    }

    public function mapPlaceholder(array $metadata, DataPublication $dataset){

    }
}