<?php

namespace App\CkanClient\Response;

class PackageSearchResponse extends BaseResponse
{
    public function __construct($body, $responseCode)
    {
        parent::__construct($body, $responseCode);
    }

    /**
     * returns total result count returned by ckan search request
     */
    public function getTotalResultsCount(): int
    {
        return $this->responseBody['result']['count'];
    }

    /**
     * returns inner results array
     */
    public function getResults(): array
    {
        return $this->responseBody['result']['results'];
    }

    /**
     * returns array containing facet information
     */
    public function getFacets(): array
    {
        return $this->responseBody['result']['search_facets'];
    }
}
