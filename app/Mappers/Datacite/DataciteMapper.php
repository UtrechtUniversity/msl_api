<?php
namespace App\Mappers\Datacite;

use App\Exceptions\MappingException;
use App\Mappers\Helpers\DataciteCitationHelper;
use App\Mappers\MapperInterface;
use App\Models\Ckan\DataPublication;

class DataciteMapper implements MapperInterface
{

    public function map(array $metadata, DataPublication $dataPublication): DataPublication
    {
        // select version based on metadata
        switch ($metadata['data']['attributes']['schemaVersion'])
        {
            case 'http://datacite.org/schema/kernel-4':
                $mapper = new Datacite4Mapper();
                break;

            default:
                throw new MappingException('No DataCiteMapper found for version:' . $metadata['metadataVersion']);
        }

        $dataPublication = $mapper->map($metadata, $dataPublication);

        // map aditional fields that are independed of the schema version
        
        // set data publication name
        $dataPublication->name = $this->createDatasetNameFromDoi($dataPublication->msl_doi);

        // get citation string
        $citationHelper = new DataciteCitationHelper();
        $dataPublication->msl_citation = $citationHelper->getCitationString($dataPublication->msl_doi);

        return $dataPublication;
    }

    /**
     * create name for data publication
     * @param string $doi
     * @return string
     */
    private function createDatasetNameFromDoi(string $doi): string
    {        
        return md5($doi);
    }
}