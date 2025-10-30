<?php

namespace App\Http\Controllers\API\V2;

use App\CkanClient\Client;
use App\CkanClient\Request\PackageSearchRequest;
use App\Http\Resources\V2\DataPublicationCollection;
use App\Http\Resources\V2\Errors\CkanErrorResource;
use App\Http\Resources\V2\Errors\ValidationErrorResource;
use App\Rules\GeoRule;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;

enum SubDomainType: string
{
    case ROCK_PHYSICS = 'rock and melt physics';
    case ANALOGUE =  'analogue modelling of geologic processes';
    case MICROSCOPY = 'microscopy and tomography';
    case PALEO = 'paleomagnetism';
    case GEO_CHEMISTRY =  'geochemistry';
    case GEO_ENERGY = 'geo-energy test beds';
}
class DataPublicationController extends BaseController
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
    private $queryMappingsAll;

    /**
     * Constructs a new controller
     */
    public function __construct(\GuzzleHttp\Client $client)
    {
        $this->guzzleClient = $client;
        $this->queryMappingsAll = array_merge($this->queryMappings, ['subDomain' => 'msl_subdomain']);
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
     * Creates a API response based upon search parameters provided in request
     * Context is used to provide subdomain specific processing
     *
     * @param  string  $context
     * @return response
     */
    private function dataPublicationResponse(Request $request, $context)
    {
        try {
            $request->validate([
                'limit' => ['nullable', 'integer', 'min:0'],
                'offset' => ['nullable', 'integer', 'min:0'],
                'query' => ['nullable', 'string'],
                'authorName' => ['nullable', 'string'],
                'labName' => ['nullable', 'string'],
                'title' => ['nullable', 'string'],
                'tags' => ['nullable', 'string'],
                'hasDownloads' => ['nullable', 'boolean'],
                'boundingBox' => ['nullable', new GeoRule()],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return new ValidationErrorResource($e);
        }

        // Create CKAN client
        $ckanClient = new Client($this->guzzleClient);

        // Create packagesearch request
        $packageSearchRequest = new PackageSearchRequest;

        // Filter on data-publications
        $packageSearchRequest->addFilterQuery('type', 'data-publication');

        // Filter for data-publications with files depending on request
        if ($request->get('hasDownloads')) {
            $packageSearchRequest->addFilterQuery('msl_download_link', '*', true);
        }
        $msl_subdomain = 'msl_subdomain';
        // Add subdomain filtering if required
        switch ($context) {
            case 'rockPhysics':
                $packageSearchRequest->addFilterQuery($msl_subdomain, SubDomainType::ROCK_PHYSICS->value);
                break;

            case 'analogue':
                $packageSearchRequest->addFilterQuery($msl_subdomain, SubDomainType::ANALOGUE->value);
                break;

            case 'paleo':
                $packageSearchRequest->addFilterQuery($msl_subdomain, SubDomainType::PALEO->value);
                break;

            case 'microscopy':
                $packageSearchRequest->addFilterQuery($msl_subdomain, SubDomainType::MICROSCOPY->value);
                break;

            case 'geochemistry':
                $packageSearchRequest->addFilterQuery($msl_subdomain, SubDomainType::GEO_CHEMISTRY->value);
                break;

            case 'geoenergy':
                $packageSearchRequest->addFilterQuery($msl_subdomain, SubDomainType::GEO_ENERGY->value);
                break;
        }
        // Set limit
        $limit = (int)  (($request->get('limit')) ? $request->get('limit') : $packageSearchRequest->rows);
        $packageSearchRequest->rows = $limit; // this is the internal to CKAN default value.

        // Set offset
        $offset = (int)  (($request->get('offset')) ? $request->get('offset') : $packageSearchRequest->start);
        $packageSearchRequest->start = $offset;

        // Process search parameters
        $packageSearchRequest->query = ($context == 'all') ? $this->buildQuery($request, $this->queryMappingsAll) : $this->buildQuery($request, $this->queryMappings);

        $paramBoundingBox =  json_decode($request->get('boundingBox') ?? null);
        if ($paramBoundingBox) {
            $packageSearchRequest->setBoundingBox(
                (float)  $paramBoundingBox[0],
                (float)  $paramBoundingBox[1],
                (float) $paramBoundingBox[2],
                (float) $paramBoundingBox[3]
            );
        }
        // Attempt to retrieve data from CKAN
        try {
            $response = $ckanClient->get($packageSearchRequest);
        } catch (\Exception $e) {
            return new CkanErrorResource([]);
        }

        // Check if CKAN was succesful
        if (! $response->isSuccess()) {
            return new CkanErrorResource([]);
        }

        $dataPublications =  $response->getResults(true);
        $totalResultCount = $response->getTotalResultsCount();
        $currentResultCount = count($dataPublications);



        $responseToReturn = new DataPublicationCollection($dataPublications, $context);
        $responseToReturn->additional([
            'success' => 'true',
            'messages' => [],
            'meta' => [
                'resultCount' => $currentResultCount,
                'totalCount' =>  $totalResultCount,
                'limit' => $limit,
                'offset' => $offset
            ],
            'links' => [
                'current_url' => $request->fullUrlWithQuery(['offset' => $offset, 'limit' => $limit])
            ]
        ]);
        return $responseToReturn;
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
