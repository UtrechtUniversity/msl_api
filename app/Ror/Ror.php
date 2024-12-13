<?php
namespace App\Ror;

use Illuminate\Support\Facades\App;

class Ror
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
    
    
    public function singleRecordRequest($ror)
    {        
        $result =  new \stdClass();               
        
        try {
            $response = $this->client->request('GET', "https://api.ror.org/organizations/$ror", [
                'headers' => [
                    
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

