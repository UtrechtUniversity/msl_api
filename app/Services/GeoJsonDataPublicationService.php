<?php

namespace App\Services;

use App\Clients\CkanClient\Client;
use App\Clients\CkanClient\Request\PackageSearchRequest;
use App\GeoJson\BoundingBox;
use App\Http\Resources\V2\Errors\CkanErrorResource;
use App\Http\Response\DataPublicationResponse;
use Exception;
use Illuminate\Http\Request;

class GeoJsonDataPublicationService
{
    protected PackageSearchRequest $packageSearchRequest;

    public function __construct()
    {
        $this->packageSearchRequest = new PackageSearchRequest;
    }

    /**
     * Make request to CKAN and get back the cleaned-up data publication response
     */
    public function getDataPublicationResponse(\GuzzleHttp\Client $client, Request $request): DataPublicationResponse
    {
        $responseFromCkan = $this->getResponseFromCKAN($client, $request);
        [$page, $pageSize] = $this->toPageNumberAndPage($this->packageSearchRequest->rows, $this->packageSearchRequest->start);
        $currentUrl = $request->fullUrlWithQuery(['page' => $page, 'pageSize' => $pageSize]);

        return new DataPublicationResponse(
            $responseFromCkan,
            page: $page,
            pageSize: $pageSize,
            currentUrl: $currentUrl
        );
    }

    private function toRowsAndStart(int $page, int $pageSize)
    {
        $rows = $pageSize;
        $start = ($page - 1) * $pageSize;

        return [$rows,  $start];
    }

    private function toPageNumberAndPage(int $rows, int $start)
    {
        $page = floor($start / $rows) + 1;
        $pageSize = $rows;

        return [$page,  $pageSize];
    }

    public function getBoundingBoxFromRequest(Request $request): BoundingBox
    {
        $boundingBox = json_decode($request->get('boundingBox'));
        if (! ((bool) $boundingBox && is_array($boundingBox) && count($boundingBox) === 4)) {
            throw new Exception('Bounding box is not defined correctly. This is a bug.');
        }

        return new BoundingBox($boundingBox[0], $boundingBox[1], $boundingBox[2], $boundingBox[3]);
    }

    /**
     * Create the request to send to CKAN
     */
    private function setRequestToCKAN(Request $request): void
    {

        // Filter on data-publications
        $this->packageSearchRequest->addFilterQuery('type', 'data-publication');
        [$rows, $start] = $this->toRowsAndStart($request->get('page'), $request->get('pageSize'));

        // Set rows
        $this->packageSearchRequest->rows = $rows;
        // Set start
        $this->packageSearchRequest->start = $start;
        $boundingBox = $request->get('boundingBox') ?? null;
        $this->setBoundingBox($boundingBox, $this->packageSearchRequest);
    }

    /**
     * Send the request to CKAN and error handling
     */
    private function getResponseFromCKAN(\GuzzleHttp\Client $client, Request $request)
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

    private function setBoundingBox(?string $boundingBox, PackageSearchRequest $packageSearchRequest): void
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
