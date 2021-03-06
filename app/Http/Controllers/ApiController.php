<?php

namespace App\Http\Controllers;

use App\Ckan\Request\PackageSearch;
use App\Response\ErrorResponse;
use Illuminate\Http\Request;
use App\Response\MainResponse;

class ApiController extends Controller
{
    private $queryMappings = [
        'query' => 'text',
        'tags' => 'tags',
        'title' => 'title',
        'authorName' => 'msl_author_name_text',
        'labName' => 'msl_lab_name_text',
    ];
    
    private $queryMappingsAll = [
        'query' => 'text',
        'tags' => 'tags',
        'title' => 'title',
        'authorName' => 'msl_author_name_text',
        'labName' => 'msl_lab_name_text',
        'subDomain' => 'msl_subdomain'
    ];
    
    
    
    public function rockPhysics(Request $request) {
        $action = 'action/package_search';
        $endpoint = config('ckan.ckan_api_url') . $action;

        $client = new \GuzzleHttp\Client();

        $searchRequest = new PackageSearch();
                
        $searchRequest->setbyRequest($request, $this->buildQuery($request, $this->queryMappings));
        $searchRequest->filterQuery = 'msl_subdomain:"rock and melt physics"';

        try {
            $response = $client->request('GET', $endpoint, $searchRequest->getAsQueryArray());
        } catch (\Exception $e) {
            $errorResponse = new ErrorResponse();
            $errorResponse->message = 'Malformed request to CKAN.';
            return $errorResponse->getAsLaravelResponse();
        }

        //Check if response is ok
        if($response->getStatusCode() !== 200) {
            $errorResponse = new ErrorResponse();
            $errorResponse->message = 'Error received from CKAN api.';
            return $errorResponse->getAsLaravelResponse();
        }

        //build api response object
        $rockPhysicsResponse = new MainResponse();
        $rockPhysicsResponse->setByCkanResponse($response, 'rockPhysics');

        //return response object
        return $rockPhysicsResponse->getAsLaravelResponse();
    }
    
    public function analogue(Request $request)
    {
        $action = 'action/package_search';
        $endpoint = config('ckan.ckan_api_url') . $action;
        
        $client = new \GuzzleHttp\Client();
        
        $searchRequest = new PackageSearch();               
        
        $searchRequest->setbyRequest($request, $this->buildQuery($request, $this->queryMappings));
        $searchRequest->filterQuery = 'msl_subdomain:"analogue modelling of geologic processes"';
        
        
        try {
            $response = $client->request('GET', $endpoint, $searchRequest->getAsQueryArray());
        } catch (\Exception $e) {
            $errorResponse = new ErrorResponse();
            $errorResponse->message = 'Malformed request to CKAN.';
            return $errorResponse->getAsLaravelResponse();
        }
        
        //Check if response is ok
        if($response->getStatusCode() !== 200) {
            $errorResponse = new ErrorResponse();
            $errorResponse->message = 'Error received from CKAN api.';
            return $errorResponse->getAsLaravelResponse();
        }
        
        //build api response object
        $analogueResponse = new MainResponse();
        $analogueResponse->setByCkanResponse($response, 'analogue');
        
        //return response object
        return $analogueResponse->getAsLaravelResponse();
    }
    
    public function paleo(Request $request)
    {
        $action = 'action/package_search';
        $endpoint = config('ckan.ckan_api_url') . $action;
        
        $client = new \GuzzleHttp\Client();
        
        $searchRequest = new PackageSearch();
        
        $searchRequest->setbyRequest($request, $this->buildQuery($request, $this->queryMappings));
        $searchRequest->filterQuery = 'msl_subdomain:"paleomagnetism"';
        
        
        try {
            $response = $client->request('GET', $endpoint, $searchRequest->getAsQueryArray());
        } catch (\Exception $e) {
            $errorResponse = new ErrorResponse();
            $errorResponse->message = 'Malformed request to CKAN.';
            return $errorResponse->getAsLaravelResponse();
        }
        
        //Check if response is ok
        if($response->getStatusCode() !== 200) {
            $errorResponse = new ErrorResponse();
            $errorResponse->message = 'Error received from CKAN api.';
            return $errorResponse->getAsLaravelResponse();
        }
        
        //build api response object
        $paleoResponse = new MainResponse();
        $paleoResponse->setByCkanResponse($response, 'paleo');
        
        //return response object
        return $paleoResponse->getAsLaravelResponse();
    }
    
