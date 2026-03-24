<?php

namespace App\Mappers\Additional;

use App\Models\Ckan\DataPublication;
use App\Models\SourceDataset;

interface AdditionalMapperInterface
{
    /**
     * @param array<string, string>
     * @return void
     */
    public function __construct(array $args);

    /**
     * Maps additional content based upon a provided datapublications values
     */
    public function map(DataPublication $dataPublication, SourceDataset $sourceDataset): DataPublication;
}
