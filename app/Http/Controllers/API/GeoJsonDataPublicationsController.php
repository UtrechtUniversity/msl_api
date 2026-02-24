<?php

namespace App\Http\Controllers\API;

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

    protected function index(
        GeoJsonDataPublicationRequest $request,
        GeoJsonDataPublicationService $dataPublicationService,
        InclusiveExclusiveGeoJsonFeatureService $inclusiveExclusiveGeoJsonService,
    ): JsonResource|ResourceCollection {

        // Get bounding box
        $bbox = $dataPublicationService->getBoundingBoxFromRequest($request);
        // Get response from ckan
        $dataPublicationResponse = $dataPublicationService->getDataPublicationResponse($this->guzzleClient, $request);
        // Create instance of an intermediate class,
        // where filtering and restructure is done.
        $inclusiveExclusiveGeoJson = $inclusiveExclusiveGeoJsonService->createInclusiveExclusiveGeoJson($dataPublicationResponse->dataPublications, $bbox);

        $resource = new InclusiveExclusiveGeoJsonDataPublicationsResource($inclusiveExclusiveGeoJson);

        return $dataPublicationResponse->getJsonResponse($resource);
    }
}
