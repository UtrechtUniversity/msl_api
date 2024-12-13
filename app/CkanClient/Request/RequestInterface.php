<?php
namespace App\CkanClient\Request;

use App\CkanClient\Response\BaseResponse;

interface RequestInterface
{

    /**
     * Request specific part of the ckan request
     * 
     * @return array parts to add to the ckan request send using Guzzle client
     */
    public function getPayloadAsArray(): array;

    /**
     * Response class used to convert the ckan response to
     * 
     * @return string response class used with request
     */
    public function getResponseClass(): string;

    /**
     * HTTP method used for ckan request
     * 
     * @return string HTTP method
     */
    public function getMethod(): string;

    /**
     * endpoint associated with request
     * 
     * @return string ckan api endpoint
     */
    public function getEndpoint(): string;

}