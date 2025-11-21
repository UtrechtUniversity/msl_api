<?php

namespace App\Http\Controllers\API\V2;

use App\CkanClient\Client;
use App\Enums\DataPublicationSubDomain;
use App\Enums\EndpointContext;
use App\Http\Resources\V2\DataPublicationCollection;
use App\Http\Resources\V2\Errors\CkanErrorResource;
use App\Http\Resources\V2\Errors\ValidationErrorResource;
use App\Rules\GeoRule;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DataPublicationController extends BaseApiController
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

    /**
     * Creates a API response based upon search parameters provided in request
     * Context is used to provide subdomain specific processing
     *
     * @param  string  $context
     */
    protected function domainResponse(Request $request, EndpointContext $context): JsonResource|ResourceCollection
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

        // Filter on data-publications
        $this->packageSearchRequest->addFilterQuery('type', 'data-publication');
        $this->getDomain($context);

        // Filter for data-publications with files depending on request
        if ($request->get('hasDownloads')) {
            $this->packageSearchRequest->addFilterQuery('msl_download_link', '*', true);
        }

        // Set limit
        $limit = (int) (($request->get('limit')) ? $request->get('limit') : $this->packageSearchRequest->rows);
        $this->packageSearchRequest->rows = $limit; // this is the internal to CKAN default value.

        // Set offset
        $offset = (int) (($request->get('offset')) ? $request->get('offset') : $this->packageSearchRequest->start);
        $this->packageSearchRequest->start = $offset;

        // Process search parameters
        $this->packageSearchRequest->query = ($context == 'all') ? $this->buildQuery($request, $this->queryMappingsAll) : $this->buildQuery($request, $this->queryMappings);

        $boundingBox = $request->get('boundingBox') ?? null;
        $this->getBoundingBox($boundingBox);
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
