<?php

namespace App\Jobs\SourceDatasetIdentifierProcessors;

use App\Jobs\ProcessSourceDataset;
use App\Models\SourceDataset;
use App\Models\SourceDatasetIdentifier;
use Phpoaipmh\Endpoint;

class ProcessOaiRetrieval implements SourceDatasetIdentifierProcessorInterface
{

    public static function process(SourceDatasetIdentifier $sourceDatasetIdentifier): bool
    {
        $endPoint = Endpoint::build($sourceDatasetIdentifier->import->importer->options['identifierProcessor']['options']['oaiEndpoint']);
        $result = $endPoint->getRecord($sourceDatasetIdentifier->identifier, $sourceDatasetIdentifier->import->importer->options['identifierProcessor']['options']['metadataPrefix']);

        if($result) {
            $SourceDataset = SourceDataset::create([
                'source_dataset_identifier_id'=> $sourceDatasetIdentifier->id,
                'import_id' => $sourceDatasetIdentifier->import->id,
                'source_dataset' => $result->asXML()
            ]);
            
            ProcessSourceDataset::dispatch($SourceDataset);
            
            return true;
        }
        return false;
    }
}