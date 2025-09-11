<?php
namespace App\Mappers\Helpers;

class FTPHelper
{
    
    
    public function getDirectoryListing($hostName, $directory, $sourceDataset, $anonymous = true, $passiveMode = true)
    {
        $ftp = ftp_connect($hostName);
        
        if(!$ftp) {
            
        }
        
        
    }
    
    
}

