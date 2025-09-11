<?php
namespace App\Mappers\Helpers;

class DataciteCitationHelper
{
    protected $client;
    
    
    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client();
    }
    
    public function getCitationString($doi)
    {
        try {
            $response = $this->client->request(
                'GET',
                "https://doi.org/doi:$doi",
                [
                    'headers' => [
                        'Accept' => 'text/x-bibliography; style=apa'
                    ]
                    
                ]
            );
        } catch (\Exception $e) {
            
        }
        
        if(isset($response)) {
            return (string)$response->getBody();
        }
        return "";
    }
    
    
}

