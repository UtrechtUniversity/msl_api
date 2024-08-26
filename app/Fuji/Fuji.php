<?php
namespace App\fuji;

use Illuminate\Support\Facades\App;
use GuzzleHttp\RequestOptions;

class Fuji
{
    
    protected $client;
    
    public function __construct()
    {
        if (App::environment('local')) {
            $this->client = new \GuzzleHttp\Client(['verify' => false, 'http_errors' => false]);
        } else {
            $this->client = new \GuzzleHttp\Client(['http_errors' => false]);
        }
    }
    
    
    public function evaluateRequest($doi)
    {        
        $result =  new \stdClass();
        
        try {
            $response = $this->client->request('POST', "10.0.2.2:1071/fuji/api/v1/evaluate", [
                'headers' => ['Accept' => 'application/json'],
                'auth' => [
                    'laurens',
                    'testtest'
                ],
                'json' => ['object_identifier' => "$doi"]                
            ]);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
        
        $result->response_code = $response->getStatusCode();
        $result->response_body = [];
        
        if($result->response_code == 200) {
            $result->response_body = json_decode($response->getBody(), true);
        }
                
        return $result;
    }
    
    
}

