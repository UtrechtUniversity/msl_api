<?php
namespace App\Mappers\Datacite;

use PHPUnit\Util\Json;
use PhpParser\JsonDecoder;
use App\Models\SourceDataset;
use App\Mappers\MapperInterface;
use App\Models\Ckan\DataPublication;

class Datacite4Mapper implements MapperInterface
{

    public function map(SourceDataset $sourceDataset): DataPublication
    {
        // create empty data publication
        $dataset = new DataPublication;

        // read json text
        $metadata = json_decode($sourceDataset->source_dataset, true);

        // map title
        $this->mapExample($metadata, $dataset);

        dd($dataset->title);

        return $dataset;
    }


    public function mapExample(array $metadata, DataPublication $dataset)
    {
 
    }

}