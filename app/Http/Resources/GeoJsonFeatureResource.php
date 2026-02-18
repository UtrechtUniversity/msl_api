<?php

namespace App\Http\Resources;

use App\GeoJson\Feature\Feature;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GeoJsonFeatureResource extends JsonResource
{
    public Feature $feature;

    private string $title;

    private string $doi;

    private string $portalLink;

    public function __construct(Feature $feature, $dataPublication)
    {
        $this->title = $dataPublication->title;
        $this->doi = $dataPublication->msl_doi;
        $this->feature = $feature;
        $this->portalLink = route('data-publication-detail', ['id' => $dataPublication->name]);
    }

    public function toArray(Request $request): array
    {
        return [
            'feature' => $this->feature, 
            'title' => $this->title, 
            'data_publication_doi' => $this->doi,
            'portalLink' => $this->portalLink,
        ];
    }
}
