<?php
namespace App\Mappers\Additional;

use App\Models\Ckan\DataPublication;
use App\Models\SourceDataset;
use App\Models\Ckan\File;

class MagicFileMapper implements AdditionalMapperInterface
{
    /**
     * Add figshare files associated by landing page/source
     * @param DataPublication $dataPublication
     * @param SourceDataset $sourceDataset
     * @return DataPublication
     */
    public function map(DataPublication $dataPublication, SourceDataset $sourceDataset): DataPublication
    {
        $sourceIdentifier = $sourceDataset->source_dataset_identifier;
        $extraPayload = $sourceIdentifier->extra_payload;

        if(isset($extraPayload['contentUrl'])) {
            if(strlen($extraPayload['contentUrl']) > 1) {
                $mslFile = new File(
                    $this->extractFileName((string)$extraPayload['contentUrl']),
                    (string)$extraPayload['contentUrl'],
                    $this->extractFileExtension((string)$extraPayload['contentUrl']),
                    false
                );

                $dataPublication->addFile($mslFile);                
            }
        }

        return $dataPublication;
    }

    private function extractFileExtension($filename) {
        $fileInfo = pathinfo($filename);
        if(isset($fileInfo['extension'])) {
            return $fileInfo['extension'];
        }
        
        return '';
    }

    private function extractFilename($filename)
    {
        $fileInfo = pathinfo($filename);
        if(isset($fileInfo['basename'])) {
            return $fileInfo['basename'];
        }
        
        return '';
    }
}