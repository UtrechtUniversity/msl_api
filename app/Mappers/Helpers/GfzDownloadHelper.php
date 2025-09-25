<?php
namespace App\Mappers\Helpers;

use DOMDocument;
use DOMXPath;
use Exception;
use GuzzleHttp\Client;

class GfzDownloadHelper
{
    /**
     * @var GuzzleClient Guzzle HTTP client instance
     */
    private $client;
    
    /**
     * constructs a new GfzDownloadHelper
     * @param client $client
     */
    public function __construct($client = new Client()) 
    {
        $this->client = $client;
    }

    public function getFiles(string $landingPageUrl)
    {
        // get landing page
        $landingPage = $this->getpage($landingPageUrl);

        // get downloads page url
        $downloadsUrl = $this->getDownloadsUrl($landingPage);

        // get downloads page
        $downloadsPage = $this->getpage($downloadsUrl);

        // extract files from downloads page
        return $this->getFilesFromPage($downloadsPage, $downloadsUrl);
    }

    /**
     * Get content of page by url
     */
    private function getPage($url): string
    {
        $response = $this->client->request('GET', $url);

        if(isset($response)) {
            return (string)$response->getBody();
        }

        throw new Exception('page retrieved empty');
    }

    /**
     * extract url of link to downloads page from landingpage source
     */
    private function getDownloadsUrl(string $landingPageSource): string
    {
        $domDocument = new DOMDocument();
        $domDocument->loadHTML($landingPageSource, LIBXML_NOERROR);

        $xpath = new DOMXPath($domDocument);
        // Link text sometimes contains whitespace at end...
        $query = '//a[contains(text(), "Download data and description")]';

        $matches = $xpath->query($query);
        if($matches->length > 0) {
            $resultNode = $matches->item(0);
            return $resultNode->getAttribute('href');
        }

        // some landing pages have a different name for the download link
        $query = '//a[text() = "Download data"]';

        $matches = $xpath->query($query);
        if($matches->length > 0) {
            $resultNode = $matches->item(0);
            return $resultNode->getAttribute('href');
        }

        throw new Exception('download url could not be extracted');
    }

    /**
     * extract file information from downloads page source
     */
    private function getFilesFromPage(string $downsloadPageSource, string $baseUrl): array
    {
        $domDocument = new DOMDocument();
        $domDocument->loadHTML($downsloadPageSource, LIBXML_NOERROR);

        $xpath = new DOMXPath($domDocument);
        $query = '//body/pre/a';

        $files = [];
        $matches = $xpath->query($query);

        // first 5 a elements are ui elements
        if($matches->length > 5) {
            for($i = 5; $i < $matches->length; $i++) {
                $isFolder = false;
                if(substr($matches->item($i)->getAttribute('href'), -1) == "/") {
                    $isFolder = true;
                }

                $file = [];
                $file['fileName'] = $matches->item($i)->nodeValue;
                $file['downloadLink'] = $baseUrl . $matches->item($i)->getAttribute('href');
                $file['extension'] = $this->extractFileExtension($matches->item($i)->nodeValue);
                $file['isFolder'] = $isFolder;

                $files[] = $file;
            }
        }

        return $files;
    }
    
    private function extractFileExtension($filename) {
        $fileInfo = pathinfo($filename);
        if(isset($fileInfo['extension'])) {
            return $fileInfo['extension'];
        }
        
        return '';
    }    
}

