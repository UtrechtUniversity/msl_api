<?php

namespace App\Http\Controllers\API;

use App\CkanClient\Client;
use App\Http\Controllers\Controller;
use App\Http\Requests\GeoJsonDataPublicationRequest;
use App\Services\GeoJsonDataPublicationService;
use Illuminate\Http\Request;
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
    protected function index(GeoJsonDataPublicationRequest $request, GeoJsonDataPublicationService $geoJsonDataPublicationService): JsonResource|ResourceCollection
    {

        $dataPublicationResponse = $geoJsonDataPublicationService->getDataPublicationResponse($this->guzzleClient, $request);
        $responseToReturn = $dataPublicationResponse->getGeoJsonResponse();

        return $responseToReturn;
    }
}
