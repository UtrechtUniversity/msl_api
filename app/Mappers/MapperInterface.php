<?php

namespace App\Mappers;

use App\Models\Ckan\DataPublication;

interface MapperInterface
{
    /**
     * Maps metadata available to BaseDataset class
     */
    public function map(array $metadata, DataPublication $dataPublication): DataPublication;
}
