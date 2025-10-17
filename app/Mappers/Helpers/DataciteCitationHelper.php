<?php

namespace App\Mappers\Helpers;

use GuzzleHttp\Client;

class DataciteCitationHelper
{
    /**
     * @var GuzzleClient Guzzle HTTP client instance
     */
    protected $client;

    /**
     * Contructs a new DataciteCitationHelper
     */
    public function __construct($client = new Client)
    {
        $this->client = $client;
    }

    /**
     * Retrieve a citationstring in apa style using doi
     *
     * @param  string  $doi
     */
    public function getCitationString($doi): string
    {
        try {
            $response = $this->client->request(
                'GET',
                "https://doi.org/doi:$doi",
                [
                    'headers' => [
                        'Accept' => 'text/x-bibliography; style=apa',
                    ],

                ]
            );
        } catch (\Exception $e) {
            return '';
        }

        if (isset($response)) {
            return (string) $response->getBody();
        }

        return '';
    }
}
