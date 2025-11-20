<?php

namespace App\Http\Controllers\API\V2;

use App\CkanClient\Client;
use App\CkanClient\Request\PackageSearchRequest;
use App\Http\Resources\V2\Errors\CkanErrorResource;
use App\Http\Resources\V2\Errors\ValidationErrorResource;
use App\Http\Resources\V2\FacilityResource;
use App\Models\Laboratory;
use App\Rules\GeoRule;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

// TODO move this on its own file
// TODO NOTICE THAT the values are different from the enum in DataPublications!
enum SubDomainTypeInCkan: string
{
    case ROCK_PHYSICS = 'Rock and melt physics';
    case ANALOGUE = 'Analogue modelling of geologic processes';
    case MICROSCOPY = 'Microscopy and tomography';
    case PALEO = 'Paleomagnetism';
    case GEO_CHEMISTRY = 'Geochemistry';
    case GEO_ENERGY = 'Geo-energy test beds';
}
class FacilityController extends BaseController
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
        'country' => 'msl_address_country_name',
        'city' => 'msl_address_city'
    ];

    private $packageSearchRequest;

    private $laboratory;

    /**
     * Constructs the controller
     */
    public function __construct(\GuzzleHttp\Client $client, Laboratory $laboratory)
    {
        $this->guzzleClient = $client;
        $this->packageSearchRequest = new PackageSearchRequest;
        $this->laboratory = $laboratory;
    }

    /**
     * Rock physics facilities endpoint
     *
     * @return response
     */
    public function rockPhysics(Request $request)
    {
        return $this->facilitiesResponse($request, 'rockPhysics');
    }

    /**
     * Analogue modelling facilities endpoint
     *
     * @return response
     */
    public function analogue(Request $request)
    {
        return $this->facilitiesResponse($request, 'analogue');
    }

    /**
     * Paleomagnetism facilities endpoint
     *
     * @return response
     */
    public function paleo(Request $request)
    {
        return $this->facilitiesResponse($request, 'paleo');
    }

    /**
     * Microscopy and tomography facilities endpoint
     *
     * @return response
     */
    public function microscopy(Request $request)
    {
        return $this->facilitiesResponse($request, 'microscopy');
    }

    /**
     * Geochemistry facilities endpoint
     *
     * @return response
     */
    public function geochemistry(Request $request)
    {
        return $this->facilitiesResponse($request, 'geochemistry');
    }

    /**
     * Geo Energy Test Beds facilities endpoint
     *
     * @return response
     */
    public function geoenergy(Request $request)
    {
        return $this->facilitiesResponse($request, 'geoenergy');
    }

    /**
     * All subdomains facilities endpoint
     *
     * @return response
     */
    public function all(Request $request)
    {
        return $this->facilitiesResponse($request, 'all');
    }

    /**
     * Creates a API response based upon search parameters provided in request
     * Context is used to provide facility specific processing
     * only facilities with location data are returned
     *
     * @return response
     */
    private function facilitiesResponse(Request $request, string $context)
    {
        try {
            $request->validate([
                'limit' => ['nullable', 'integer', 'min:0'],
                'offset' => ['nullable', 'integer', 'min:0'],
                'title' => ['nullable', 'string'],
                'country' => ['nullable', 'string'],
                'city' => ['nullable', 'string'],
                'boundingBox' => ['nullable', new GeoRule],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return new ValidationErrorResource($e);
        }

        $this->setRequestToCKAN($request, $context);
        // Create CKAN client
        $ckanClient = new Client($this->guzzleClient);

        // Attempt to retrieve data from CKAN
        try {
            $response = $ckanClient->get($this->packageSearchRequest);
        } catch (\Exception $e) {
            return new CkanErrorResource([]);
        }

        // Check if CKAN was succesful
        if (! $response->isSuccess()) {
            return new CkanErrorResource([]);
        }

        $totalResultCount = $response->getTotalResultsCount();

        $facilities = $this->getResultsfromCkanArray($response->responseBody);
        $currentResultCount = count($facilities);
        $limit = $this->packageSearchRequest->rows;
        $offset = $this->packageSearchRequest->start;
        $responseToReturn = FacilityResource::collection(collect($facilities));
        $responseToReturn->additional([
            'success' => 'true',
            'messages' => [],
            'meta' => [
                'resultCount' => $currentResultCount,
                'totalCount' => $totalResultCount,
                'limit' => $limit,
                'offset' => $offset,
            ],
            'links' => [
                'current_url' => $request->fullUrlWithQuery(['offset' => $offset, 'limit' => $limit]),
            ],
        ]);

        return $responseToReturn;
    }
    /**
     * Building up the request that we are going to send
     * to CKAN for facilities.
     *
     * @return response
     */
    private function getResultsfromCkanArray($responseBody): array
    {
        $resultsFromResponse = $responseBody['result']['results'];

        $resultsToReturn = [];

        foreach ($resultsFromResponse as $result) {
            if ($result['msl_fast_id']) {
                $resultsToReturn[] = $this->laboratory->where('fast_id', $result['msl_fast_id'])->first();
            }
        }

        return $resultsToReturn;
    }

    /**
     * Building up the request that we are going to send
     * to CKAN for facilities.
     *
     * @return response
     */
    private function setRequestToCKAN(Request $request, string $context): void
    {
        // Filter on facilities
        $this->packageSearchRequest->addFilterQuery('type', 'lab');
        $this->setSubdomain($context);

        // Filter for failities with coordinates
        $this->packageSearchRequest->addFilterQuery('msl_latitude', '*', false);
        $this->packageSearchRequest->addFilterQuery('msl_longitude', '*', false);

        // Set rows
        if (($request->get('limit'))) {
            $this->packageSearchRequest->rows = $request->get('limit');
        }
        // Set start
        if ($request->get('offset')) {
            $this->packageSearchRequest->start = $request->get('offset');
        }
        // includes facility and equipment query
        $this->packageSearchRequest->query = $this->buildQuery($request, $this->queryMappingsFacilities);
        // bounding box

        $paramBoundingBox = json_decode($request->get('boundingBox') ?? null);
        if ($paramBoundingBox) {
            $this->packageSearchRequest->setBoundingBox(
                (float) $paramBoundingBox[0],
                (float) $paramBoundingBox[1],
                (float) $paramBoundingBox[2],
                (float) $paramBoundingBox[3]
            );
        }
    }

    // TODO $context also could use a reusable enum
    private function setSubdomain(string $context): void
    {
        $msl_subdomain = 'msl_domain_name';
        // Add subdomain filtering if required
        switch ($context) {
            case 'rockPhysics':
                $this->packageSearchRequest->addFilterQuery($msl_subdomain, SubDomainTypeInCkan::ROCK_PHYSICS->value);
                break;

            case 'analogue':
                $this->packageSearchRequest->addFilterQuery($msl_subdomain, SubDomainTypeInCkan::ANALOGUE->value);
                break;

            case 'paleo':
                $this->packageSearchRequest->addFilterQuery($msl_subdomain, SubDomainTypeInCkan::PALEO->value);
                break;

            case 'microscopy':
                $this->packageSearchRequest->addFilterQuery($msl_subdomain, SubDomainTypeInCkan::MICROSCOPY->value);
                break;

            case 'geochemistry':
                $this->packageSearchRequest->addFilterQuery($msl_subdomain, SubDomainTypeInCkan::GEO_CHEMISTRY->value);
                break;

            case 'geoenergy':
                $this->packageSearchRequest->addFilterQuery($msl_subdomain, SubDomainTypeInCkan::GEO_ENERGY->value);
                break;
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
}
