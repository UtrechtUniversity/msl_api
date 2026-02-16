<?php

namespace App\Http\Controllers\API;

use App\CkanClient\Client;
use App\Http\Controllers\Controller;
use App\Http\Requests\GeoJsonDataPublicationRequest;
use App\Http\Resources\InclusiveExclusiveGeoJsonDataPublicationsResource;
use App\Services\GeoJsonDataPublicationService;
use App\Services\InclusiveExclusiveGeoJsonFeatureService;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class GeoJsonDataPublicationsController extends Controller
{
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
    }

    /**
     * Creates a API response based upon search parameters provided in request
     * Context is used to provide subdomain specific processing
     */
    protected function index(
        GeoJsonDataPublicationRequest $request,
        GeoJsonDataPublicationService $geoJsonDataPublicationService,
    ): JsonResource|ResourceCollection {

        // TODO pass it as a service?
        $service = new InclusiveExclusiveGeoJsonFeatureService;

        // Get bounding box
        $bbox = $geoJsonDataPublicationService->getBoundingBoxFromRequest($request);
        // Get response from ckan
        $dataPublicationResponse = $geoJsonDataPublicationService->getDataPublicationResponse($this->guzzleClient, $request);
        // Create instance of an intermediate class
        $inclusiveExclusiveGeoJson = $service->createInclusiveExclusiveGeoJson($dataPublicationResponse->dataPublications, $bbox);

        $resource = new InclusiveExclusiveGeoJsonDataPublicationsResource($inclusiveExclusiveGeoJson);

        return $dataPublicationResponse->getJsonResponse($resource);
    }
}
