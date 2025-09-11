<?php
namespace App\Datacite;

class Datacite
{
    
    protected $client;
    
    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client();
    }
    
    
    public function doisRequest($doi, $retryOnFailure = false)
    {
        $doi = urlencode($doi);
        $result =  new \stdClass();
        
        try {
            $response = $this->client->request('GET', "https://api.datacite.org/dois/$doi", [
                'headers' => [
                    'Accept' => 'application/vnd.api+json',
                ],
            ]);
        } catch (\Exception $e) {
            if($retryOnFailure) {
                sleep(1);
                $this->doisRequest($doi);
            }
            
            $result->response_code = $e->getCode();
            $result->response_body = [];
            return $result;
        }
        
        
        $result->response_code = $response->getStatusCode();
        $result->response_body = [];
        
        if($result->response_code == 200) {
            $result->response_body = json_decode($response->getBody(), true);
        }
                
        return $result;
    }
    
    
}

