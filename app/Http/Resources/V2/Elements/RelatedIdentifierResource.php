<?php

namespace App\Http\Resources\V2\Elements;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RelatedIdentifierResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'relatedIdentifier' => $this->msl_related_identifier,
            'relatedIdentifierType' => $this->msl_related_identifier_type,
            'relationType' => $this->msl_related_identifier_relation_type,
            'relatedMetadataScheme' => $this->msl_related_identifier_metadata_scheme,
            'schemeUri' => $this->msl_related_identifier_metadata_scheme_uri,
            'schemeType' => $this->msl_related_identifier_metadata_scheme_type,
            'resourceTypeGeneral' => $this->msl_related_identifier_resource_type_general,
        ];
    }
}
