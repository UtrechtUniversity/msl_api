<?php

namespace App\Http\Resources\V2;

use App\Enums\SubDomains\EndpointContext;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DataPublicationCollection extends ResourceCollection
{
    private EndpointContext $context;

    public function setContext(EndpointContext $context): self
    {
        $this->context = $context;

        return $this;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return $this->collection->map(fn ($resource) =>
            ((new DataPublicationResource($resource))->setContext($this->context)->setIncludesGeoJson(false))
        );
    }
}
