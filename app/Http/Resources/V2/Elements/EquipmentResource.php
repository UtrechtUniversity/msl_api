<?php

namespace App\Http\Resources\V2\Elements;

use App\Http\Resources\V2\Helpers\Descriptions;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EquipmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $genericDescription = $this->description ?? '';
        $genericDescriptionHtml = $this->description_html ?? '';

        return [
            'name' => $this->name,
            'descriptions' => new DescriptionResource(new Descriptions(genericDescription: $genericDescription, genericDescriptionHtml: $genericDescriptionHtml)),
            'domain' => $this->domain_name,
            'category' => $this->category_name,
            'type' => $this->type_name,
            'group' => $this->group_name,
            'brand' => $this->brand,
            'addOns' => AddOnResource::collection($this->laboratory_equipment_addons),
        ];
    }
}
