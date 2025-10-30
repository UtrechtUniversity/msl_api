<?php

namespace App\Http\Resources\V2\Elements;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IdentifierResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return  [
            'nameIdentifier' => $this->msl_creator_name_identifier,
            'nameIdentifierScheme' => $this->msl_creator_name_identifiers_scheme,
            'nameIdentifierUri' => $this->msl_creator_name_identifiers_uri
        ];
    }
}
