<?php

namespace App\Http\Controllers;

use App\Ckan\Request\PackageSearch;
use App\Response\ErrorResponse;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Response\MainResponse;
use App\Models\TnaMockup;
use App\Models\Keyword;
use App\Http\Resources\KeywordResource;

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

        // ------------------------------------------------------------------------------------------------------------------------------------
// "http://dev.epos-msl.uu.nl/webservice/api/rock_physics?rows=8&start=0&authorName=Montanaro&labName=Munich&title=Variability"

        $errorResponse = new ErrorResponse();

        // $endpoint = 'https://epos-msl.ckan.test/api/3/action/package_search';

        // echo $endpoint;

//        $errorResponse->message = $endpoint;
//        return $errorResponse->getAsLaravelResponse();

        //     ----------------------------------------------------------------------------------------------------------------------------------

        $client = new \GuzzleHttp\Client([ 'verify' => false ]);

        $searchRequest = new PackageSearch();

        $searchRequest->setbyRequest($request, $this->buildQuery($request, $this->queryMappings));

        if($request->boolean('hasDownloads', true)) {
            $searchRequest->filterQuery = 'type:"data-publication" AND msl_subdomain:"rock and melt physics" AND msl_download_link:*';
        } else {
            $searchRequest->filterQuery = 'type:"data-publication" AND msl_subdomain:"rock and melt physics"';
        }

        $queryArray = $searchRequest->getAsQueryArray();

        try {
            $response = $client->request('GET', $endpoint, $searchRequest->getAsQueryArray());
        } // catch (GuzzleException $e) {
        catch (\Exception $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $responseBodyAsString = $response->getBody()->getContents();
            }
            else {
                $responseBodyAsString = "No response bla";
            }
            $errorResponse = new ErrorResponse();
            $errorResponse->message = $responseBodyAsString;
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

        if($request->boolean('hasDownloads', true)) {
            $searchRequest->filterQuery = 'type:"data-publication" AND msl_subdomain:"analogue modelling of geologic processes" AND msl_download_link:*';
        } else {
            $searchRequest->filterQuery = 'type:"data-publication" AND msl_subdomain:"analogue modelling of geologic processes"';
        }

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

        if($request->boolean('hasDownloads', true)) {
            $searchRequest->filterQuery = 'type:"data-publication" AND msl_subdomain:"paleomagnetism" AND msl_download_link:*';
        } else {
            $searchRequest->filterQuery = 'type:"data-publication" AND msl_subdomain:"paleomagnetism"';
        }

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

        if($request->boolean('hasDownloads', true)) {
            $searchRequest->filterQuery = 'type:"data-publication" AND msl_subdomain:"microscopy and tomography" AND msl_download_link:*';
        } else {
            $searchRequest->filterQuery = 'type:"data-publication" AND msl_subdomain:"microscopy and tomography"';
        }

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

        if($request->boolean('hasDownloads', true)) {
            $searchRequest->filterQuery = 'type:"data-publication" AND msl_subdomain:"geochemistry" AND msl_download_link:*';
        } else {
            $searchRequest->filterQuery = 'type:"data-publication" AND msl_subdomain:"geochemistry"';
        }

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

        $endpoint = 'https://epos-msl.uu.nl/api/3/' . $action;
        $client = new \GuzzleHttp\Client([ 'verify' => false ]);

        $searchRequest = new PackageSearch();

        $searchRequest->setbyRequest($request, $this->buildQuery($request, $this->queryMappingsAll));

        // yoda-repository
        // gfz-potsdam
        // csic
        // 4TU
        if($request->boolean('hasDownloads', true)) {
            $searchRequest->filterQuery = 'type:"data-publication" AND msl_download_link:* AND organization:csic';
        } else {
            $searchRequest->filterQuery = 'type:"data-publication" AND organization:csic';
        }

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

    public function tna() {
        $data = TnaMockup::all()->toArray();

        return response()->json([
            'success' => true,
            'message' => '',
            'result' => [
                'count' => count($data),
                'resultCount' => count($data),
                'results' => $data
            ]
        ], 200);
    }

    public function term(Request $request) {

        $validator = Validator::make(request()->all(), [
            'uri' => 'required'
        ]);

        if ($validator->fails()) {
            $errorResponse = new ErrorResponse();
            $errorResponse->message = $validator->errors();
            return $errorResponse->getAsLaravelResponse();
        }

        $keyword = Keyword::where('uri', $request->get('uri'))->first();

        if($keyword) {
            $resource = new KeywordResource($keyword);
            return $resource->toArray($request);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'term not found',
                'result' => [

                ]
            ], 200);
        }


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
