<?php

namespace App\Http\Controllers\Console;

use App\CkanClient\Client;
use App\CkanClient\Request\PackageSearchRequest;
use App\Enums\EndpointContext;
use App\Http\Controllers\Controller;
use App\Http\Resources\V2\Errors\CkanErrorResource;
use App\Http\Resources\V2\Errors\ValidationErrorResource;
use App\Rules\GeoRule;
use App\Services\DataPublicationService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class GeoJsonDataPublicationsController extends Controller
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

    protected $packageSearchRequest;

    /**
     * @var \GuzzleHttp\Client Guzzle http client instance
     */
    protected $guzzleClient;

    /**
     * Constructs a new controller
     */
    public function __construct(\GuzzleHttp\Client $client)
    {
        $this->guzzleClient = $client;
        $this->packageSearchRequest = new PackageSearchRequest;
        $this->queryMappingsAll = array_merge($this->queryMappings, ['subDomain' => 'msl_subdomain']);
    }

    // TODO have a different endpoint for geojson
    // todo Maybe also a different controller.
    // todo Might want to return regular json response rather than pure geojson.

    /**
     * Creates a API response based upon search parameters provided in request
     * Context is used to provide subdomain specific processing
     */
    protected function index(Request $request): JsonResource|ResourceCollection
    {
        $context = EndpointContext::ALL;
        try {
            $request->validate([
                'limit' => ['nullable', 'integer', 'min:0'],
                'offset' => ['nullable', 'integer', 'min:0'],
                'boundingBox' => ['nullable', new GeoRule],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return new ValidationErrorResource($e);
        }

        // Create CKAN client
        $ckanClient = new Client($this->guzzleClient);

        $this->setRequestToCKAN($request);
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

        return $dpService->getGeoJsonResponse(
            response: $response,
            limit: $limit,
            offset: $offset,
            currentUrl: $request->fullUrlWithQuery(['offset' => $offset, 'limit' => $limit]),
            context: $context
        );

    }

    protected function setRequestToCKAN(Request $request): void
    {
        // Filter on data-publications
        $this->packageSearchRequest->addFilterQuery('type', 'data-publication');

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
        $this->packageSearchRequest->query = $this->buildQuery($request, $this->queryMappingsAll);

        $boundingBox = $request->get('boundingBox') ?? null;
        $this->getBoundingBox($boundingBox);
    }

    // TODO Can I make a service out of it? Or maybe an action?
    protected function getBoundingBox(?string $boundingBox): void
    {
        $paramBoundingBox = json_decode($boundingBox);
        if ($paramBoundingBox) {
            $this->packageSearchRequest->setBoundingBox(
                (float) $paramBoundingBox[0],
                (float) $paramBoundingBox[1],
                (float) $paramBoundingBox[2],
                (float) $paramBoundingBox[3]
            );
        }
    }

    /**
     * Converts search parameters to solr query using field mappings
     *
     * @param  array  $querymappings
     */
    protected function buildQuery(Request $request, $queryMappings): string
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
