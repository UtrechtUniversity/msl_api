<?php
namespace App\CkanClient\Request;

use App\CkanClient\Response\BaseResponse;

class DatasetPurgeRequest implements RequestInterface
{
    /**
     * @var string endpoint in CKAN used for this request;
     */
    private $endpoint = 'action/dataset_purge';

    /**
     * @var string method of request
     */
    private $method = 'POST';

    /**
     * @var string class for creating result object
     */
    private $responseClass = BaseResponse::class;

    /**
     * @var string id of dataset to be removed
     */
    public $id;

    
    public function getPayloadAsArray(): array
    {
        return [
            'form_params' => [
                'id' => $this->id
            ]
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