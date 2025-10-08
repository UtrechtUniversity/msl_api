<?php

namespace App\Jobs\ImportProcessors;

use App\Jobs\ImportProcessors\ExtraPayloadProvider;
use App\Jobs\ProcessSourceDatasetIdentifier;
use App\Models\Import;
use Phpoaipmh\Endpoint;
use App\Models\SourceDatasetIdentifier;

class ProcessOaiListing implements ImportProcessorInterface
{
    public static function process(Import $import, bool|int $limit = false): bool
    {
        $importer = $import->importer;
        $endPoint = Endpoint::build($importer->options['importProcessor']['options']['oaiEndpoint']);
            
        try {
            $results = $endPoint->listIdentifiers($importer->options['importProcessor']['options']['metadataPrefix'], null, null, $importer->options['importProcessor']['options']['setDefinition']);
        } catch (\Exception $e) {
            return false;
        }
        
        if($results->getTotalRecordCount() > 0) {            
            $counter = 0;
            foreach($results as $item) {
                if($limit) {
                    $counter++;
                    if($counter > $limit) {
                        break;
                    } 
                }

                $identifier = SourceDatasetIdentifier::create([
                    'import_id' => $import->id,
                    'identifier' => (string)$item->identifier,
                    'extra_payload' => ExtraPayloadProvider::getExtraPayload($importer, (string)$item->identifier)
                ]);
                
                ProcessSourceDatasetIdentifier::dispatch($identifier);
            }

            return true;
        }

        return false;
    }
}