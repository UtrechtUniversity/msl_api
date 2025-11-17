<?php

namespace App\Http\Resources\V2\Elements;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FundingReferenceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'funderName' => $this->msl_funding_reference_funder_name,
            'funderIdentifier' => $this->msl_funding_reference_funder_identifier,
            'funderIdentifierType' => $this->msl_funding_reference_funder_identifier_type,
            'schemeUri' => $this->msl_funding_reference_scheme_uri,
            'awardNumber' => $this->msl_funding_reference_award_number,
            'awardUri' => $this->msl_funding_reference_award_uri,
            'awardTitle' => $this->msl_funding_reference_award_title,
        ];
    }
}
