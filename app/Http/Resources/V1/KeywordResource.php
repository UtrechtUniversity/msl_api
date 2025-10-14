<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class KeywordResource extends JsonResource
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
            'name' => $this->value,
            'uri' => $this->uri,
            'synonyms' => KeywordSearchResource::collection($this->getSynonyms()),
            'parent' =>  ($this->hasParent() ? KeywordFlatResource::make($this->parent) : null),
            'children' => KeywordFlatResource::collection($this->getChildren()),
            'vocabulary' => VocabularyFlatResource::make($this->vocabulary)
        ];
    }
}
