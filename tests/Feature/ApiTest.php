<?php

namespace Tests\Feature;

use App\Http\Controllers\ApiController;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ApiTest extends TestCase
{

    public function test_all_succes_results(): void
    {
        $this->app->bind(ApiController::class, function($app){
            $response = file_get_contents(base_path('/tests/MockData/CkanResponses/package_search_datapublications_31.txt'));
        
            $mock = new MockHandler([
                new Response(200, [], $response)
            ]);
        
            $handler = HandlerStack::create($mock);
                    
            return new ApiController(new Client(['handler' => $handler]));
        });
        
        $response = $this->get('webservice/api/all?hasDownloads=false');
        
        $response->assertStatus(200);
        
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('success')
                ->where('success', true)
                ->where('result.count', 31)
                ->where('result.resultCount', 10)
                ->has('result.results.0', fn (AssertableJson $json) =>
                    $json->where('title', 'Dataset of particle size distribution data of Holocene volcanic ashes of NW Argentina')
                        ->etc()
                )
                ->etc()
        );
    }

    public function test_all_error_ckan(): void
    {

    }

    public function test_all_success_empty(): void
    {

    }

}
