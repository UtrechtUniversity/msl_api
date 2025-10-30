<?php

namespace App\CkanClient\Request;

use App\CkanClient\Response\BaseResponse;

class OrganizationUpdateRequest implements RequestInterface
{
    /**
     * @var string endpoint in CKAN used for this request;
     */
    private string $endpoint = 'action/organization_patch';

    /**
     * @var string method of request
     */
    private string $method = 'POST';

    /**
     * @var string class for creating result object
     */
    private string $responseClass = BaseResponse::class;

    /**
     * @var array data to store
     */
    public array $payload;

    public function getPayloadAsArray(): array
    {
        return [
            'json' => $this->payload,
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
