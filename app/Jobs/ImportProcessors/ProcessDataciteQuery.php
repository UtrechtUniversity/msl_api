<?php

namespace App\Jobs\ImportProcessors;

use App\Datacite\Datacite;
use App\Jobs\ProcessSourceDatasetIdentifier;
use App\Models\Import;
use App\Models\SourceDatasetIdentifier;

class ProcessDataciteQuery implements ImportProcessorInterface
{
    public static function process(Import $import, bool|int $limit = false): bool
    {
        $importer = $import->importer;

        if (! isset($importer->options['importProcessor']['options']['query'])) {
            throw new \Exception('Query setting required for DataciteQuery importProcessor.');
        }
        if (! isset($importer->options['importProcessor']['options']['prefix'])) {
            throw new \Exception('Prefix setting required for DataciteQuery importProcessor.');
        }
        if (! isset($importer->options['importProcessor']['options']['pageSize'])) {
            throw new \Exception('PageSize required for DataciteQuery importProcessor.');
        }

        $dataCite = new Datacite;

        $result = $dataCite->cursorSearchRequest(
            $importer->options['importProcessor']['options']['query'],
            $importer->options['importProcessor']['options']['prefix'],
            'doi', (int) $importer->options['importProcessor']['options']['pageSize']
        );

        $count = 0;

        foreach ($result->response_body['data'] as $data) {
            if ($limit) {
                $count++;
                if ($count > $limit) {
                    return true;
                }
            }

            $identifier = SourceDatasetIdentifier::create([
                'import_id' => $import->id,
                'identifier' => $data['attributes']['doi'],
                'extra_payload' => ExtraPayloadProvider::getExtraPayload($importer, $data['attributes']['doi']),
            ]);

            ProcessSourceDatasetIdentifier::dispatch($identifier);
        }

        while (isset($result->response_body['links']['next'])) {
            $result = $dataCite->cursorPageRequest($result->response_body['links']['next']);

            foreach ($result->response_body['data'] as $data) {
                if ($limit) {
                    $count++;
                    if ($count > $limit) {
                        return true;
                    }
                }

                $identifier = SourceDatasetIdentifier::create([
                    'import_id' => $import->id,
                    'identifier' => $data['attributes']['doi'],
                    'extra_payload' => ExtraPayloadProvider::getExtraPayload($importer, $data['attributes']['doi']),
                ]);

                ProcessSourceDatasetIdentifier::dispatch($identifier);
            }
        }

        return true;
    }
}
