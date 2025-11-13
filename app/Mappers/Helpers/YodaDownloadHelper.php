<?php

namespace App\Mappers\Helpers;

use DOMDocument;
use DOMXPath;
use Exception;
use GuzzleHttp\Client;

class YodaDownloadHelper
{
    /**
     * @var GuzzleClient Guzzle HTTP client instance
     */
    private $client;

    /**
     * constructs a new YodaDownloadHelper
     *
     * @param  client  $client
     */
    public function __construct($client = new Client)
    {
        $this->client = $client;
    }

    /**
     * get a array with file information extracted via the Yoda landing page
     */
    public function getFileList(string $landingPageUrl): array
    {
        // Get landingpage
        $landingPage = $this->getpage($landingPageUrl);

        // Get url to contents page
        $contentUrl = $this->getContentUrl($landingPage);

        // Assume that every content page contains a 'original folder' and append to url
        $contentUrl .= '/original';

        // retrieve original folder
        $contentsPage = $this->getPage($contentUrl);

        // extract file information from page
        $files = $this->getFiles($contentsPage, $contentUrl);

        return $files;
    }

    /**
     * Get content of page by url
     */
    private function getPage($url): string
    {
        $response = $this->client->request('GET', $url);

        if (isset($response)) {
            return (string) $response->getBody();
        }

        throw new Exception('page retrieved empty');
    }

    /**
     * extract url of link to content page from landingpage source
     */
    private function getContentUrl(string $landingPageSource): string
    {
        $domDocument = new DOMDocument;
        $domDocument->loadHTML($landingPageSource, LIBXML_NOERROR);

        $xpath = new DOMXPath($domDocument);
        $query = '//*[contains(text(), "View contents")]';

        $matches = $xpath->query($query);
        if ($matches->length > 0) {
            $resultNode = $matches->item(0);

            return $resultNode->getAttribute('href');
        }

        throw new Exception('content url could not be extracted');
    }

    /**
     * extract file information from original folder in Yoda web file system
     */
    private function getFiles(string $contentPageSource, string $baseUrl): array
    {
        $files = [];

        $domDocument = new DOMDocument;
        $domDocument->loadHTML($contentPageSource, LIBXML_NOERROR);

        $xpath = new DOMXPath($domDocument);
        $query = '//div[contains(@class,"yoda-table")]/table/tbody/tr';

        $matches = $xpath->query($query);

        if ($matches->length > 0) {
            foreach ($matches as $match) {
                $isFolder = false;
                if ($match->className == 'object collection') {
                    $isFolder = true;
                }

                foreach ($match->childNodes as $childnode) {
                    if ($childnode->className == 'name') {
                        if ($isFolder) {
                            $file = [];
                            $file['fileName'] = $this->cleanFolderName($childnode->nodeValue);
                            $file['downloadLink'] = $baseUrl.'/'.$childnode->nodeValue;
                            $file['extension'] = '';
                            $file['isFolder'] = true;
                        } else {
                            $file = [];
                            $file['fileName'] = $childnode->nodeValue;
                            $file['downloadLink'] = $baseUrl.'/'.$childnode->nodeValue;
                            $file['extension'] = $this->extractFileExtension($childnode->nodeValue);
                            $file['isFolder'] = false;
                        }

                        $files[] = $file;
                    }
                }
            }
        }

        return $files;
    }

    /**
     * extract file extension from filename
     */
    private function extractFileExtension($filename)
    {
        $fileInfo = pathinfo($filename);
        if (isset($fileInfo['extension'])) {
            return $fileInfo['extension'];
        }

        return '';
    }

    /**
     * clean folder name as returned by Yoda
     */
    private function cleanFolderName($folderName)
    {
        return substr($folderName, 0, -1);
    }
}
