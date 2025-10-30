<?php

namespace App\CkanClient\Response;

use App\Models\Ckan\DataPublication;

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
    public function getResults(bool $autoCast = false): array
    {
        if (! $autoCast) {
            return $this->responseBody['result']['results'];
        }

        $results = [];
        foreach ($this->responseBody['result']['results'] as $result) {
            switch ($this->responseBody['result']['type']) {
                case 'data-publication':
                    $results[] = DataPublication::fromCkanArray($result);
                    break;
                default:
                    $results[] = (object) $result;
                    break;
            }
        }

        return $results;
    }

    /**
     * returns array containing facet information
     */
    public function getFacets(): array
    {
        return $this->responseBody['result']['search_facets'];
    }
}
