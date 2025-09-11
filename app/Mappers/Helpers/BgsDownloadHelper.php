<?php
namespace App\Mappers\Helpers;

use App\Datasets\BaseDataset;
use GuzzleHttp\Client;


class BgsDownloadHelper
{
    private $client;
    
    public function __construct() {
        $this->client = new Client();
    }
    
    public function addData(BaseDataset $dataset, $identifier, $baseUrl) {                        
        $response = $this->client->request('GET', "$baseUrl/$identifier");
                
        if($response->getStatusCode() == 200) {
            $responseData = json_decode($response->getBody()->getContents(), true);
                                    
            if(isset($responseData['attributes']['fileList'])) {
                if(count($responseData['attributes']['fileList']) > 0) {
                    foreach ($responseData['attributes']['fileList'] as $fileListItem) {
                        
                        $file = [
                            'msl_file_name' => $fileListItem['name'],
                            'msl_download_link' => "https://webservices.bgs.ac.uk/accessions/download/$identifier?fileName=" . $fileListItem['name'],
                            'msl_extension' => $this->extractFileExtension($fileListItem['name']),
                            'msl_timestamp' => ''
                        ];
                        
                        $dataset->msl_downloads[] = $file;
                    }
                }
            }                        
        }
        
        return $dataset;
    }
       
    private function extractFileName($filename) {
        $fileInfo = pathinfo($filename);
        if(isset($fileInfo['filename'])) {
            return $fileInfo['filename'];
        }
        
        return '';
    }
    
    private function extractFileExtension($filename) {
        $fileInfo = pathinfo($filename);
        if(isset($fileInfo['extension'])) {
            return $fileInfo['extension'];
        }
        
        return '';
    }
    
    
    
}

