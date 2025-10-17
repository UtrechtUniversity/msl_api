<?php

namespace App\Response\V1;

class ResultBlock
{
    public $count = 0;

    public $resultCount = 0;

    public $results = [];

    public function setByCkanResponse($response, $context)
    {
        $this->count = $response->getTotalResultsCount();

        $results = $response->getResults();
        $this->resultCount = count($results);

        foreach ($results as $result) {
            if ($context == 'facilities') {
                $this->results[] = new FacilitiesResult($result);
            } else {
                $this->results[] = new BaseResult($result, $context);
            }
        }

    }

    public function getAsArray()
    {
        return (array) $this;
    }
}
