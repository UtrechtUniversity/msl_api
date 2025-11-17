<?php

namespace App\Http\Resources\V2\Elements;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'subject' => $this->msl_tag_string,
            'schemeUri' => $this->msl_tag_scheme_uri,
            'valueUri' => $this->msl_tag_value_uri,
            'subjectScheme' => $this->msl_tag_subject_scheme,
            'classificationCode' => $this->msl_tag_classification_code,
            'EPOS_Uris' => $this->msl_tag_msl_uris,
        ];
    }
}
