<?php

namespace Tests\Feature;

use App\Mappers\Helpers\YodaDownloadHelper;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use Tests\TestCase;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;

class YodaDownloadHelperTest extends TestCase
{

    public function test_get_file_list_files_only(): void
    {
        $responseContentLandingpage = file_get_contents(base_path('/tests/MockData/Yoda/UU01-A8BLMR/landingpage.txt'));
        $responseContentOriginal = file_get_contents(base_path('/tests/MockData/Yoda/UU01-A8BLMR/contentpage_original.txt'));

        $mock = new MockHandler([
            new Response(200, [], $responseContentLandingpage),
            new Response(200, [], $responseContentOriginal),
        ]);
    
        $handler = HandlerStack::create($mock);
        
        $fileHelper = new YodaDownloadHelper(new Client(['handler' => $handler]));
        
        $fileList = $fileHelper->getFileList('test');

        $this->assertEquals('Data_explanation.pdf', $fileList[0]['fileName']);
        $this->assertEquals('https://geo.public.data.uu.nl:443/vault-seismic-slip-pulse-experiments/research-seismic-slip-pulse-experiments[1618835278]/original/Data_explanation.pdf', $fileList[0]['downloadLink']);
        $this->assertEquals('pdf', $fileList[0]['extension']);
        $this->assertEquals(false, $fileList[0]['isFolder']);

        $this->assertEquals('HV_slip_pulse_data.zip', $fileList[1]['fileName']);
        $this->assertEquals('https://geo.public.data.uu.nl:443/vault-seismic-slip-pulse-experiments/research-seismic-slip-pulse-experiments[1618835278]/original/HV_slip_pulse_data.zip', $fileList[1]['downloadLink']);
        $this->assertEquals('zip', $fileList[1]['extension']);
        $this->assertEquals(false, $fileList[1]['isFolder']);
        
        $this->assertEquals('yoda-metadata.json', $fileList[2]['fileName']);
        $this->assertEquals('https://geo.public.data.uu.nl:443/vault-seismic-slip-pulse-experiments/research-seismic-slip-pulse-experiments[1618835278]/original/yoda-metadata.json', $fileList[2]['downloadLink']);
        $this->assertEquals('json', $fileList[2]['extension']);
        $this->assertEquals(false, $fileList[2]['isFolder']);
    }

    public function test_get_file_list_mixed(): void
    {
        $responseContentLandingpage = file_get_contents(base_path('/tests/MockData/Yoda/UU01-575EWU/landingpage.txt'));
        $responseContentOriginal = file_get_contents(base_path('/tests/MockData/Yoda/UU01-575EWU/contentpage_original.txt'));

        $mock = new MockHandler([
            new Response(200, [], $responseContentLandingpage),
            new Response(200, [], $responseContentOriginal),
        ]);
    
        $handler = HandlerStack::create($mock);
        
        $fileHelper = new YodaDownloadHelper(new Client(['handler' => $handler]));

        $fileList = $fileHelper->getFileList('test');

        $this->assertEquals('calibration', $fileList[0]['fileName']);
        $this->assertEquals('https://geo.public.data.uu.nl:443/vault-sandstone-compaction/Mehranpour_et_al_2021_DEModeling[1621322230]/original/calibration/', $fileList[0]['downloadLink']);
        $this->assertEquals('', $fileList[0]['extension']);
        $this->assertEquals(true, $fileList[0]['isFolder']);

        $this->assertEquals('contact model', $fileList[1]['fileName']);
        $this->assertEquals('https://geo.public.data.uu.nl:443/vault-sandstone-compaction/Mehranpour_et_al_2021_DEModeling[1621322230]/original/contact model/', $fileList[1]['downloadLink']);
        $this->assertEquals('', $fileList[1]['extension']);
        $this->assertEquals(true, $fileList[1]['isFolder']);
        
        $this->assertEquals('PFC code', $fileList[2]['fileName']);
        $this->assertEquals('https://geo.public.data.uu.nl:443/vault-sandstone-compaction/Mehranpour_et_al_2021_DEModeling[1621322230]/original/PFC code/', $fileList[2]['downloadLink']);
        $this->assertEquals('', $fileList[2]['extension']);
        $this->assertEquals(true, $fileList[2]['isFolder']);

        $this->assertEquals('uniaxial compaction', $fileList[3]['fileName']);
        $this->assertEquals('https://geo.public.data.uu.nl:443/vault-sandstone-compaction/Mehranpour_et_al_2021_DEModeling[1621322230]/original/uniaxial compaction/', $fileList[3]['downloadLink']);
        $this->assertEquals('', $fileList[3]['extension']);
        $this->assertEquals(true, $fileList[3]['isFolder']);

        $this->assertEquals('Mehranpour_et-al_2021-description.docx', $fileList[4]['fileName']);
        $this->assertEquals('https://geo.public.data.uu.nl:443/vault-sandstone-compaction/Mehranpour_et_al_2021_DEModeling[1621322230]/original/Mehranpour_et-al_2021-description.docx', $fileList[4]['downloadLink']);
        $this->assertEquals('docx', $fileList[4]['extension']);
        $this->assertEquals(false, $fileList[4]['isFolder']);

        $this->assertEquals('yoda-metadata.json', $fileList[5]['fileName']);
        $this->assertEquals('https://geo.public.data.uu.nl:443/vault-sandstone-compaction/Mehranpour_et_al_2021_DEModeling[1621322230]/original/yoda-metadata.json', $fileList[5]['downloadLink']);
        $this->assertEquals('json', $fileList[5]['extension']);
        $this->assertEquals(false, $fileList[5]['isFolder']);
    }

    public function test_get_file_list_content_not_found()
    {
        $responseContentLandingpage = file_get_contents(base_path('/tests/MockData/Yoda/errors/missing_link.txt'));

        $mock = new MockHandler([
            new Response(200, [], $responseContentLandingpage),
        ]);
    
        $handler = HandlerStack::create($mock);
        
        $fileHelper = new YodaDownloadHelper(new Client(['handler' => $handler]));

        $this->expectException(Exception::class);
        $fileHelper->getFileList('test');
    }

    public function test_get_file_list_guzzle_exception()
    {
        $mock = new MockHandler([
            new RequestException('Error Communicating with Server', new Request('GET', 'test')),
        ]);
    
        $handler = HandlerStack::create($mock);
        
        $fileHelper = new YodaDownloadHelper(new Client(['handler' => $handler]));

        $this->expectException(RequestException::class);
        $fileHelper->getFileList('test');        
    }
}
