<?php
namespace App\Mappers\Helpers;

use Exception;
use DOMDocument;
use DOMXPath;
use GuzzleHttp\Client;

class FigshareFilesHelper
{
    /**
     * @var GuzzleClient Guzzle HTTP client instance
     */
    protected $client;
    
    /**
     * Contructs a new FigshareFilesHelper
     * @param Client $client
     */
    public function __construct($client = new Client())
    {
        $this->client = $client;
    }

    /**
     * Get ro crate by url of landing page
     */
    public function getRoCrate($url): array
    {
        // get landingpage
        $page = $this->getPage($url);

        // get ro-crate location
        $roCrateLocation = $this->getRoCrateUrl($page);

        // get the ro crate
        $roCrate = $this->getPage($roCrateLocation);

        return json_decode($roCrate, true);
    }
    
    /**
     * get page content by url
     */
    private function getPage($url)
    {
        $response = $this->client->request('GET', $url);

        if(isset($response)) {
            return (string)$response->getBody();
        }

        throw new Exception('page retrieved empty');
    }

    /**
     * get the url of the ro crate location from html
     */
    private function getRoCrateUrl($page)
    {
        $domDocument = new DOMDocument();
        $domDocument->loadHTML($page, LIBXML_NOERROR);

        $xpath = new DOMXPath($domDocument);
        $query = '//a[contains(@title, "RO-Crate Metadata")]';

        $matches = $xpath->query($query);
        if($matches->length > 0) {
            $resultNode = $matches->item(0);
            return $resultNode->getAttribute('href');
        }

        throw new Exception('ro crate location could not be extracted');
    }    
}