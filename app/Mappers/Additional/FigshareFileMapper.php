<?php
namespace App\Mappers\Additional;

use App\Mappers\Helpers\FigshareFilesHelper;
use App\Models\Ckan\DataPublication;
use App\Models\Ckan\File;

class FigshareFileMapper implements AdditionalMapperInterface
{

    /**
     * Add figshare files associated by DOI
     * @param DataPublication $dataPublication
     * @return DataPublication
     */
    public function map(DataPublication $dataPublication): DataPublication
    {
        $figshareHelper = new FigshareFilesHelper;

        $filelist = $figshareHelper->getFileListByDOI($dataPublication->msl_doi);

        foreach($filelist as $file) {
            $mslFile = new File(
                $file['name'],                
                $file['download_url'],
                $this->extractFileExtension($file['name']),
                false
            );

            $dataPublication->addFile($mslFile);
        }
        
        return $dataPublication;
    }

    /**
     * extract extension from full file name
     * @param string $filename
     */
    private function extractFileExtension(string $filename): string
    {
        $fileInfo = pathinfo($filename);
        if(isset($fileInfo['extension'])) {
            return $fileInfo['extension'];
        }
        
        return '';
    }
}