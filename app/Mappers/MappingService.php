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

        // run description and title annotations and keyword detection
        $dataPublication = $keywordHelper->mapTextToKeywordsAnnotated($dataPublication, 'title', 'msl_title_annotated', 'title');
        $dataPublication = $keywordHelper->mapTextToKeywordsAnnotated($dataPublication, 'msl_description_abstract', 'msl_description_abstract_annotated', 'description abstract');
        $dataPublication = $keywordHelper->mapTextToKeywordsAnnotated($dataPublication, 'msl_description_methods', 'msl_description_methods_annotated', 'description methods');
        $dataPublication = $keywordHelper->mapTextToKeywordsAnnotated($dataPublication, 'msl_description_series_information', 'msl_description_series_information_annotated', 'description series');
        $dataPublication = $keywordHelper->mapTextToKeywordsAnnotated($dataPublication, 'msl_description_table_of_contents', 'msl_description_table_of_contents_annotated', 'description table of contents');
        $dataPublication = $keywordHelper->mapTextToKeywordsAnnotated($dataPublication, 'msl_description_technical_info', 'msl_description_technical_info_annotated', 'description technical information');
        $dataPublication = $keywordHelper->mapTextToKeywordsAnnotated($dataPublication, 'msl_description_other', 'msl_description_other_annotated', 'description other');

        // run additional mappers based on options
        if(isset($config['sourceDatasetProcessor']['options']['additionalMappers'])) {
            foreach($config['sourceDatasetProcessor']['options']['additionalMappers'] as $additionalMapper)
            {
                $mapper = new $additionalMapper;
                $dataPublication = $mapper->map($dataPublication);
            }
        }

        // validate data publication
        $validator = Validator::make((array)$dataPublication, $dataPublication::$importingRules);

        if($validator->fails()) {
            throw new MappingException('Datapublication could not be validated');
        }
        
        return $dataPublication;
    }
}