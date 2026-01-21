<?php

namespace App\Http\Controllers\API;

use App\CkanClient\Client;
use App\CkanClient\Request\PackageSearchRequest;
use App\Enums\EndpointContext;
use App\Http\Controllers\Controller;
use App\Http\Resources\GeoJsonDataPublicationResource;
use App\Http\Resources\V2\Errors\CkanErrorResource;
use App\Http\Resources\V2\Errors\ValidationErrorResource;
use App\Rules\GeoRule;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class GeoJsonDataPublicationsController extends Controller
{
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
    }

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
        //
        $dataPublications = $response->getResults(true);
        $totalResultCount = $response->getTotalResultsCount();
        $currentResultCount = count($dataPublications);
        $responseToReturn = GeoJsonDataPublicationResource::collection($dataPublications);
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

    protected function setRequestToCKAN(Request $request): void
    {
        // Filter on data-publications
        $this->packageSearchRequest->addFilterQuery('type', 'data-publication');

        // Set rows
        if (($request->get('limit'))) {
            $this->packageSearchRequest->rows = $request->get('limit');
        }
        // Set start
        if ($request->get('offset')) {
            $this->packageSearchRequest->start = $request->get('offset');
        }

        $boundingBox = $request->get('boundingBox') ?? null;
        $this->getBoundingBox($boundingBox);
    }

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
}
