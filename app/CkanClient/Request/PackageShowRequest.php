<?php

namespace App\CkanClient\Request;

use App\CkanClient\Response\BaseResponse;

class PackageShowRequest implements RequestInterface
{
    /**
     * @var string endpoint in CKAN used for this request;
     */
    private string $endpoint = 'action/package_show';

    /**
     * @var string method of request
     */
    private string $method = 'GET';

    /**
     * @var string class for creating result object
     */
    private string $responseClass = BaseResponse::class;

    /**
     * @var string ckan package id
     */
    public string $id;

    public function getPayloadAsArray(): array
    {
        return [
            'query' => [
                'id' => $this->id,
            ],
        ];
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
