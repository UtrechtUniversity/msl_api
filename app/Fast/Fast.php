<?php
namespace App\Fast;

use Illuminate\Support\Facades\App;

class Fast
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
    
    
    public function facilityRequest($facilityId)
    {        
        $result =  new \stdClass();               
        
        try {
            $response = $this->client->request('GET', "https://fast.geo.uu.nl/api/facility/$facilityId", [
                'headers' => [
                    'Authorization' => config('fast.fast_api_token'),
                ],
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

    public function metaTreeRequest()
    {
        $result =  new \stdClass();               
        
        try {
            $response = $this->client->request('GET', "https://fast.geo.uu.nl/api/meta_tree", [
                'headers' => [
                    'Authorization' => config('fast.fast_api_token'),
                ],
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

