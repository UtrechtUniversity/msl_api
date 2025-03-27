<?php
namespace App\Mappers\Datacite;

use App\Mappers\MapperInterface;
use App\Models\Ckan\DataPublication;

class DataciteMapper implements MapperInterface
{

    public function map(array $metadata, DataPublication $dataPublication): DataPublication
    {
        // select version based on metadata

        // map aditional fields that independed of schema version

        $mapper = new Datacite4Mapper();

        return $mapper->map($metadata, $dataPublication);
    }
}