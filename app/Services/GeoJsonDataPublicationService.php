<?php

namespace App\Services;

use App\CkanClient\Client;
use App\CkanClient\Request\PackageSearchRequest;
use App\Http\Resources\V2\Errors\CkanErrorResource;
use App\Http\Response\DataPublicationResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class GeoJsonDataPublicationService
{
    protected $packageSearchRequest;

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->packageSearchRequest = new PackageSearchRequest;
    }

    // Set request to ckan: protected
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
        $this->getBoundingBox($boundingBox, $this->packageSearchRequest);
    }

    // make request and handle:public
    protected function getResponseFromCKAN(\GuzzleHttp\Client $client, Request $request)
    {
        $ckanClient = new Client($client);

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

        return $response;
    }
    // Create response and return: public

    public function getDataPublicationResponse(\GuzzleHttp\Client $client, Request $request): JsonResource|ResourceCollection
    {
        $responseFromCkan = $this->getResponseFromCKAN($client, $request);
        $limit = $this->packageSearchRequest->rows;
        $offset = $this->packageSearchRequest->start;
        $currentUrl = $request->fullUrlWithQuery(['offset' => $offset, 'limit' => $limit]);

        return (new DataPublicationResponse(response: $responseFromCkan, limit: $limit, offset: $offset, currentUrl: $currentUrl))->getResponse();
    }

    protected function getBoundingBox(?string $boundingBox, PackageSearchRequest $packageSearchRequest): void
    {
        $paramBoundingBox = json_decode($boundingBox);
        if ($paramBoundingBox) {
            $packageSearchRequest->setBoundingBox(
                (float) $paramBoundingBox[0],
                (float) $paramBoundingBox[1],
                (float) $paramBoundingBox[2],
                (float) $paramBoundingBox[3]
            );
        }
    }
}
