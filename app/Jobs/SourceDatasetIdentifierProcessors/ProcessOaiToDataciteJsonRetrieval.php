<?php

namespace App\Jobs\SourceDatasetIdentifierProcessors;

use App\Datacite\Datacite;
use App\Jobs\ProcessSourceDataset;
use App\Models\SourceDataset;
use App\Models\SourceDatasetIdentifier;
use Phpoaipmh\Endpoint;

class ProcessOaiToDataciteJsonRetrieval implements SourceDatasetIdentifierProcessorInterface
{
    public static function process(SourceDatasetIdentifier $sourceDatasetIdentifier): bool
    {
        $endPoint = Endpoint::build($sourceDatasetIdentifier->import->importer->options['identifierProcessor']['options']['oaiEndpoint']);
        $xml = $endPoint->getRecord($sourceDatasetIdentifier->identifier, $sourceDatasetIdentifier->import->importer->options['identifierProcessor']['options']['metadataPrefix']);

        if ($xml) {
            $xml->registerXPathNamespace('oai', 'http://www.openarchives.org/OAI/2.0/');
            $xml->registerXPathNamespace('default', 'http://datacite.org/schema/kernel-4');

            $doi = $xml->xpath('/oai:OAI-PMH/oai:GetRecord/oai:record/oai:metadata/default:resource/default:identifier/node()');
            if ($doi[0]) {
                $datacite = new Datacite;
                $result = $datacite->doisRequest((string) $doi[0], true, false);

                if ($result->response_code == 200) {
                    $SourceDataset = SourceDataset::create([
                        'source_dataset_identifier_id' => $sourceDatasetIdentifier->id,
                        'import_id' => $sourceDatasetIdentifier->import->id,
                        'source_dataset' => $result->response_body,
                    ]);

                    ProcessSourceDataset::dispatch($SourceDataset);

                    return true;
                }
            }
        }

        return false;
    }
}
