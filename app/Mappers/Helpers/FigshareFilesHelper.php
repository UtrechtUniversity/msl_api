<?php
namespace App\Mappers\Helpers;

use Exception;
use GuzzleHttp\Client;

class FigshareFilesHelper
{
    /**
     * @var GuzzleClient Guzzle HTTP client instance
     */
    protected $client;
    
    /**
     * Contructs a new FigshareFilesHelper
     * @param Client $client
     */
    public function __construct($client = new Client())
    {
        $this->client = $client;
    }
    
    /**
     * Get list of files for given doi
     * @param string $doi
     */
    public function getFileListByDOI(string $doi): array
    {        
        $articleId = $this->getArticleIdByDoi($doi);
        
        if($articleId) {
            return $this->getFileList($articleId);
        }
            
        return [];
    }
    
    /**
     * retrieve article id for given doi
     * @param string $doi
     */
    public function getArticleIdByDoi(string $doi) 
    {
        try {
            $response = $this->client->request(
                'POST',
                "https://api.figshare.com/v2/articles/search?doi=$doi",                
            );
        } catch (Exception $e) {
            
        }
        
        if(isset($response)) {
            $body = json_decode($response->getBody(), true);
            
            if(isset($body[0]['id'])) {
                return $body[0]['id'];
            } else {
                return null;
            }            
        }
    }
    
    /**
     * get array with file information by article id
     * @param $articleId
     */
    public function getFileList($articleId): array
    {
        try {
            $response = $this->client->request(
                'GET',
                "https://api.figshare.com/v2/articles/$articleId",
                );
        } catch (Exception $e) {
            
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

        return [];
    }    
}
