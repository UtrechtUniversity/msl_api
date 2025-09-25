<?php
namespace App\Mappers\Additional;

use App\Models\Ckan\DataPublication;
use App\Models\SourceDataset;

interface AdditionalMapperInterface
{

    /**
     * Maps additional content based upon a provided datapublications values
     * @param DataPublication $dataPublication
     * @param SourceDataset $sourceDataset
     * @return DataPuclication
     */
    public function map(DataPublication $dataPublication, SourceDataset $sourceDataset): DataPublication;
}