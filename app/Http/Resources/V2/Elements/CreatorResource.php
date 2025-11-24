<?php

namespace App\Http\Resources\V2\Elements;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CreatorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->msl_creator_name,
            'fullName' => $this->resource->getFullName(),
            'nameType' => $this->msl_creator_name_type,
            'givenName' => $this->msl_creator_given_name,
            'familyName' => $this->msl_creator_family_name,
            'nameIdentifiers' => IdentifierResource::collection($this->nameIdentifiers),
            'affiliation' => AffiliationResource::collection($this->affiliations),
        ];
    }
}
