<?php

namespace App\Http\Resources\V2\Elements;

use App\Http\Resources\V2\DataPublicationResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class FileCollection extends ResourceCollection
{
    private string $dataPublicationName;

    public function __construct($resource, string $dataPublicationName = '')
    {
        parent::__construct($resource);
        $this->dataPublicationName = $dataPublicationName;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        return $this->collection->map(fn ($player) => (new FileResource($player, $this->dataPublicationName)));
    }
}
