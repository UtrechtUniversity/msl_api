<?php

namespace App\Response;

class ResultBlock
{
    public $count = 0;

    public $resultCount = 0;

    public $results = [];


    public function setByCkanResponse($response, $context) {
        $this->count = $response->getTotalResultsCount();

        $results = $response->getResults();
        $this->resultCount = count($results);

        foreach ($results as $result) {               
            $this->results[] = new BaseResult($result, $context);                
        }        
    }

    public function getAsArray() {
        return (array)$this;
    }
    
        
}
