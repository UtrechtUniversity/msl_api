<?php
namespace App\CkanClient\Request;

use App\CkanClient\Response\BaseResponse;
use App\CkanClient\Response\PackageSearchResponse;
use App\CkanClient\SolrUtils;

class PackageSearchRequest implements RequestInterface
{

    /**
     * @var string endpoint in CKAN used for this request;
     */
    private $endpoint = 'action/package_search';

    /**
     * @var string method of request
     */
    private $method = 'GET';

    /**
     * @var string class for creating result object
     */
    private $responseClass = PackageSearchResponse::class;

    /**
     * @var string query string used in solr
     */
    public $query;

    /**
     * @var array filter query parts used to contruct the solr filter query
     */
    public $filterQueries = [];

    /**
     * @var int number of rows to request from solr
     */
    public $rows;

    /**
     * @var int number to start results from
     */
    public $start;

    /**
     * @var array facets used to contruct the facets part of the solr query
     */
    public $facetFields = [];

    /**
     * @var string sort results
     */
    public $sortField = '';

    /**
     * @var int maximum number of facet values returned by CKAN. Use a negative number for unlimited
     */
    private $facetLimit = -1;



    public function getPayloadAsArray(): array
    {
        return [
            'query' => [
                'q' => $this->query,
                'fq' => $this->getFilterQueryQuery(),
                'rows' => $this->rows,
                'start' => $this->start,
                'facet.field' => $this->getFacetFieldQuery(),
                'facet.limit' => $this->facetLimit,
                'sort' => $this->sortField
            ]
        ];
    }

    public function addFilterQuery($fieldName, $value)
    {
        $this->filterQueries[$fieldName][] = '"' .  SolrUtils::escape($value) . '"';
    }

    private function getFilterQueryQuery()
    {
        if(count($this->filterQueries) > 0) {
            $parts = [];
            foreach($this->filterQueries as $key => $values) {
                foreach($values as $value) {
                    $parts[] = $key . ':' . $value;
                }
            }

            $return = implode(' AND ', $parts);

            return $return;
        }

        return '';
    }

    public function addFacetField($facetField)
    {
        $this->facetFields[] = $facetField;
    }

    public function loadFacetsFromConfig($type)
    {
        if($type == "data-publications") {
            $facets = config('ckan.facets.data-publications');
            foreach($facets as $key => $value) {
                $this->addFacetField($key);
            }
        } elseif($type == 'laboratories') {
            $facets = config('ckan.facets.laboratories');
            foreach($facets as $key => $value) {
                $this->addFacetField($key);
            }
        } elseif($type == 'equipment') {
            $facets = config('ckan.facets.equipment');
            foreach($facets as $key => $value) {
                $this->addFacetField($key);
            }
        }
    }

    private function getFacetFieldQuery()
    {
        if(count($this->facetFields) > 0) {
            $return = '[';
            $parts = [];
            foreach($this->facetFields as $facetField) {
                $parts[] = '"' . $facetField . '"';
            }
            $return .= implode(',', $parts);
            $return .= ']';

            return $return;
        }

        return '[]';
    }

    public function getResponseClass(): string
    {
        return $this->responseClass;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getEndpoint(): string
    {
        return $this->endpoint;
    }


}