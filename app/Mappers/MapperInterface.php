<?php
namespace App\Mappers;

use App\Models\Ckan\DataPublication;
use App\Models\SourceDataset;

interface MapperInterface
{
    /**
     * Maps metadata available in sourceDataset to BaseDataset class
     * 
     * @param SourceDataset $sourceDataset
     * @return DataPublication
     */
    public function map(SourceDataset $sourceDataset): DataPublication;
}