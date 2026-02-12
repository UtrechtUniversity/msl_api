<?php

namespace App\Http\Resources;

use App\GeoJson\Feature\Feature;
use App\Models\Ckan\DataPublication;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GeoJsonFeatureResource extends JsonResource
{
    public Feature $feature;

    private string $title;

    private string $doi;

    public DataPublication $dataPublication;

    public function __construct(Feature $feature, DataPublication $dataPublication)
    {
        $this->title = $dataPublication->title;
        $this->doi = $dataPublication->msl_doi;
        $this->feature = $feature;
        $this->dataPublication = $dataPublication;
    }

    public function toArray(Request $request): array
    {
        return ['feature' => $this->feature, 'title' => $this->title, 'data_publication_doi' => $this->doi];
    }
}
