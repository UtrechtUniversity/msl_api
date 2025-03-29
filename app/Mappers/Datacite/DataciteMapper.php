<?php
namespace App\Mappers\Datacite;

use App\Mappers\MapperInterface;
use App\Models\Ckan\DataPublication;

class DataciteMapper implements MapperInterface
{

    public function map(array $metadata, DataPublication $dataPublication): DataPublication
    {
        // select version based on metadata
        $mapper = new Datacite4Mapper();

        $dataset = $mapper->map($metadata, $dataPublication);

        // map aditional fields that independed of schema version

        // set data publication name
        $dataPublication->name = $this->createDatasetNameFromDoi($dataPublication->msl_doi);

        return $dataset;
    }

    private function createDatasetNameFromDoi(string $doi) 
    {        
        return md5($doi);
    }
}