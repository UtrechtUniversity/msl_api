<?php

namespace App\Http\Resources\V2\Elements;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContributorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->msl_contributor_name,
            'fullName' => $this->resource->getFullName(),
            'contributorType' => $this->msl_contributor_type,
            'nameType' => $this->msl_contributor_name_type,
            'givenName' => $this->msl_contributor_given_name,
            'familyName' => $this->msl_contributor_family_name,
            'nameIdentifiers' => IdentifierResource::collection($this->nameIdentifiers),
            'affiliation' => AffiliationResource::collection($this->affiliations)
        ];
    }
}
