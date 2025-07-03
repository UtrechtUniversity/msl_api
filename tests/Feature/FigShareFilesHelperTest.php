<?php

namespace Tests\Feature;

use App\Mappers\Helpers\FigshareFilesHelper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class FigShareFilesHelperTest extends TestCase
{
    /**
     * Test retrieveing article id by doi
     */
    public function test_get_article_id_by_doi(): void
    {
        $response = file_get_contents(base_path('/tests/MockData/Figshare/articles_12697364.v2.txt'));
        
        $mock = new MockHandler([
            new Response(200, [], $response)
        ]);
    
        $handler = HandlerStack::create($mock);
        
        $figshareHelper = new FigshareFilesHelper(new Client(['handler' => $handler]));

        $this->assertEquals('12697364', $figshareHelper->getArticleIdByDoi('10.4121/12697364.v2'));
    }

    /**
     * Test retrieveing file list by doi
     */
    public function test_get_file_list(): void
    {
        $response = file_get_contents(base_path('/tests/MockData/Figshare/articles_12697364.txt'));
        
        $mock = new MockHandler([
            new Response(200, [], $response)
        ]);
    
        $handler = HandlerStack::create($mock);
        
        $figshareHelper = new FigshareFilesHelper(new Client(['handler' => $handler]));

        $results = $figshareHelper->getFileList('12697364');

        $this->assertEquals('README.txt', $results[0]['name']);
        $this->assertEquals('https://ndownloader.figshare.com/files/24044669', $results[0]['download_url']);

        $this->assertEquals('data.zip', $results[1]['name']);
        $this->assertEquals('https://ndownloader.figshare.com/files/24044672', $results[1]['download_url']);
    }

    /**
     * Test retrieveing file list by doi
     */
    public function test_get_file_list_by_doi(): void
    {
        $response1 = file_get_contents(base_path('/tests/MockData/Figshare/articles_12697364.v2.txt'));
        $response2 = file_get_contents(base_path('/tests/MockData/Figshare/articles_12697364.txt'));
        
        $mock = new MockHandler([
            new Response(200, [], $response1),
            new Response(200, [], $response2),
        ]);
    
        $handler = HandlerStack::create($mock);
        
        $figshareHelper = new FigshareFilesHelper(new Client(['handler' => $handler]));

        $results = $figshareHelper->getFileListByDOI('10.4121/12697364.v2');

        $this->assertEquals('README.txt', $results[0]['name']);
        $this->assertEquals('https://ndownloader.figshare.com/files/24044669', $results[0]['download_url']);

        $this->assertEquals('data.zip', $results[1]['name']);
        $this->assertEquals('https://ndownloader.figshare.com/files/24044672', $results[1]['download_url']);
    }

    public function test_get_file_list_by_doi_article_not_found(): void
    {

    }

    public function test_get_file_list_by_doi_files_not_found(): void
    {

    }

    public function test_get_file_list_by_doi_guzzle_error(): void
    {
        
    }
}
