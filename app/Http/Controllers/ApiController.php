<?php

namespace App\Http\Controllers;

use App\Ckan\Request\PackageSearch;
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
    private $guzzleClient;

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

    public function __construct(\GuzzleHttp\Client $client)
    {
        $this->guzzleClient = $client;
    }
    
    
    
    public function rockPhysics(Request $request) {
        return $this->dataPublicationResponse($request, 'rockPhysics');
    }
    
    public function analogue(Request $request)
    {
        return $this->dataPublicationResponse($request, 'analogue');
    }
    
    public function paleo(Request $request)
    {
        return $this->dataPublicationResponse($request, 'paleo');
    }
    
    #microscopy and tomography
    public function microscopy(Request $request)
    {
        return $this->dataPublicationResponse($request, 'microscopy');
    }
    
    #geochemistry
    public function geochemistry(Request $request)
    {
        return $this->dataPublicationResponse($request, 'geochemistry');
    }
    
    #all
    public function all(Request $request)
    {
        return $this->dataPublicationResponse($request, 'all');
    }

    private function dataPublicationResponse(Request $request, $context)
    {
        $ckanClient = new Client($this->guzzleClient);

        $packageSearchRequest = new PackageSearchRequest();

        $packageSearchRequest->addFilterQuery("type", "data-publication");
        
        if($request->boolean('hasDownloads', true)) {
            $packageSearchRequest->addFilterQuery('msl_download_link', '*', false);
        }

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
        }        

        if($context == 'all') {
            $packageSearchRequest->query = $this->buildQuery($request, $this->queryMappingsAll);    
        } else {
            $packageSearchRequest->query = $this->buildQuery($request, $this->queryMappings);    
        }
        
        try {
            $response = $ckanClient->get($packageSearchRequest);
        } catch (\Exception $e) {
            $errorResponse = new ErrorResponse();
            $errorResponse->message = 'Malformed request to CKAN.';
            return $errorResponse->getAsLaravelResponse();
        }

        if(!$response->isSuccess()) {
            $errorResponse = new ErrorResponse();
            $errorResponse->message = 'Error received from CKAN api.';
            return $errorResponse->getAsLaravelResponse();
        }

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
