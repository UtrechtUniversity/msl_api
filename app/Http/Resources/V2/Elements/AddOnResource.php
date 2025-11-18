<?php

namespace App\Http\Resources\V2\Elements;

use App\Http\Resources\V2\Helpers\Descriptions;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddOnResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return ['description' => new DescriptionResource(new Descriptions(genericDescription: $this->description)), 'type' => $this->type, 'group' => $this->group];
    }
}
