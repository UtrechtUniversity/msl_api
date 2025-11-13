<?php

namespace App\Http\Controllers;

use App\CkanClient\Client;
use App\CkanClient\Request\PackageSearchRequest;
use App\Http\Resources\FacilityResource;
use App\Response\V1\ErrorResponse;
use App\Response\V1\MainResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class FacilitiesController extends BaseController
{
    /**
     * @var \GuzzleHttp\Client Guzzle http client instance
     */
    private $guzzleClient;

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
     * Context is used to provide facility specific processing
     * only facilities with location data are returned
     *
     * @param  string  $context
     * @return response
     */
    private function facilitiesResponse(Request $request)
    {
        $context = 'facilities';
        $packageSearchRequest = $this->setRequestToCKAN($request);

        // Create CKAN client
        $ckanClient = new Client($this->guzzleClient);

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

        $facilities = $response->getResults(true);
        return FacilityResource::collection(collect($facilities));
        // Create response object
        // $ApiResponse = new MainResponse;
        // $ApiResponse->setByCkanResponse($response, $context);

        // return $ApiResponse->getAsLaravelResponse();
    }

    /**
     * Building up the request that we are going to send
     * to CKAN for facilities.
     *
     * @param  Request  $request
     * @return response
     */
    private function setRequestToCKAN(Request $request): \App\CkanClient\Request\PackageSearchRequest
    {
        // Create packagesearch request
        $packageSearchRequest = new PackageSearchRequest();
        // Create packagesearch request
        $packageSearchRequest = new PackageSearchRequest;

        // Filter on facilities
        $packageSearchRequest->addFilterQuery('type', 'lab');

        // Filter for failities with coordinates
        $packageSearchRequest->addFilterQuery('msl_latitude', '*', false);
        $packageSearchRequest->addFilterQuery('msl_longitude', '*', false);

        // Set rows
        $limit = (int) (($request->get('limit')) ? $request->get('limit') : $packageSearchRequest->rows);
        $packageSearchRequest->rows = $limit;


        // Set start
        $offset = (int) (($request->get('offset')) ? ($request->get('offset')) : $packageSearchRequest->start);
        $packageSearchRequest->start = $offset;

        // includes facility and equipment query
        $packageSearchRequest->query = $this->buildQuery($request, $this->queryMappingsFacilities);
        // bounding box
        $paramBoundingBox = (string) $request->get('boundingBox');
        if (strlen($paramBoundingBox) > 0) {
            $evaluatedQuery = $this->boundingboxStringToArray($paramBoundingBox);
            if (count($evaluatedQuery) == 4) {
                $packageSearchRequest->setBoundingBox(
                    $evaluatedQuery[0],
                    $evaluatedQuery[1],
                    $evaluatedQuery[2],
                    $evaluatedQuery[3]
                );
                // } else {
                //     $errorResponse = new ErrorResponse;
                //     $errorResponse->message = 'Malformed request to CKAN. "boundingBox" not in correct format or values exceeding bounds. Use "." for decimals. E.g: 12.4 instead of 12,4';

                //     return $errorResponse->getAsLaravelResponse();
            }
        }

        return $packageSearchRequest;
    }

    /**
     * Convert boundingbox parameter to array
     *
     * @return array
     */
    private function boundingboxStringToArray(string $boundingBoxQuery)
    {
        $boundingBoxElements = explode(',', $boundingBoxQuery);
        $checkedArr = [];

        // must be 4 values. It could be that decimals are indicated with comma instead of dot
        if (count($boundingBoxElements) == 4) {
            if ($this->checkBounds((float) $boundingBoxElements[0], 180, -180) && is_numeric($boundingBoxElements[0])) {
                $checkedArr[] = (float) $boundingBoxElements[0];
            } else {
                return [];
            }
            if ($this->checkBounds((float) $boundingBoxElements[1], 90, -90) && is_numeric($boundingBoxElements[1])) {
                $checkedArr[] = (float) $boundingBoxElements[1];
            } else {
                return [];
            }
            if ($this->checkBounds((float) $boundingBoxElements[2], 180, -180) && is_numeric($boundingBoxElements[2])) {
                $checkedArr[] = (float) $boundingBoxElements[2];
            } else {
                return [];
            }
            if ($this->checkBounds((float) $boundingBoxElements[3], 90, -90) && is_numeric($boundingBoxElements[3])) {
                $checkedArr[] = (float) $boundingBoxElements[3];
            } else {
                return [];
            }

            return $checkedArr;
        } else {
            return [];
        }
    }

    /**
     * Check if bounding box component is within limits
     *
     * @return bool
     */
    private function checkBounds(float $toCheck, float $limitUp, float $limitLow)
    {
        if (gettype($toCheck) != 'double') {
            return false;
        } else {
            return $toCheck <= $limitUp && $toCheck >= $limitLow ? true : false;
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
                    $queryParts[] = $value . ':"' . $request->get($key) . '"';
                } else {
                    $queryParts[] = $value . ':' . $request->get($key);
                }
            }
        }

        if (count($queryParts) > 0) {
            return implode(' AND ', $queryParts);
        }

        return '';
    }
}
