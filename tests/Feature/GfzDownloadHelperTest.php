<?php

namespace Tests\Feature;

use App\Mappers\Helpers\GfzDownloadHelper;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Tests\TestCase;

class GfzDownloadHelperTest extends TestCase
{
    public function test_get_file_list_files_only(): void
    {
        $responseContentLandingPage = file_get_contents(base_path('/tests/MockData/Gfz/landingpage1.txt'));
        $responseContentDownloadPage = file_get_contents(base_path('/tests/MockData/Gfz/filepage1.txt'));

        $mock = new MockHandler([
            new Response(200, [], $responseContentLandingPage),
            new Response(200, [], $responseContentDownloadPage),
        ]);

        $handler = HandlerStack::create($mock);

        $fileHelper = new GfzDownloadHelper(new Client(['handler' => $handler]));

        $fileList = $fileHelper->getFiles('test');

        $this->assertEquals('2024-001_Wittmann-et-al_Data-Description.pdf', $fileList[0]['fileName']);
        $this->assertEquals('https://datapub.gfz-potsdam.de/download/10.5880.GFZ.3.3.2024.001jbbbh2024-001_Wittmann-et-al_Data-Description.pdf', $fileList[0]['downloadLink']);
        $this->assertEquals('pdf', $fileList[0]['extension']);
        $this->assertEquals(false, $fileList[0]['isFolder']);

        $this->assertEquals('2024-001_Wittmann-et-al_Data.zip', $fileList[1]['fileName']);
        $this->assertEquals('https://datapub.gfz-potsdam.de/download/10.5880.GFZ.3.3.2024.001jbbbh2024-001_Wittmann-et-al_Data.zip', $fileList[1]['downloadLink']);
        $this->assertEquals('zip', $fileList[1]['extension']);
        $this->assertEquals(false, $fileList[1]['isFolder']);
    }

    public function test_get_file_list_files_and_folder(): void
    {
        $responseContentLandingPage = file_get_contents(base_path('/tests/MockData/Gfz/landingpage2.txt'));
        $responseContentDownloadPage = file_get_contents(base_path('/tests/MockData/Gfz/filepage2.txt'));

        $mock = new MockHandler([
            new Response(200, [], $responseContentLandingPage),
            new Response(200, [], $responseContentDownloadPage),
        ]);

        $handler = HandlerStack::create($mock);

        $fileHelper = new GfzDownloadHelper(new Client(['handler' => $handler]));

        $fileList = $fileHelper->getFiles('test');

        $this->assertEquals('previous-versions/', $fileList[0]['fileName']);
        $this->assertEquals('https://datapub.gfz-potsdam.de/download/10.5880.GFZ.1.4.2019.005/previous-versions/', $fileList[0]['downloadLink']);
        $this->assertEquals('', $fileList[0]['extension']);
        $this->assertEquals(true, $fileList[0]['isFolder']);

        $this->assertEquals('2019-005_Koerting-et-al_Apliki_version-2.0.zip', $fileList[1]['fileName']);
        $this->assertEquals('https://datapub.gfz-potsdam.de/download/10.5880.GFZ.1.4.2019.005/2019-005_Koerting-et-al_Apliki_version-2.0.zip', $fileList[1]['downloadLink']);
        $this->assertEquals('zip', $fileList[1]['extension']);
        $this->assertEquals(false, $fileList[1]['isFolder']);

        $this->assertEquals('2019-005_Koerting_et-al_Technical_report_Apliki_version-2.0.pdf', $fileList[2]['fileName']);
        $this->assertEquals('https://datapub.gfz-potsdam.de/download/10.5880.GFZ.1.4.2019.005/2019-005_Koerting_et-al_Technical_report_Apliki_version-2.0.pdf', $fileList[2]['downloadLink']);
        $this->assertEquals('pdf', $fileList[2]['extension']);
        $this->assertEquals(false, $fileList[2]['isFolder']);
    }

    public function test_get_file_list_content_not_found()
    {
        $responseContentLandingpage = file_get_contents(base_path('/tests/MockData/Gfz/landingpage_missing_link.txt'));

        $mock = new MockHandler([
            new Response(200, [], $responseContentLandingpage),
        ]);

        $handler = HandlerStack::create($mock);

        $fileHelper = new GfzDownloadHelper(new Client(['handler' => $handler]));

        $this->expectException(Exception::class);
        $fileHelper->getFiles('test');
    }

    public function test_get_file_list_guzzle_exception()
    {
        $mock = new MockHandler([
            new RequestException('Error Communicating with Server', new Request('GET', 'test')),
        ]);

        $handler = HandlerStack::create($mock);

        $fileHelper = new GfzDownloadHelper(new Client(['handler' => $handler]));

        $this->expectException(RequestException::class);
        $fileHelper->getFiles('test');
    }
}
