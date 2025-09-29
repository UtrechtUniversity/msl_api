<?php

namespace App\Jobs\SourceDatasetIdentifierProcessors;

use App\Datacite\Datacite;
use App\Jobs\ProcessSourceDataset;
use App\Models\SourceDataset;
use App\Models\SourceDatasetIdentifier;

class ProcessDataciteXmlRetrieval implements SourceDatasetIdentifierProcessorInterface
{

    public static function process(SourceDatasetIdentifier $sourceDatasetIdentifier): bool
    {
        $datacite = new Datacite;
        $result = $datacite->doisRequest($sourceDatasetIdentifier->identifier, true);

        if($result->response_code == 200) {                
            $xml = base64_decode($result->response_body['data']['attributes']['xml']);

            $SourceDataset = SourceDataset::create([
                'source_dataset_identifier_id'=> $sourceDatasetIdentifier->id,
                'import_id' => $sourceDatasetIdentifier->import->id,
                'source_dataset' => $xml,
            ]);
            
            ProcessSourceDataset::dispatch($SourceDataset);
            
            return true;
        }
        
        return false;
    }
}