    #microscopy and tomography
    public function microscopy(Request $request)
    {
        $action = 'action/package_search';
        $endpoint = config('ckan.ckan_api_url') . $action;
        
        $client = new \GuzzleHttp\Client();
        
        $searchRequest = new PackageSearch();
        
        $searchRequest->setbyRequest($request, $this->buildQuery($request, $this->queryMappings));
        $searchRequest->filterQuery = 'msl_subdomain:"microscopy and tomography"';
        
        
        try {
            $response = $client->request('GET', $endpoint, $searchRequest->getAsQueryArray());
        } catch (\Exception $e) {
            $errorResponse = new ErrorResponse();
            $errorResponse->message = 'Malformed request to CKAN.';
            return $errorResponse->getAsLaravelResponse();
        }
        
        //Check if response is ok
        if($response->getStatusCode() !== 200) {
            $errorResponse = new ErrorResponse();
            $errorResponse->message = 'Error received from CKAN api.';
            return $errorResponse->getAsLaravelResponse();
        }
        
        //build api response object
        $paleoResponse = new MainResponse();
        $paleoResponse->setByCkanResponse($response, 'microscopy');
        
        //return response object
        return $paleoResponse->getAsLaravelResponse();
    }
    
    #geochemistry
    public function geochemistry(Request $request)
    {
        $action = 'action/package_search';
        $endpoint = config('ckan.ckan_api_url') . $action;
        
        $client = new \GuzzleHttp\Client();
        
        $searchRequest = new PackageSearch();
        
        $searchRequest->setbyRequest($request, $this->buildQuery($request, $this->queryMappings));
        $searchRequest->filterQuery = 'msl_subdomain:"geochemistry"';
        
        
        try {
            $response = $client->request('GET', $endpoint, $searchRequest->getAsQueryArray());
        } catch (\Exception $e) {
            $errorResponse = new ErrorResponse();
            $errorResponse->message = 'Malformed request to CKAN.';
            return $errorResponse->getAsLaravelResponse();
        }
        
        //Check if response is ok
        if($response->getStatusCode() !== 200) {
            $errorResponse = new ErrorResponse();
            $errorResponse->message = 'Error received from CKAN api.';
            return $errorResponse->getAsLaravelResponse();
        }
        
        //build api response object
        $paleoResponse = new MainResponse();
        $paleoResponse->setByCkanResponse($response, 'geochemistry');
        
        //return response object
        return $paleoResponse->getAsLaravelResponse();
    }
    
    #all
    public function all(Request $request)
    {
        $action = 'action/package_search';
        $endpoint = config('ckan.ckan_api_url') . $action;
        
        $client = new \GuzzleHttp\Client();
        
        $searchRequest = new PackageSearch();
        
        $searchRequest->setbyRequest($request, $this->buildQuery($request, $this->queryMappingsAll));  
        $searchRequest->filterQuery = 'type:"data-publication"';
        
        try {
            $response = $client->request('GET', $endpoint, $searchRequest->getAsQueryArray());
        } catch (\Exception $e) {
            $errorResponse = new ErrorResponse();
            $errorResponse->message = 'Malformed request to CKAN.';
            return $errorResponse->getAsLaravelResponse();
        }
        
        //Check if response is ok
        if($response->getStatusCode() !== 200) {
            $errorResponse = new ErrorResponse();
            $errorResponse->message = 'Error received from CKAN api.';
            return $errorResponse->getAsLaravelResponse();
        }
        
        //build api response object
        $paleoResponse = new MainResponse();
        $paleoResponse->setByCkanResponse($response, 'all');
        
        //return response object
        return $paleoResponse->getAsLaravelResponse();
    }
    
    private function buildQuery(Request $request, $queryMappings)
    {
        $queryParts = [];
        
        foreach ($queryMappings as $key => $value)
        {
            if($request->filled($key)) {
                if($key == 'subDomain') {
                    $queryParts[] = $value . ':"' . $request->get($key). '"';
                } else {
                    $queryParts[] = $value . ':' . $request->get($key);
                }
            }
        }
                
        if(count($queryParts) > 0) {
            return implode(' AND ', $queryParts);
        }                
        
        return '';
    }
}
