<?php
namespace App\Mappers\Additional;

use App\Mappers\Helpers\FigshareFilesHelper;
use App\Mappers\Helpers\RoCrateHelper;
use App\Models\Ckan\DataPublication;
use App\Models\Ckan\File;
use Exception;

class FigshareFileMapper implements AdditionalMapperInterface
{

    /**
     * Add figshare files associated by landing page/source
     * @param DataPublication $dataPublication
     * @return DataPublication
     */
    public function map(DataPublication $dataPublication): DataPublication
    {
        $figshareHelper = new FigshareFilesHelper;
        $roCrateHelper = new RoCrateHelper;        

        try{
            $roCrate = $figshareHelper->getRoCrate($dataPublication->msl_source);    
        }
        catch(Exception $e) {
            return $dataPublication;            
        }

        $filelist = $roCrateHelper->getFiles($roCrate);

        foreach($filelist as $file) {
            $mslFile = new File(
                $file['name'],                
                $file['contentUrl'],
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