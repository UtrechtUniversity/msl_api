<?php

namespace App\Http\Resources\V2\Elements;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AffiliationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->msl_creator_affiliation_name,
            'affiliationIdentifier' => $this->msl_creator_affiliation_identifier,
            'affiliationIdentifierScheme' => $this->msl_creator_affiliation_identifier_scheme,
            'schemeUri' => $this->msl_creator_affiliation_scheme_uri,
        ];
    }
}
