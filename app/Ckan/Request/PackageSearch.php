<?php

namespace App\Ckan\Request;

class PackageSearch
{
    public $endPoint;
    
    public $method = 'GET';

    public $query = '';

    public $filterQuery = '';

    public $rows;

    public $start;
    
    public function __construct() {
        $this->endPoint = config('ckan.ckan_api_url') . 'action/package_search';
    }

    public function getAsQueryArray() {
        return [
            'query' => [
                'q' => $this->query,
                'fq' => $this->filterQuery,
                'rows' => $this->rows,
                'start' => $this->start,
            ],
            'http_errors' => false
        ];
    }

    public function setbyRequest($request, $processedQuery = '') {
        $this->rows = (int)$request->get('rows');
        if($this->rows < 1) {
            $this->rows = 10;
        }

        $this->start = (int)$request->get('start');
        if($this->start < 0) {
            $this->start = 0;
        }

        if($processedQuery !== '') {
            $this->query = $processedQuery;
        } else {
            $this->query = $request->get('query');        
        }
        
        if(!$this->query) {
            $this->query = "";
        }
    }
}
