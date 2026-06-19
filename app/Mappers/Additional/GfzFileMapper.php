<?php

namespace App\Mappers\Additional;

use App\Mappers\Helpers\GfzDownloadHelper;
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
     * Add figshare files associated by DOI
     */
    public function map(DataPublication $dataPublication, SourceDataset $sourceDataset): DataPublication
    {
        $yodaFileHelper = new GfzDownloadHelper;

        try {
            $filelist = $yodaFileHelper->getFiles($dataPublication->msl_source);
        } catch (Exception $e) {
            return $dataPublication;
        }

        foreach ($filelist as $file) {
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
