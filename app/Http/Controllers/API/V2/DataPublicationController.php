<?php

namespace App\Http\Controllers\API\V2;

use App\CkanClient\Client;
use App\Enums\DataPublicationSubDomain;
use App\Enums\EndpointContext;
use App\Http\Resources\V2\Errors\CkanErrorResource;
use App\Http\Resources\V2\Errors\ValidationErrorResource;
use App\Rules\GeoRule;
use App\Services\DataPublicationService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DataPublicationController extends BaseDomainApiController
{
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
        parent::__construct($client); // Call parent constructor
        $this->queryMappingsAll = array_merge($this->queryMappings, ['subDomain' => 'msl_subdomain']);
    }

    // TODO have a different endpoint for geojson
    // todo Maybe also a different controller.
    // todo Might want to return regular json response rather than pure geojson.

    /**
     * Creates a API response based upon search parameters provided in request
     * Context is used to provide subdomain specific processing
     */
    protected function domainResponse(Request $request, EndpointContext $context): JsonResource|ResourceCollection
    {
        // TODO I would like a different endpoint
        // Geo json and json are the same content type
        $preferredType = $request->prefers(['application/json', 'application/geojson']);
        // For now, we want always a bounding box set if we are asking for geojson
        if ($preferredType === 'application/geojson') {
            try {
                $request->validate([
                    'boundingBox' => ['required', new GeoRule],
                ]);
            } catch (\Illuminate\Validation\ValidationException $e) {
                return new ValidationErrorResource($e);
            }
        }

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

        $this->setRequestToCKAN($request, $context);
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

        $limit = $this->packageSearchRequest->rows;
        $offset = $this->packageSearchRequest->start;
        $dpService = new DataPublicationService;

        if ($preferredType === 'application/geojson') {
            return $dpService->getGeoJsonResponse(
                response: $response,
                limit: $limit,
                offset: $offset,
                currentUrl: $request->fullUrlWithQuery(['offset' => $offset, 'limit' => $limit]),
                context: $context
            );
        } else {
            return $dpService->getResponse(
                response: $response,
                limit: $limit,
                offset: $offset,
                currentUrl: $request->fullUrlWithQuery(['offset' => $offset, 'limit' => $limit]),
                context: $context
            );
        }
    }

    protected function setRequestToCKAN(Request $request, EndpointContext $context): void
    {
        // Filter on data-publications
        $this->packageSearchRequest->addFilterQuery('type', 'data-publication');
        $this->getDomain($context);

        // Filter for data-publications with files depending on request
        if ($request->get('hasDownloads')) {
            $this->packageSearchRequest->addFilterQuery('msl_download_link', '*', true);
        }

        // Set rows
        if (($request->get('limit'))) {
            $this->packageSearchRequest->rows = $request->get('limit');
        }
        // Set start
        if ($request->get('offset')) {
            $this->packageSearchRequest->start = $request->get('offset');
        }
        // Process search parameters
        $this->packageSearchRequest->query = ($context == EndpointContext::ALL) ? $this->buildQuery($request, $this->queryMappingsAll) : $this->buildQuery($request, $this->queryMappings);

        $boundingBox = $request->get('boundingBox') ?? null;
        $this->getBoundingBox($boundingBox);
    }

    protected function getDomain(EndpointContext $context): void
    {
        $msl_subdomain = 'msl_subdomain';
        // Add subdomain filtering if required
        switch ($context) {
            case EndpointContext::ROCK_PHYSICS:
                $this->packageSearchRequest->addFilterQuery($msl_subdomain, DataPublicationSubDomain::ROCK_PHYSICS->value);
                break;

            case EndpointContext::ANALOGUE:
                $this->packageSearchRequest->addFilterQuery($msl_subdomain, DataPublicationSubDomain::ANALOGUE->value);
                break;

            case EndpointContext::PALEO:
                $this->packageSearchRequest->addFilterQuery($msl_subdomain, DataPublicationSubDomain::PALEO->value);
                break;

            case EndpointContext::MICROSCOPY:
                $this->packageSearchRequest->addFilterQuery($msl_subdomain, DataPublicationSubDomain::MICROSCOPY->value);
                break;

            case EndpointContext::GEO_CHEMISTRY:
                $this->packageSearchRequest->addFilterQuery($msl_subdomain, DataPublicationSubDomain::GEO_CHEMISTRY->value);
                break;

            case EndpointContext::GEO_ENERGY:
                $this->packageSearchRequest->addFilterQuery($msl_subdomain, DataPublicationSubDomain::GEO_ENERGY->value);
                break;
        }
    }
}
