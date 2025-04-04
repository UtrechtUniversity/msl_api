<?php
namespace App\Mappers;

use App\Exceptions\MappingException;
use App\Mappers\Datacite\DataciteMapper;
use App\Mappers\Helpers\KeywordHelper;
use App\Models\Ckan\DataPublication;
use App\Models\Importer;
use App\Models\SourceDataset;
use Illuminate\Support\Facades\Validator;

class MappingService
{


    public function map(SourceDataset $sourceDataset, Importer $importer): DataPublication
    {
        $dataPublication = new DataPublication();
        $config = $importer->options;

        switch ($config['sourceDatasetProcessor']['type'])
        {
            case 'datacite':
                // extract metadata
                $metadata = json_decode($sourceDataset->source_dataset, true);

                $mapper = new DataciteMapper();
                $dataPublication = $mapper->map($metadata, $dataPublication);
                break;
        }

        // set general fields independend of mapping implementation
        
        // set owner organization for data publication
        $dataPublication->owner_org = $importer->data_repository->ckan_name;

        // map keywords
        $keywordHelper = new KeywordHelper;
        $dataPublication = $keywordHelper->mapTagsToKeywords($dataPublication);

        // run additional mappers based on options

        // validate data publication
        $validator = Validator::make((array)$dataPublication, $dataPublication::$importingRules);

        if($validator->fails()) {
            throw new MappingException('Datapublication could not be validated');
        }
        
        return $dataPublication;
    }
}