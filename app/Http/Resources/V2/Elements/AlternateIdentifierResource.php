<?php

namespace App\Http\Resources\V2\Elements;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AlternateIdentifierResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'alternate_identifier' => $this->msl_alternate_identifier,
            'alternate_identifier_type' => $this->msl_alternate_identifier_type
        ];
    }
}
