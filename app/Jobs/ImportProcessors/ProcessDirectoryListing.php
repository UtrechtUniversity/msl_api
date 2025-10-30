<?php

namespace App\Jobs\ImportProcessors;

use App\Jobs\ProcessSourceDatasetIdentifier;
use App\Models\Import;
use App\Models\SourceDatasetIdentifier;
use Illuminate\Support\Facades\Storage;

class ProcessDirectoryListing implements ImportProcessorInterface
{
    public static function process(Import $import, bool|int $limit = false): bool
    {
        $importer = $import->importer;

        if (! isset($importer->options['importProcessor']['options']['directoryPath'])) {
            throw new \Exception('DirectoryPath setting required for directoryListing importProcessor.');
        }
        if (! isset($importer->options['importProcessor']['options']['recursive'])) {
            throw new \Exception('Recursive setting required for directoryListing importProcessor.');
        }

        $directoryPath = $importer->options['importProcessor']['options']['directoryPath'];

        $fileList = Storage::disk()->files($directoryPath, (bool) $importer->options['importProcessor']['options']['recursive']);

        $counter = 0;
        foreach ($fileList as $file) {
            if ($limit) {
                $counter++;
                if ($counter > $limit) {
                    break;
                }
            }

            if ($file !== '') {
                $identifier = SourceDatasetIdentifier::create([
                    'import_id' => $import->id,
                    'identifier' => (string) $file,
                    'extra_payload' => ExtraPayloadProvider::getExtraPayload($importer, (string) $file),
                ]);

                ProcessSourceDatasetIdentifier::dispatch($identifier);
            }
        }

        return true;
    }
}
