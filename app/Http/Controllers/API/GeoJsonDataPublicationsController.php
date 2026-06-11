<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\GeoJsonDataPublicationRequest;
use App\Http\Resources\GeoFeatureDataPublicationResource;
use App\Services\GeoFeatureDatapublicationService;
use App\Services\GeoJsonDataPublicationService;
use GuzzleHttp\Client;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class GeoJsonDataPublicationsController extends Controller
{
    /**
     * @var Client Guzzle http client instance
     */
    protected $guzzleClient;

    /**
     * Constructs a new controller
     */
    public function __construct(Client $client)
    {
        $this->guzzleClient = $client;
    }

    protected function index(
        GeoJsonDataPublicationRequest $request,
        GeoJsonDataPublicationService $dataPublicationService,
        GeoFeatureDatapublicationService $geoFeatureDatapublicationService,
    ): JsonResource|ResourceCollection {

        // Get bounding box
        $bbox = $dataPublicationService->getBoundingBoxFromRequest($request);
        // Get response from ckan
        $dataPublicationResponse = $dataPublicationService->getDataPublicationResponse($this->guzzleClient, $request);
        // Create instance of an intermediate class,
        // where filtering and restructure is done.
        $geoFeatureDatapublication = $geoFeatureDatapublicationService->createGeoFeatureDataPublication($dataPublicationResponse->dataPublications, $bbox);

        $resource = new GeoFeatureDataPublicationResource($geoFeatureDatapublication);

        return $dataPublicationResponse->getJsonResponse($resource);
    }
}
