<?php

namespace App\Datacite;

use Illuminate\Support\Uri;
use stdClass;

class Datacite
{
    protected $client;

    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client;
    }

    public function doisRequest($doi, bool $retryOnFailure = false, bool $jsonDecode = true): stdClass
    {
        $doi = urlencode($doi);
        $result = new stdClass;

        try {
            $response = $this->client->request('GET', "https://api.datacite.org/dois/$doi", [
                'headers' => [
                    'Accept' => 'application/vnd.api+json',
                ],
            ]);
        } catch (\Exception $e) {
            if ($retryOnFailure) {
                sleep(1);
                $this->doisRequest($doi);
            }

            $result->response_code = $e->getCode();
            $result->response_body = [];

            return $result;
        }

        $result->response_code = $response->getStatusCode();
        $result->response_body = [];

        if ($result->response_code == 200) {
            if ($jsonDecode) {
                $result->response_body = json_decode($response->getBody(), true);
            } else {
                $result->response_body = $response->getBody();
            }
        }

        return $result;
    }

    public function cursorSearchRequest(string $query, string $prefix, string $fields, int $pageSize = 1000, bool $retryOnFailure = false, bool $jsonDecode = true): stdClass
    {
        $uri = Uri::of('https://api.datacite.org/dois');
        $uri = $uri->withQuery([
            'query' => $query,
            'prefix' => $prefix,
            'fields[dois]' => $fields,
            'page[cursor]' => 1,
            'page[size]' => $pageSize,
        ]);

        $result = new stdClass;

        try {
            $response = $this->client->request('GET', $uri->value(), [
                'headers' => [
                    'Accept' => 'application/vnd.api+json',
                ],
            ]);
        } catch (\Exception $e) {
            if ($retryOnFailure) {
                sleep(1);
                $this->cursorSearchRequest($query, $prefix, $fields);
            }

            $result->response_code = $e->getCode();
            $result->response_body = [];

            return $result;
        }

        $result->response_code = $response->getStatusCode();
        $result->response_body = [];

        if ($result->response_code == 200) {
            if ($jsonDecode) {
                $result->response_body = json_decode($response->getBody(), true);
            } else {
                $result->response_body = $response->getBody();
            }
        }

        return $result;
    }

    public function cursorPageRequest(string $uri, bool $retryOnFailure = false, bool $jsonDecode = true): stdClass
    {
        $result = new stdClass;

        try {
            $response = $this->client->request('GET', $uri, [
                'headers' => [
                    'Accept' => 'application/vnd.api+json',
                ],
            ]);
        } catch (\Exception $e) {
            if ($retryOnFailure) {
                sleep(1);
                $this->cursorPageRequest($uri, false, true);
            }

            $result->response_code = $e->getCode();
            $result->response_body = [];

            return $result;
        }

        $result->response_code = $response->getStatusCode();
        $result->response_body = [];

        if ($result->response_code == 200) {
            if ($jsonDecode) {
                $result->response_body = json_decode($response->getBody(), true);
            } else {
                $result->response_body = $response->getBody();
            }
        }

        return $result;
    }
}
