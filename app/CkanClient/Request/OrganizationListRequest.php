<?php

namespace App\CkanClient\Request;

use App\CkanClient\Response\BaseResponse;

class OrganizationListRequest implements RequestInterface
{
    /**
     * @var string endpoint in CKAN used for this request;
     */
    private string $endpoint = 'action/organization_list';

    /**
     * @var string method of request
     */
    private string $method = 'GET';

    /**
     * @var string class for creating result object
     */
    private string $responseClass = BaseResponse::class;

    /**
     * @var bool all fields for organizations should be requested
     */
    private bool $allFields = true;

    /**
     * @var bool fields not within the default ckan schema should be returned
     */
    private bool $includeExtras = true;

    /**
     * @var string sort results
     */
    public string $sortField = '';

    public function getPayloadAsArray(): array
    {
        return [
            'query' => [
                'all_fields' => $this->allFields,
                'include_extras' => $this->includeExtras,
                'sort' => $this->sortField,
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
