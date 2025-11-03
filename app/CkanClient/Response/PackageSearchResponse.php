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
    public function getResults(bool $castToObjects = false): array
    {

        $result = $this->responseBody['result'];
        if (! $castToObjects) {
            return $result;
        }

        if ($result['type']) {
            switch ($result['type']) {
                case 'data-publication':
                    return DataPublication::fromCkanArray($result);
                default:
                    return (object) $result;
            }
        }

        return (object) $result;
    }

    /**
     * returns array containing facet information
     */
    public function getFacets(): array
    {
        return $this->responseBody['result']['search_facets'];
    }
}
