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

    /**
     * Test /all API endpoint based on mocked CKAN request
     * 
     * @return void
     */
    public function test_all_success_results(): void
    {
        $this->app->bind(ApiController::class, function($app){
            $response = file_get_contents(base_path('/tests/MockData/CkanResponses/package_search_datapublications_all_2818.txt'));
        
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
                ->where('result.count', 2818)
                ->where('result.resultCount', 10)
                ->has('result.results.0', fn (AssertableJson $json) =>
                    $json->where('title', 'North America during the Lower Cretaceous: new palaeomagnetic constraints from intrusions in New England (Dataset)')
                        ->etc()
                )
                ->etc()
        );
    }

    /**
     * Test /rock_physics API endpoint based on mocked CKAN request
     * 
     * @return void
     */
    public function test_rockphysics_success_results(): void
    {

    }

    /**
     * Test /analogue API endpoint based on mocked CKAN request
     * 
     * @return void
     */
    public function test_analogue_success_results(): void
    {

    }

    /**
     * Test /paleo API endpoint based on mocked CKAN request
     * 
     * @return void
     */
    public function test_paleo_success_results(): void
    {

    }

    /**
     * Test /microscopy API endpoint based on mocked CKAN request
     * 
     * @return void
     */
    public function test_microscopy_success_results(): void
    {

    }

    /**
     * Test /geochemistry API endpoint based on mocked CKAN request
     * 
     * @return void
     */
    public function test_geochemistry_success_results(): void
    {

    }

    /**
     * Test /all endpoint with error received from CKAN
     * 
     * @return void
     */
    public function test_all_error_ckan(): void
    {

    }

    /**
     * Test /all endpoint with empty resultset received from CKAN
     * 
     * @return void
     */
    public function test_all_success_empty(): void
    {

    }

    /**
     * Test /all endpoint with Exception returned by GuzzleClient
     * 
     * @return void
     */
    public function test_all_guzzle_exception(): void
    {

    }

}
