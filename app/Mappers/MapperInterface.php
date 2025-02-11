<?php
namespace App\Mappers;

use App\Datasets\BaseDataset;
use App\Models\SourceDataset;

interface MapperInterface
{
    /**
     * Maps metadata available in sourceDataset to BaseDataset class
     * 
     * @param SourceDataset $sourceDataset
     * @return BaseDataset
     */
    public function map(SourceDataset $sourceDataset): BaseDataset;
}