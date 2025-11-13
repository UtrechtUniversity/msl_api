<?php

namespace App\Mappers\Additional;

use App\Mappers\Helpers\YodaDownloadHelper;
use App\Models\Ckan\DataPublication;
use App\Models\Ckan\File;
use App\Models\SourceDataset;
use Exception;

class YodaFileMapper implements AdditionalMapperInterface
{
    /**
     * Add figshare files associated by DOI
     */
    public function map(DataPublication $dataPublication, SourceDataset $sourceDataset): DataPublication
    {
        $yodaFileHelper = new YodaDownloadHelper;

        try {
            $filelist = $yodaFileHelper->getFileList($dataPublication->msl_source);
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
