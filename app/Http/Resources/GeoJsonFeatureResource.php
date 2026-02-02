<?php

namespace App\Http\Resources;

use App\GeoJson\Feature\Feature;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GeoJsonFeatureResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public Feature $feature;

    private string $title;

    private string $doi;

    public function __construct(Feature $feature, $dataPublication)
    {
        $this->title = $dataPublication->title;
        $this->doi = $dataPublication->msl_doi;
        $this->feature = $feature;
    }

    public function toArray(Request $request): array
    {
        // create an array
        return ['feature' => $this->feature, 'title' => $this->title, 'data_publication_doi' => $this->doi];
    }
}
