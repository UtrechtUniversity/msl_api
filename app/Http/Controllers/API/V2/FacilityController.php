<?php

namespace App\Http\Controllers\API\V2;

use App\CkanClient\Client;
use App\Enums\EndpointContext;
use App\Enums\LabDomain;
use App\Http\Resources\V2\Errors\CkanErrorResource;
use App\Http\Resources\V2\Errors\ValidationErrorResource;
use App\Http\Resources\V2\FacilityResource;
use App\Models\Laboratory;
use App\Rules\GeoRule;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class FacilityController extends BaseApiController
{
    /**
     * @var array mappings from all endpoint search parameters to ckan fields
     */
    private $queryMappingsFacilities = [
        'query' => 'text',
        'tags' => 'tags',
        'title' => 'title',
        'country' => 'msl_address_country_name',
        'city' => 'msl_address_city',
    ];

    private $laboratory;

    /**
     * Constructs the controller
     */
    public function __construct(\GuzzleHttp\Client $client, Laboratory $laboratory)
    {
        parent::__construct($client); // Call parent constructor
        $this->laboratory = $laboratory;
    }

    /**
     * Creates a API response based upon search parameters provided in request
     * Context is used to provide facility specific processing
     * only facilities with location data are returned
     */
    protected function domainResponse(Request $request, EndpointContext $context): JsonResource|ResourceCollection
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
     * From the CKAN records, it retrieves
     * the corresponding instances from the SQL database.
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
     */
    private function setRequestToCKAN(Request $request, EndpointContext $context): void
    {
        // Filter on facilities
        $this->packageSearchRequest->addFilterQuery('type', 'lab');
        $this->getDomain($context);

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
        $boundingBox = $request->get('boundingBox') ?? null;
        $this->getBoundingBox($boundingBox);
    }

    protected function getDomain(EndpointContext $context): void
    {
        $msl_subdomain = 'msl_domain_name';
        // Add subdomain filtering if required
        switch ($context) {
            case EndpointContext::ROCK_PHYSICS:
                $this->packageSearchRequest->addFilterQuery($msl_subdomain, LabDomain::ROCK_PHYSICS->value);
                break;

            case EndpointContext::ROCK_PHYSICS:
                $this->packageSearchRequest->addFilterQuery($msl_subdomain, LabDomain::ANALOGUE->value);
                break;

            case EndpointContext::PALEO:
                $this->packageSearchRequest->addFilterQuery($msl_subdomain, LabDomain::PALEO->value);
                break;

            case EndpointContext::MICROSCOPY:
                $this->packageSearchRequest->addFilterQuery($msl_subdomain, LabDomain::MICROSCOPY->value);
                break;

            case EndpointContext::GEO_CHEMISTRY:
                $this->packageSearchRequest->addFilterQuery($msl_subdomain, LabDomain::GEO_CHEMISTRY->value);
                break;

            case EndpointContext::GEO_ENERGY:
                $this->packageSearchRequest->addFilterQuery($msl_subdomain, LabDomain::GEO_ENERGY->value);
                break;
        }
    }
}
