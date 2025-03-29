<?php
namespace App\Mappers\Datacite;

use App\Exceptions\MappingException;
use App\Mappers\MapperInterface;
use App\Models\Ckan\DataPublication;

class DataciteMapper implements MapperInterface
{

    public function map(array $metadata, DataPublication $dataPublication): DataPublication
    {
        // select version based on metadata
        switch ($metadata['metadataVersion'])
        {
            case 4:
                $mapper = new Datacite4Mapper();
                break;

            default:
                throw new MappingException('No DataCiteMapper found for version:' . $metadata['metadataVersion']);
        }

        $dataset = $mapper->map($metadata, $dataPublication);

        // map aditional fields that are independed of the schema version
        
        // set data publication name
        $dataPublication->name = $this->createDatasetNameFromDoi($dataPublication->msl_doi);

        return $dataset;
    }

    private function createDatasetNameFromDoi(string $doi) 
    {        
        return md5($doi);
    }
}