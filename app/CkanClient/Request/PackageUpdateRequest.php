<?php
namespace App\CkanClient\Request;

use App\CkanClient\Response\BaseResponse;

class PackageUpdateRequest implements RequestInterface
{
    /**
     * @var string endpoint in CKAN used for this request;
     */
    private $endpoint = 'action/package_update';

    /**
     * @var string method of request
     */
    private $method = 'POST';

    /**
     * @var string class for creating result object
     */
    private $responseClass = BaseResponse::class;

    /**
     * @var array data to update
     */
    public $payload;
    
    public function getPayloadAsArray(): array
    {
        return [
            'json' => $this->payload
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