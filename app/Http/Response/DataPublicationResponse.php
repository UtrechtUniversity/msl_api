<?php

namespace App\Http\Response;

use App\Http\Resources\GeoJsonDataPublicationResource;
use App\Models\Ckan\DataPublication;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DataPublicationResponse
{
    public int $limit;

    public int $offset;

    public int $currentCount;

    public int $totalCount;

    /** @var DataPublication[] */
    public array $dataPublications;

    public string $currentUrl;

    /**
     * Create a new class instance.
     */
    public function __construct($response, int $limit, int $offset, string $currentUrl)
    {

        $this->limit = $limit;
        $this->offset = $offset;
        $this->dataPublications = $response->getResults(true);
        $this->totalCount = $response->getTotalResultsCount();
        $this->currentCount = count($this->dataPublications);
        $this->currentUrl = $currentUrl;
    }

    public function getResponse(): JsonResource|ResourceCollection
    {
        $dataPublicationResource = GeoJsonDataPublicationResource::collection($this->dataPublications);

        return $dataPublicationResource->additional([
            'success' => 'true',
            'messages' => [],
            'meta' => [
                'resultCount' => $this->currentCount,
                'totalCount' => $this->totalCount,
                'limit' => $this->limit,
                'offset' => $this->offset,
            ],
            'links' => [
                'current_url' => $this->currentUrl,
            ],
        ]);
    }
}
