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
    public function getResults(bool $castToObjects = false): array|object
    {
        $resultsFromResponse = $this->responseBody['result']['results'];

        if (! $castToObjects) {
            return $resultsFromResponse;
        }

        $resultsToReturn = [];

        foreach ($resultsFromResponse as $result) {
            switch ($result['type']) {
                case 'data-publication':
                    $resultsToReturn[] = DataPublication::fromCkanArray($result);
                    break;
                default:
                    $resultsToReturn[] = (object) $result;
                    break;
            }
        }
        return  $resultsToReturn;
    }

    /**
     * returns array containing facet information
     */
    public function getFacets(): array
    {
        return $this->responseBody['result']['search_facets'];
    }
}
