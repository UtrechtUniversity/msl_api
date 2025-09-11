<?php
namespace App\Mappers\Helpers;

class FigshareFilesHelper
{
    protected $client;
    
    
    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client();
    }
    
    public function getFileListByDOI($doi) {        
        $articleId = $this->getArticleIdByDoi($doi);
        
        if($articleId) {
            return $this->getFileList($articleId);
        }
            
        return [];
    }
    
    
    public function getArticleIdByDoi($doi) {
        try {
            $response = $this->client->request(
                'POST',
                "https://api.figshare.com/v2/articles/search?doi=$doi",                
            );
        } catch (\Exception $e) {
            
        }
        
        if(isset($response)) {
            $body = json_decode($response->getBody(), true);
            
            if(isset($body[0]['id'])) {
                return $body[0]['id'];
            }
            
            else {
                return null;
            }            
        }
    }
    
    public function getFileList($articleId) {
        try {
            $response = $this->client->request(
                'GET',
                "https://api.figshare.com/v2/articles/$articleId",
                );
        } catch (\Exception $e) {
            
        }
        
        if(isset($response)) {
            $body = json_decode($response->getBody(), true);
                        
            if(isset($body['files'])) {
                return $body['files'];
            }
            
            else {
                return [];
            }
        }
    }
    
}

