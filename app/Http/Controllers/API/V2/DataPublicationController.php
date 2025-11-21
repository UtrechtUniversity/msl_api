<?php

namespace App\Http\Controllers\API\V2;

use App\CkanClient\Client;
use App\CkanClient\Request\PackageSearchRequest;
use App\Enums\DataPublicationSubDomain;
use App\Enums\EndpointContext;
use App\Http\Resources\V2\DataPublicationCollection;
use App\Http\Resources\V2\Errors\CkanErrorResource;
use App\Http\Resources\V2\Errors\ValidationErrorResource;
use App\Rules\GeoRule;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Routing\Controller as BaseController;

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
     * @return  JsonResource | ResourceCollection
     */
    public function rockPhysics(Request $request): JsonResource | ResourceCollection
    {
        return $this->dataPublicationResponse($request, EndpointContext::ROCK_PHYSICS);
    }

    /**
     * Analogue modelling API endpoint
     *
     * @return JsonResource | ResourceCollection
     */
    public function analogue(Request $request): JsonResource | ResourceCollection
    {
        return $this->dataPublicationResponse($request, EndpointContext::ANALOGUE);
    }

    /**
     * Paleomagnetism API endpoint
     *
     * @return JsonResource | ResourceCollection
     */
    public function paleo(Request $request): JsonResource | ResourceCollection
    {
        return $this->dataPublicationResponse($request, EndpointContext::PALEO);
    }

    /**
     * Microscopy and tomography API endpoint
     *
     * @return JsonResource | ResourceCollection
     */
    public function microscopy(Request $request): JsonResource | ResourceCollection
    {
        return $this->dataPublicationResponse($request, EndpointContext::MICROSCOPY);
    }

    /**
     * Geochemistry API endpoint
     *
     * @return JsonResource | ResourceCollection
     */
    public function geochemistry(Request $request): JsonResource | ResourceCollection
    {
        return $this->dataPublicationResponse($request, EndpointContext::GEO_CHEMISTRY);
    }

    /**
     * Geo Energy Test Beds API endpoint
     *
     * @return JsonResource | ResourceCollection
     */
    public function geoenergy(Request $request): JsonResource | ResourceCollection
    {
        return $this->dataPublicationResponse($request, EndpointContext::GEO_ENERGY);
    }

    /**
     * All subdomains API endpoint
     *
     * @return JsonResource | ResourceCollection
     */
    public function all(Request $request): JsonResource | ResourceCollection
    {
        return $this->dataPublicationResponse($request, EndpointContext::ALL);
    }

    /**
     * Creates a API response based upon search parameters provided in request
     * Context is used to provide subdomain specific processing
     *
     * @param  string  $context
     * @return JsonResource | ResourceCollection
     */
    private function dataPublicationResponse(Request $request, EndpointContext $context): JsonResource | ResourceCollection
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
                'boundingBox' => ['nullable', new GeoRule],
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
            case EndpointContext::ROCK_PHYSICS:
                $packageSearchRequest->addFilterQuery($msl_subdomain, DataPublicationSubDomain::ROCK_PHYSICS->value);
                break;

            case EndpointContext::ANALOGUE:
                $packageSearchRequest->addFilterQuery($msl_subdomain, DataPublicationSubDomain::ANALOGUE->value);
                break;

            case EndpointContext::PALEO:
                $packageSearchRequest->addFilterQuery($msl_subdomain, DataPublicationSubDomain::PALEO->value);
                break;

            case EndpointContext::MICROSCOPY:
                $packageSearchRequest->addFilterQuery($msl_subdomain, DataPublicationSubDomain::MICROSCOPY->value);
                break;

            case EndpointContext::GEO_CHEMISTRY:
                $packageSearchRequest->addFilterQuery($msl_subdomain, DataPublicationSubDomain::GEO_CHEMISTRY->value);
                break;

            case EndpointContext::GEO_ENERGY:
                $packageSearchRequest->addFilterQuery($msl_subdomain, DataPublicationSubDomain::GEO_ENERGY->value);
                break;
        }
        // Set limit
        $limit = (int) (($request->get('limit')) ? $request->get('limit') : $packageSearchRequest->rows);
        $packageSearchRequest->rows = $limit; // this is the internal to CKAN default value.

        // Set offset
        $offset = (int) (($request->get('offset')) ? $request->get('offset') : $packageSearchRequest->start);
        $packageSearchRequest->start = $offset;

        // Process search parameters
        $packageSearchRequest->query = ($context == 'all') ? $this->buildQuery($request, $this->queryMappingsAll) : $this->buildQuery($request, $this->queryMappings);

        $paramBoundingBox = json_decode($request->get('boundingBox') ?? null);
        if ($paramBoundingBox) {
            $packageSearchRequest->setBoundingBox(
                (float) $paramBoundingBox[0],
                (float) $paramBoundingBox[1],
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

        $dataPublications = $response->getResults(true);
        $totalResultCount = $response->getTotalResultsCount();
        $currentResultCount = count($dataPublications);

        $responseToReturn = new DataPublicationCollection($dataPublications, $context);
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
     * Converts search parameters to solr query using field mappings
     *
     * @param  array  $querymappings
     * @return string
     */
    private function buildQuery(Request $request, $queryMappings): string
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
