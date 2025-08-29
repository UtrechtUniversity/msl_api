<?php

namespace App\Jobs\SourceDatasetIdentifierProcessors;

use App\Jobs\ProcessSourceDataset;
use App\Models\SourceDataset;
use App\Models\SourceDatasetIdentifier;
use Illuminate\Support\Facades\Storage;

class ProcessFileRetrieval implements SourceDatasetIdentifierProcessorInterface
{

    public static function process(SourceDatasetIdentifier $sourceDatasetIdentifier): bool
    {
        if(Storage::disk()->exists($sourceDatasetIdentifier->identifier)) {
            $fileContent = Storage::get($sourceDatasetIdentifier->identifier);
            
            $SourceDataset = SourceDataset::create([
                'source_dataset_identifier_id'=> $sourceDatasetIdentifier->id,
                'import_id' => $sourceDatasetIdentifier->import->id,
                'source_dataset' => $fileContent
            ]);
            
            ProcessSourceDataset::dispatch($SourceDataset);
            
            return true;
        }

        return false;
    }
}