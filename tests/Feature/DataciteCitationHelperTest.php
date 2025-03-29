<?php

namespace Tests\Feature;

use App\Mappers\Helpers\DataciteCitationHelper;
use Tests\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class DataciteCitationHelperTest extends TestCase
{
    /**
     * Test to check if citationstring is correctly returned.
     */
    public function test_datacite_citation_success(): void
    {
        $response = file_get_contents(base_path('/tests/MockData/DataCiteResponses/citation_response_200.txt'));
        
        $mock = new MockHandler([
            new Response(200, [], $response)
        ]);
    
        $handler = HandlerStack::create($mock);
        
        $citationHelper = new DataciteCitationHelper(new Client(['handler' => $handler]));

        $citationString = $citationHelper->getCitationString('j.tecto.2017.11.018');

        $this->assertEquals('Ritter, M. C., Santimano, T., Rosenau, M., Leever, K., & Oncken, O. (2018). Sandbox rheometry: Co-evolution of stress and strain in Riedel– and Critical Wedge–experiments. Tectonophysics, 722, 400–409. https://doi.org/10.1016/j.tecto.2017.11.018', $citationString);
    }

    /**
     * Test to check if citation helper returns an empty string when doi is not found
     */
    public function test_datacite_citation_notfound(): void
    {
        $response = file_get_contents(base_path('/tests/MockData/DataCiteResponses/citation_response_404.txt'));
        
        $mock = new MockHandler([
            new Response(404, [], $response)
        ]);
    
        $handler = HandlerStack::create($mock);
        
        $citationHelper = new DataciteCitationHelper(new Client(['handler' => $handler]));

        $citationString = $citationHelper->getCitationString('j.tecto.2017.');

        $this->assertEquals('', $citationString);
    }
}
