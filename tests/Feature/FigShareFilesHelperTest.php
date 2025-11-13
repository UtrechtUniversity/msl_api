<?php

namespace Tests\Feature;

use App\Mappers\Helpers\FigshareFilesHelper;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Tests\TestCase;

class FigShareFilesHelperTest extends TestCase
{
    /**
     * Test retrieving ro crate
     */
    public function test_get_ro_crate(): void
    {
        $responseContentLandingpage = file_get_contents(base_path('/tests/MockData/Figshare/landingpage.txt'));
        $roCrateResponse = file_get_contents(base_path('/tests/MockData/Figshare/rocrate.txt'));

        $mock = new MockHandler([
            new Response(200, [], $responseContentLandingpage),
            new Response(200, [], $roCrateResponse),
        ]);

        $handler = HandlerStack::create($mock);

        $fileHelper = new FigshareFilesHelper(new Client(['handler' => $handler]));

        $roCrate = $fileHelper->getRoCrate('test');

        $this->assertEquals('https://w3id.org/ro/crate/1.1/context', $roCrate['@context']);
    }

    /**
     * Test getting ro crate with missing link in html
     */
    public function test_get_to_crate_not_found()
    {
        $responseContentLandingpage = file_get_contents(base_path('/tests/MockData/Figshare/missing_ro_crate_link.txt'));

        $mock = new MockHandler([
            new Response(200, [], $responseContentLandingpage),
        ]);

        $handler = HandlerStack::create($mock);

        $fileHelper = new FigshareFilesHelper(new Client(['handler' => $handler]));

        $this->expectException(Exception::class);
        $fileHelper->getRoCrate('test');
    }

    /**
     * test getting ro create with requestexception from Guzzle client
     */
    public function test_get_file_list_guzzle_exception()
    {
        $mock = new MockHandler([
            new RequestException('Error Communicating with Server', new Request('GET', 'test')),
        ]);

        $handler = HandlerStack::create($mock);

        $fileHelper = new FigshareFilesHelper(new Client(['handler' => $handler]));

        $this->expectException(RequestException::class);
        $fileHelper->getRoCrate('test');
    }
}
