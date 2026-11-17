<?php

namespace App\Mappers\DataPublicationImport\Helpers;

use App\Mappers\Helpers\GuzzleClient;
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
     *
     * @param client $client
     */
    public function __construct(Client $client = new Client(['http_errors' => false,]))
    {
        $this->client = $client;
    }

    public function getFiles(string $doi)
    {
        // get downloads page url
        $downloadsUrl = $this->getDownloadsUrl($doi);
        $downloadsPageResponse = $this->getpage($downloadsUrl);

        if($downloadsPageResponse->getStatusCode() != 200) {
            $downloadsUrl = $this->getDownloadsUrl(strtoupper($doi));
            $downloadsPageResponse = $this->getpage($downloadsUrl);
        }

        if($downloadsPageResponse->getStatusCode() != 200) {
            return [];
        }

        // get downloads page
        $downloadsPage = (string) $downloadsPageResponse->getBody();

        // extract files from downloads page
        return $this->getFilesFromPage($downloadsPage, $downloadsUrl);
    }

    /**
     * Get content of page by url
     */
    private function getPage($url)
    {
        return $this->client->request('GET', $url);
    }

    /**
     * extract url of link to downloads page from landingpage source
     */
    private function getDownloadsUrl(string $doi): string
    {
        $baseUrl = 'https://datapub.gfz.de/download/';

        $escapedDoi = str_replace('gfz', 'GFZ', $doi);
        $escapedDoi = str_replace('/', '.', $escapedDoi);

        return $baseUrl.$escapedDoi.'/';
    }

    /**
     * extract file information from downloads page source
     */
    private function getFilesFromPage(string $downloadPageSource, string $baseUrl): array
    {
        $domDocument = new DOMDocument;
        $domDocument->loadHTML($downloadPageSource, LIBXML_NOERROR);

        $xpath = new DOMXPath($domDocument);
        $query = '//body/pre/a';

        $files = [];
        $matches = $xpath->query($query);

        // first 5 a elements are ui elements
        if ($matches->length > 5) {
            for ($i = 5; $i < $matches->length; $i++) {
                $isFolder = false;
                if (substr($matches->item($i)->getAttribute('href'), -1) == '/') {
                    $isFolder = true;
                }

                $file = [];
                $file['fileName'] = $matches->item($i)->nodeValue;
                $file['downloadLink'] = $baseUrl.$matches->item($i)->getAttribute('href');
                $file['extension'] = $this->extractFileExtension($matches->item($i)->nodeValue);
                $file['isFolder'] = $isFolder;

                $files[] = $file;
            }
        }

        return $files;
    }

    private function extractFileExtension($filename)
    {
        $fileInfo = pathinfo($filename);
        if (isset($fileInfo['extension'])) {
            return $fileInfo['extension'];
        }

        return '';
    }
}
