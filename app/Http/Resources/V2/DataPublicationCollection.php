<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Resources\Json\ResourceCollection;

class DataPublicationCollection extends ResourceCollection
{
    private $context;

    public function __construct($resource, $context = '')
    {
        parent::__construct($resource);
        $this->context = $context;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        return $this->collection->map(fn ($player) => (new DataPublicationResource($player, $this->context)));
    }
}
