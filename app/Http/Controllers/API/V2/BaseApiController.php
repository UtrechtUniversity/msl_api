<?php

namespace App\Http\Controllers\API\V2;

use App\CkanClient\Request\PackageSearchRequest;
use App\Enums\EndpointContext;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

abstract class BaseApiController extends Controller
{
    protected $packageSearchRequest;

    /**
     * @var \GuzzleHttp\Client Guzzle http client instance
     */
    protected $guzzleClient;
    public function __construct(\GuzzleHttp\Client $client)
    {
        $this->guzzleClient = $client;
        $this->packageSearchRequest = new PackageSearchRequest;
    }

    /**
     * Rock physics facilities endpoint
     *
     * @return JsonResource | ResourceCollection
     */
    public function rockPhysics(Request $request): JsonResource | ResourceCollection
    {
        return $this->domainResponse($request, EndpointContext::ROCK_PHYSICS);
    }

    /**
     * Analogue modelling facilities endpoint
     *
     * @return JsonResource | ResourceCollection
     */
    public function analogue(Request $request): JsonResource | ResourceCollection
    {
        return $this->domainResponse($request, EndpointContext::ANALOGUE);
    }

    /**
     * Paleomagnetism facilities endpoint
     *
     * @return JsonResource | ResourceCollection
     */
    public function paleo(Request $request): JsonResource | ResourceCollection
    {
        return $this->domainResponse($request, EndpointContext::PALEO);
    }

    /**
     * Microscopy and tomography facilities endpoint
     *
     * @return JsonResource | ResourceCollection
     */
    public function microscopy(Request $request): JsonResource | ResourceCollection
    {
        return $this->domainResponse($request, EndpointContext::MICROSCOPY);
    }

    /**
     * Geochemistry facilities endpoint
     *
     * @return JsonResource | ResourceCollection
     */
    public function geochemistry(Request $request): JsonResource | ResourceCollection
    {
        return $this->domainResponse($request, EndpointContext::GEO_CHEMISTRY);
    }

    /**
     * Geo Energy Test Beds facilities endpoint
     *
     * @return JsonResource | ResourceCollection
     */
    public function geoenergy(Request $request): JsonResource | ResourceCollection
    {
        return $this->domainResponse($request, EndpointContext::GEO_ENERGY);
    }

    /**
     * All subdomains facilities endpoint
     *
     * @return JsonResource | ResourceCollection
     */
    public function all(Request $request): JsonResource | ResourceCollection
    {
        return $this->domainResponse($request, EndpointContext::ALL);
    }

    /**
     * Creates a API response based upon search parameters provided in request
     * Context is used to provide facility specific processing
     * only facilities with location data are returned
     *
     * @return JsonResource | ResourceCollection
     */
    abstract protected function domainResponse(Request $request, EndpointContext $context): JsonResource | ResourceCollection;


    abstract protected function getDomain(EndpointContext $context): void;


    protected function getBoundingBox(string|null $boundingBox): void
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
     * @return string
     */
    protected function buildQuery(Request $request, $queryMappings): string
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
