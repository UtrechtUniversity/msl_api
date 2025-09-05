<?php

namespace App\Jobs\ImportProcessors;

use App\Models\Importer;
use Illuminate\Support\Facades\Storage;

class ExtraPayloadProvider
{
    public static function getExtraPayload(Importer $importer, string $identifier)
    {
        if(isset($importer->options['importProcessor']['extra_data_loader'])) {
            $extraDataLoader = $importer->options['importProcessor']['extra_data_loader'];
            
            if($extraDataLoader['type'] == 'jsonLoader') {
                $filePath = $extraDataLoader['options']['filePath'];
                $dataMappings = $extraDataLoader['options']['dataKeyMapping'];
                $identifierKey = $importer->options['importProcessor']['options']['identifierKey'];
                
                if(Storage::disk()->exists($filePath)) {
                    $jsonEntries = json_decode(Storage::get($filePath), true);
                    
                    foreach ($jsonEntries as $jsonEntry) {
                        if(isset($jsonEntry[$identifierKey])) {
                            if($jsonEntry[$identifierKey] == $identifier) {
                                $result = [];
                                
                                foreach ($dataMappings as $mappingKey => $mappingValue) {
                                    if(isset($jsonEntry[$mappingKey])) {
                                        $result[$mappingValue] = $jsonEntry[$mappingKey];
                                    }
                                }
                                
                                return $result;
                            }
                        }                        
                    }
                }
            }            
        }        
        
        return [];
    }
}