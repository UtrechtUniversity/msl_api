<?php
namespace App\CkanClient\Response;

class BaseResponse
{

    /**
     * @var int HTTP response code
     */
    public $responseCode;

    /**
     * @var bool success status reported by CKAN
     */
    public $ckanSuccess;

    /**
     * @var array full response from CKAN
     */
    public $responseBody;

    
    public function __construct($body, $responseCode)
    {
        $this->responseCode = $responseCode;

        if($responseCode == '200') {
            $this->responseBody = $body;
            $this->ckanSuccess = (bool)$body['success'];
        } elseif($responseCode == '404') {
            $this->responseBody = $body;
            $this->ckanSuccess = (bool)$body['success'];
        } elseif($responseCode == '409') {
            $this->responseBody = $body;
            $this->ckanSuccess = (bool)$body['success'];
        }
    }

    /**
     * Returns success status of request depending on responscode and ckan response
     * 
     * @return bool
     */
    public function isSuccess(): bool
    {
        if($this->responseCode == 200) {
            if($this->ckanSuccess) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns result element from response body
     * 
     * @return array
     */
    public function getResult(): array
    {
        return $this->responseBody['result'];
    }

    /**
     * response body contains error element
     * 
     * @return bool
     */
    public function hasError(): bool
    {
        return isset($this->responseBody['error']);
    }

    /**
     * error type returned by ckan
     * 
     * @return string
     */
    public function getErrorType(): string
    {
        if($this->hasError()) {
            if(isset($this->responseBody['error']['__type'])) {
                return $this->responseBody['error']['__type'];
            }
        }
    }

    /**
     * error message returned by ckan
     * 
     * @return string
     */
    public function getErrorMessage(): string
    {
        if($this->hasError()) {
            if(isset($this->responseBody['error']['message'])) {
                return $this->responseBody['error']['message'];
            }
        }
    }


}