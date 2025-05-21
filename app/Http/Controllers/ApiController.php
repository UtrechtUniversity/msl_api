<?php

namespace App\Http\Controllers;

use App\CkanClient\Client;
use App\CkanClient\Request\PackageSearchRequest;
use App\Http\Resources\KeywordResource;
use App\Models\Keyword;
use App\Models\TnaMockup;
use App\Response\ErrorResponse;
use App\Response\MainResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
        'authorName' => 'msl_author_name_text',
        'labName' => 'msl_lab_name_text',
    ];

    /**
     * @var array mappings from all endpoint search parameters to ckan fields
     */
    private $queryMappingsAll = [
        'query' => 'text',
        'tags' => 'tags',
        'title' => 'title',
        'authorName' => 'msl_author_name_text',
        'labName' => 'msl_lab_name_text',
        'subDomain' => 'msl_subdomain',
    ];

    /**
     * @var array mappings from all endpoint search parameters to ckan fields
     */
    private $queryMappingsFacilities = [
        'query' => 'text',
        'tags' => 'tags',
        'title' => 'title',
        'authorName' => 'msl_author_name_text',
        'facilityQuery' => 'title',
        'subDomain' => 'msl_subdomain',
        'equipmentQuery' => 'msl_laboratory_equipment_text',
    ];

    /**
     * Constructs a new ApiController
     */
    public function __construct(\GuzzleHttp\Client $client)
    {
        $this->guzzleClient = $client;
    }

    /**
     * Rock physics API endpoint
     *
     * @return response
     */
    public function rockPhysics(Request $request)
    {
        return $this->dataPublicationResponse($request, 'rockPhysics');
    }

    /**
     * Analogue modelling API endpoint
     *
     * @return response
     */
    public function analogue(Request $request)
    {
        return $this->dataPublicationResponse($request, 'analogue');
    }

    /**
     * Paleomagnetism API endpoint
     *
     * @return response
     */
    public function paleo(Request $request)
    {
        return $this->dataPublicationResponse($request, 'paleo');
    }

    /**
     * Microscopy and tomography API endpoint
     *
     * @return response
     */
    public function microscopy(Request $request)
    {
        return $this->dataPublicationResponse($request, 'microscopy');
    }

    /**
     * Geochemistry API endpoint
     *
     * @return response
     */
    public function geochemistry(Request $request)
    {
        return $this->dataPublicationResponse($request, 'geochemistry');
    }

    /**
     * Geo Energy Test Beds API endpoint
     *
     * @return response
     */
    public function geoenergy(Request $request)
    {
        return $this->dataPublicationResponse($request, 'geoenergy');
    }

    /**
     * All subdomains API endpoint
     *
     * @return response
     */
    public function all(Request $request)
    {
        return $this->dataPublicationResponse($request, 'all');
    }

    /**
     * facilities API endpoint
     *
     * @return response
     */
    public function facilities(Request $request)
    {
        return $this->facilitiesResponse($request);
    }

    /**
     * Creates a API response based upon search parameters provided in request
     * Context is used to provide subdomain specific processing
     *
     * @param  string  $context
     * @return response
     */
    private function dataPublicationResponse(Request $request, $context)
    {
        // Create CKAN client
        $ckanClient = new Client($this->guzzleClient);

        // Create packagesearch request
        $packageSearchRequest = new PackageSearchRequest;

        // Filter on data-publications
        $packageSearchRequest->addFilterQuery('type', 'data-publication');

        // Filter for data-publications with files depending on request
        if ($request->boolean('hasDownloads', true)) {
            $packageSearchRequest->addFilterQuery('msl_download_link', '*', false);
        }

        // Add subdomain filtering if required
        switch ($context) {
            case 'rockPhysics':
                $packageSearchRequest->addFilterQuery('msl_subdomain', 'rock and melt physics');
                break;

            case 'analogue':
                $packageSearchRequest->addFilterQuery('msl_subdomain', 'analogue modelling of geologic processes');
                break;

            case 'paleo':
                $packageSearchRequest->addFilterQuery('msl_subdomain', 'paleomagnetism');
                break;

            case 'microscopy':
                $packageSearchRequest->addFilterQuery('msl_subdomain', 'microscopy and tomography');
                break;

            case 'geochemistry':
                $packageSearchRequest->addFilterQuery('msl_subdomain', 'geochemistry');
                break;

            case 'geoenergy':
                $packageSearchRequest->addFilterQuery('msl_subdomain', 'geo-energy test beds');
                break;
        }

        // Set rows
        $paramRows = (int) $request->get('rows');
        if ($paramRows > 0) {
            $packageSearchRequest->rows = $paramRows;
        }

        // Set start
        $paramStart = (int) $request->get('start');
        if ($paramStart > 0) {
            $packageSearchRequest->start = $paramStart;
        }

        // Process search parameters
        if ($context == 'all') {
            $packageSearchRequest->query = $this->buildQuery($request, $this->queryMappingsAll);
        } else {
            $packageSearchRequest->query = $this->buildQuery($request, $this->queryMappings);
        }

        // Attempt to retrieve data from CKAN
        try {
            $response = $ckanClient->get($packageSearchRequest);
        } catch (\Exception $e) {
            $errorResponse = new ErrorResponse;
            $errorResponse->message = 'Malformed request to CKAN.';

            return $errorResponse->getAsLaravelResponse();
        }

        // Check if CKAN was succesful
        if (! $response->isSuccess()) {
            $errorResponse = new ErrorResponse;
            $errorResponse->message = 'Error received from CKAN api.';

            return $errorResponse->getAsLaravelResponse();
        }

        // Create response object
        $ApiResponse = new MainResponse;
        $ApiResponse->setByCkanResponse($response, $context);

        // return response object
        return $ApiResponse->getAsLaravelResponse();
    }

    public function tna()
    {
        $data = TnaMockup::all()->toArray();

        return response()->json([
            'success' => true,
            'message' => '',
            'result' => [
                'count' => count($data),
                'resultCount' => count($data),
                'results' => $data,
            ],
        ], 200);
    }

    public function term(Request $request)
    {

        $validator = Validator::make(request()->all(), [
            'uri' => 'required',
        ]);

        if ($validator->fails()) {
            $errorResponse = new ErrorResponse;
            $errorResponse->message = $validator->errors();

            return $errorResponse->getAsLaravelResponse();
        }

        $keyword = Keyword::where('uri', $request->get('uri'))->first();

        if ($keyword) {
            $resource = new KeywordResource($keyword);

            return $resource->toArray($request);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'term not found',
                'result' => [

                ],
            ], 200);
        }
    }

    /**
     * Converts search parameters to solr query using field mappings
     *
     * @param  array  $querymappings
     * @return string
     */
    private function buildQuery(Request $request, $queryMappings)
    {
        $queryParts = [];

        foreach ($queryMappings as $key => $value) {
            if ($request->filled($key)) {
                if ($key == 'subDomain') {
                    $queryParts[] = $value.':"'.$request->get($key).'"';
                } else {
                    $queryParts[] = $value.':'.$request->get($key);
                }
            }
        }

        if (count($queryParts) > 0) {
            return implode(' AND ', $queryParts);
        }

        return '';
    }

    /**
     * Creates a API response based upon search parameters provided in request
     * Context is used to provide facility specific processing
     * only facilities with location data are returned
     *
     * @param  string  $context
     * @return response
     */
    private function facilitiesResponse(Request $request)
    {
        $context = 'facilities';
        // Create CKAN client
        $ckanClient = new Client($this->guzzleClient);

        // Create packagesearch request
        $packageSearchRequest = new PackageSearchRequest;

        // Filter on facilities
        $packageSearchRequest->addFilterQuery('type', 'lab');

        // Filter for failities with coordinates
        $packageSearchRequest->addFilterQuery('msl_latitude', '*', false);
        $packageSearchRequest->addFilterQuery('msl_longitude', '*', false);

        // Set rows
        $paramRows = (int) $request->get('rows');
        if ($paramRows > 0) {
            $packageSearchRequest->rows = $paramRows;
        }

        // Set start
        $paramStart = (int) $request->get('start');
        if ($paramStart > 0) {
            $packageSearchRequest->start = $paramStart;
        }

        // includes facility and equipment query
        $packageSearchRequest->query = $this->buildQuery($request, $this->queryMappingsFacilities);

        // bounding box
        $paramBoundingBox = (string) $request->get('boundingBox');
        if (strlen($paramBoundingBox) > 0) {
            $evaluatedQuery = $this->checkBoundingBoxQuery($paramBoundingBox);
            if (count($evaluatedQuery) == 4) {
                $packageSearchRequest->setBoundingBox(
                    $evaluatedQuery[0],
                    $evaluatedQuery[1],
                    $evaluatedQuery[2],
                    $evaluatedQuery[3]
                );

            } else {
                $errorResponse = new ErrorResponse;
                $errorResponse->message = 'Malformed request to CKAN. "boundingBox" not in correct format or values exceeding bounds. Use "." for decimals. E.g: 12.4 instead of 12,4';

                return $errorResponse->getAsLaravelResponse();
            }
        }

        // Attempt to retrieve data from CKAN
        try {
            $response = $ckanClient->get($packageSearchRequest);
        } catch (\Exception $e) {
            $errorResponse = new ErrorResponse;
            $errorResponse->message = 'Malformed request to CKAN.';

            return $errorResponse->getAsLaravelResponse();
        }

        // Check if CKAN was succesful
        if (! $response->isSuccess()) {
            $errorResponse = new ErrorResponse;
            $errorResponse->message = 'Error received from CKAN api.';

            return $errorResponse->getAsLaravelResponse();
        }

        // Create response object
        $ApiResponse = new MainResponse;
        $ApiResponse->setByCkanResponse($response, $context);

        return $ApiResponse->getAsLaravelResponse();
    }

    /**
     * Checks the string from the boundingBox query
     * or
     * returns each float of the 4 values in an array instead of string
     * when parameter $check equals false
     */
    private function checkBoundingBoxQuery($boundingBoxQuery)
    {
        $bbr = explode(',', $boundingBoxQuery);
        $checkedArr = [];

        if (count($bbr) == 4) { // must be 4 values. It could be that decimals are indicated with comma instead of dot

            if ($this->checkBounds((float) $bbr[0], 180, -180) && is_numeric($bbr[0])) {
                $checkedArr[] = (float) $bbr[0];
            } else {
                return [];
            }
            if ($this->checkBounds((float) $bbr[1], 90, -90) && is_numeric($bbr[1])) {
                $checkedArr[] = (float) $bbr[1];
            } else {
                return [];
            }
            if ($this->checkBounds((float) $bbr[2], 180, -180) && is_numeric($bbr[2])) {
                $checkedArr[] = (float) $bbr[2];
            } else {
                return [];
            }
            if ($this->checkBounds((float) $bbr[3], 90, -90) && is_numeric($bbr[3])) {
                $checkedArr[] = (float) $bbr[3];
            } else {
                return [];
            }

            return $checkedArr;

        } else {
            return [];
        }

    }

    private function checkBounds(float $toCheck, float $limitUp, float $limitLow)
    {
        if (gettype($toCheck) != 'double') {
            return false;
        } else {
            return $toCheck <= $limitUp && $toCheck >= $limitLow ? true : false;
        }
    }
}
