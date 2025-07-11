<?php

namespace App\Http\Controllers;

use App\CkanClient\Client;
use App\CkanClient\Request\PackageSearchRequest;
use App\Response\ErrorResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Response\MainResponse;
use App\Models\TnaMockup;
use App\Models\Keyword;
use App\Http\Resources\KeywordResource;

class ApiController extends Controller
{
    /**
     * @var \GuzzleHttp\Client Guzzle http client instance
     */
    private $guzzleClient;

    /**
     * @var array mappings from subdomain endpoint search parameters to ckan fields
     */
    private $queryMappings = [
        'query' => 'text',
        'tags' => 'tags',
        'title' => 'title',
        'authorName' => 'msl_creator_name_text',
        'labName' => 'msl_lab_name_text',
    ];
    
    /**
     * @var array mappings from all endpoint search parameters to ckan fields
     */
    private $queryMappingsAll = [
        'query' => 'text',
        'tags' => 'tags',
        'title' => 'title',
        'authorName' => 'msl_creator_name_text',
        'labName' => 'msl_lab_name_text',
        'subDomain' => 'msl_subdomain'
    ];

    /**
     * Constructs a new ApiController
     * 
     * @param \GuzzleHttp\Client $client
     */
    public function __construct(\GuzzleHttp\Client $client)
    {
        $this->guzzleClient = $client;
    }
    
    /**
     * Rock physics API endpoint
     * 
     * @param Request $request
     * @return response
     */
    public function rockPhysics(Request $request) {
        return $this->dataPublicationResponse($request, 'rockPhysics');
    }
    
    /**
     * Analogue modelling API endpoint
     * 
     * @param Request $request
     * @return response
     */
    public function analogue(Request $request)
    {
        return $this->dataPublicationResponse($request, 'analogue');
    }
    
    /**
     * Paleomagnetism API endpoint
     * 
     * @param Request $request
     * @return response
     */
    public function paleo(Request $request)
    {
        return $this->dataPublicationResponse($request, 'paleo');
    }
    
    /**
     * Microscopy and tomography API endpoint
     * 
     * @param Request $request
     * @return response
     */
    public function microscopy(Request $request)
    {
        return $this->dataPublicationResponse($request, 'microscopy');
    }
    
    /**
     * Geochemistry API endpoint
     * 
     * @param Request $request
     * @return response
     */
    public function geochemistry(Request $request)
    {
        return $this->dataPublicationResponse($request, 'geochemistry');
    }

        /**
     * Geo Energy Test Beds API endpoint
     * 
     * @param Request $request
     * @return response
     */
    public function geoenergy(Request $request)
    {
        return $this->dataPublicationResponse($request, 'geoenergy');
    }
    
    /**
     * All subdomains API endpoint
     * 
     * @param Request $request
     * @return response
     */
    public function all(Request $request)
    {
        return $this->dataPublicationResponse($request, 'all');
    }

    /**
     * Creates a API response based upon search parameters provided in request
     * Context is used to provide subdomain specific processing
     * 
     * @param Request $request
     * @param string $context
     * @return response
     */
    private function dataPublicationResponse(Request $request, $context)
    {
        // Create CKAN client
        $ckanClient = new Client($this->guzzleClient);

        // Create packagesearch request
        $packageSearchRequest = new PackageSearchRequest();

        // Filter on data-publications
        $packageSearchRequest->addFilterQuery("type", "data-publication");
        
        // Filter for data-publications with files depending on request
        if($request->boolean('hasDownloads', true)) {
            $packageSearchRequest->addFilterQuery('msl_download_link', '*', false);
        }

        // Add subdomain filtering if required
        switch($context) {
            case 'rockPhysics':
                $packageSearchRequest->addFilterQuery("msl_subdomain", "rock and melt physics");
                break;

            case 'analogue':
                $packageSearchRequest->addFilterQuery("msl_subdomain", "analogue modelling of geologic processes");
                break;

            case 'paleo':
                $packageSearchRequest->addFilterQuery("msl_subdomain", "paleomagnetism");
                break;

            case 'microscopy':
                $packageSearchRequest->addFilterQuery("msl_subdomain", "microscopy and tomography");
                break;

            case 'geochemistry':
                $packageSearchRequest->addFilterQuery("msl_subdomain", "geochemistry");
                break;

            case 'geoenergy':
                $packageSearchRequest->addFilterQuery("msl_subdomain", "geo-energy test beds");
                break;
        }        

        // Set rows
        $paramRows = (int)$request->get('rows');
        if($paramRows > 0) {
            $packageSearchRequest->rows = $paramRows;
        }

        // Set start
        $paramStart = (int)$request->get('start');
        if($paramStart > 0) {
            $packageSearchRequest->start = $paramStart;
        }

        // Process search parameters
        if($context == 'all') {
            $packageSearchRequest->query = $this->buildQuery($request, $this->queryMappingsAll);    
        } else {
            $packageSearchRequest->query = $this->buildQuery($request, $this->queryMappings);    
        }
        
        // Attempt to retrieve data from CKAN
        try {
            $response = $ckanClient->get($packageSearchRequest);
        } catch (\Exception $e) {
            $errorResponse = new ErrorResponse();
            $errorResponse->message = 'Malformed request to CKAN.';
            return $errorResponse->getAsLaravelResponse();
        }

        // Check if CKAN was succesful
        if(!$response->isSuccess()) {
            $errorResponse = new ErrorResponse();
            $errorResponse->message = 'Error received from CKAN api.';
            return $errorResponse->getAsLaravelResponse();
        }

        // Create response object
        $ApiResponse = new MainResponse();
        $ApiResponse->setByCkanResponse($response, $context);
        
        //return response object
        return $ApiResponse->getAsLaravelResponse();
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
    
    /**
     * Converts search parameters to solr query using field mappings
     * 
     * @param Request $request
     * @param array $querymappings
     * @return string
     */
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
