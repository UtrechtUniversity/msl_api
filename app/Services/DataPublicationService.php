<?php

namespace App\Services;

use App\CkanClient\Response\PackageSearchResponse;
use App\Enums\EndpointContext;
use App\Http\Resources\GeoFeatureResource;
use App\Http\Resources\V2\DataPublicationCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DataPublicationService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getResponse(PackageSearchResponse $response, int $limit, int $offset, string $currentUrl, EndpointContext $context): JsonResource|ResourceCollection
    {
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
                'current_url' => $currentUrl,
            ],
        ]);

        return $responseToReturn;
    }

    public function getGeoJsonResponse(PackageSearchResponse $response, int $limit, int $offset, string $currentUrl, EndpointContext $context): JsonResource|ResourceCollection
    {

        // TODO filter in only datapublications that have coordinates
        $dataPublications = $response->getResults(true);
        $totalResultCount = $response->getTotalResultsCount();
        $currentResultCount = count($dataPublications);
        $responseToReturn = GeoFeatureResource::collection($dataPublications);
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
                'current_url' => $currentUrl,
            ],
        ]);

        return $responseToReturn;
    }
}
