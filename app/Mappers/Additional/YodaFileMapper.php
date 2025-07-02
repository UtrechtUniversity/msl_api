<?php
namespace App\Mappers\Additional;

use App\Mappers\Helpers\YodaDownloadHelper;
use App\Models\Ckan\DataPublication;
use App\Models\Ckan\File;
use Exception;

class YodaFileMapper extends AdditionalMapperInterface
{
 
    /**
     * Add figshare files associated by DOI
     * @param DataPublication $dataPublication
     * @return DataPublication
     */
    public function map(DataPublication $dataPublication): DataPublication
    {
        $yodaFileHelper = new YodaDownloadHelper;

        try {
            $filelist = $yodaFileHelper->getFileList($dataPublication->msl_doi);
        }
        catch(Exception $e) {
            return $dataPublication;
        }

        foreach($filelist as $file) {
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