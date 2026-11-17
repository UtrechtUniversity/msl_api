<?php

namespace App\Mappers\DataPublicationImport\Additional;

use App\Mappers\DataPublicationImport\Helpers\GfzDownloadHelper;
use App\Models\Ckan\DataPublication;
use App\Models\Ckan\File;
use App\Models\SourceDataset;
use Exception;

class GfzFileMapper extends AdditionalMapper
{
    protected function validateInput(array $args): array
    {
        return [];
    }

    protected function initialize(array $args): void {}

    /**
     * Add GFZ files associated by DOI
     */
    public function map(DataPublication $dataPublication, SourceDataset $sourceDataset): DataPublication
    {
        $gfzFileHelper = new GfzDownloadHelper;

        try {
            $fileList = $gfzFileHelper->getFiles($dataPublication->msl_doi);
        } catch (Exception $e) {
            return $dataPublication;
        }

        foreach ($fileList as $file) {
            $dataPublication->addFile(
                new File(
                    $file['fileName'],
                    $file['downloadLink'],
                    $file['extension'],
                    $file['isFolder']
                )
            );
        }

        return $dataPublication;
    }
}
