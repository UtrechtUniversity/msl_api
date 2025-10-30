<?php

namespace App\Http\Resources\V2\Elements;

use Illuminate\Http\Resources\Json\JsonResource;

class RightResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'rights' => $this->msl_right,
            'rightsUri' => $this->msl_right_uri,
            'rightsIdentifier' => $this->msl_right_identifier,
            'rightsIdentifierScheme' => $this->msl_right_identifier_scheme,
            'rightsSchemeUri' => $this->msl_right_scheme_uri

        ];
    }
}
