<?php

namespace App\Jobs\ImportProcessors;

use App\Models\Import;
use App\Jobs\ImportProcessors\ExtraPayloadProvider;
use Illuminate\Support\Facades\Storage;
use App\Models\SourceDatasetIdentifier;
use App\Jobs\ProcessSourceDatasetIdentifier;

class ProcessJsonListing implements ImportProcessorInterface
{
    public static function process(Import $import, bool|int $limit = false): bool
    {
        $importer = $import->importer;

        $filePath = $importer->options['importProcessor']['options']['filePath'];
            
        if(!isset($importer->options['importProcessor']['options']['identifierKey'])) {
            throw new \Exception('IdentifierKey required for jsonListing importProcessor.');
        }
        
        if(Storage::disk()->exists($filePath)) {
            $jsonEntries = json_decode(Storage::get($filePath), true);
            $identifierKey = $importer->options['importProcessor']['options']['identifierKey'];
            
            $counter = 0;
            foreach ($jsonEntries as $jsonEntry) {                
                if($limit) {
                    $counter++;
                    if($counter > $limit) {
                        break;
                    } 
                }

                if(isset($jsonEntry[$identifierKey])) {
                    $identifier = SourceDatasetIdentifier::create([
                        'import_id' => $import->id,
                        'identifier' => (string)$jsonEntry[$identifierKey],
                        'extra_payload' => ExtraPayloadProvider::getExtraPayload($importer, (string)$jsonEntry[$identifierKey])
                    ]);
                    
                    ProcessSourceDatasetIdentifier::dispatch($identifier);
                }
            }

            return true;
        }

        return false;        
    }
}