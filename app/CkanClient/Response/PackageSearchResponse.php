<?php
namespace App\CkanClient\Response;

class PackageSearchResponse extends BaseResponse
{
    

    public function __construct($body, $responseCode) {
        parent::__construct($body, $responseCode);
    }

    /**
     * returns total result count returned by ckan search request
     * 
     * @return int
     */
    public function getTotalResultsCount(): int
    {
        return $this->responseBody['result']['count'];
    }

    /**
     * returns inner results array
     * 
     * @return array
     */
    public function getResults(): array
    {
        return $this->responseBody['result']['results'];
    }

    /**
     * returns array containing facet information
     * 
     * @return array
     */
    public function getFacets(): array
    {
        return $this->responseBody['result']['search_facets'];
    }
}