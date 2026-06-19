<?php

namespace App\Http\Response;

use App\CkanClient\Response\PackageSearchResponse;
use App\Models\Ckan\DataPublication;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;

class DataPublicationResponse
{
    public int $page;

    public int $pageSize;

    public int $currentCount;

    public int $totalCount;

    /** @var DataPublication[] */
    public array $dataPublications;

    public string $currentUrl;

    public LengthAwarePaginator $paginator;

    /**
     * Create a new class instance.
     */
    public function __construct(PackageSearchResponse $response, int $page, int $pageSize, string $currentUrl)
    {

        $this->page = $page;
        $this->pageSize = $pageSize;
        $this->dataPublications = $response->getResults(true);
        $this->totalCount = $response->getTotalResultsCount();
        $this->currentCount = count($this->dataPublications);
        $this->currentUrl = $currentUrl;
        $this->paginator = new LengthAwarePaginator($this->dataPublications, $this->totalCount, $this->pageSize, $this->page, [$currentUrl]);
    }

    public function getJsonResponse(JsonResource $dataPublicationResource): JsonResource
    {

        return $dataPublicationResource->additional([
            'success' => 'true',
            'messages' => [],
            'meta' => [
                'perPage' => $this->paginator->perPage(),
                'resultsCount' => $this->currentCount,
                'totalCount' => $this->paginator->total(),
                'currentPage' => $this->paginator->currentPage(),
                'lastPage' => $this->paginator->lastPage(),
            ],
            'links' => [
                'currentUrl' => $this->currentUrl,
            ],
        ]);
    }
}
