<?php
namespace App\Mappers;

use App\Exceptions\MappingException;
use App\Mappers\Datacite\DataciteMapper;
use App\Models\Ckan\DataPublication;
use App\Models\SourceDataset;
use Illuminate\Support\Facades\Validator;

class MappingService
{


    public function map(SourceDataset $sourceDataset, $config): DataPublication
    {
        $dataPublication = new DataPublication();

        switch ($config['sourceDatasetProcessor']['type'])
        {
            case 'datacite':
                // extract metadata
                $metadata = json_decode($sourceDataset->source_dataset, true);

                $mapper = new DataciteMapper();
                $dataPublication = $mapper->map($metadata, $dataPublication);
                break;
        }

        // run additional mappers based on options

        // validate data publication
        $validator = Validator::make((array)$dataPublication, $dataPublication::$importingRules);

        if($validator->fails()) {
            throw new MappingException('Datapublication could not be validated');
        }
        
        return $dataPublication;
    }
}