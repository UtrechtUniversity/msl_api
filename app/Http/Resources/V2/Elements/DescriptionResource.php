<?php

namespace App\Http\Resources\V2\Elements;

use Illuminate\Http\Resources\Json\JsonResource;

abstract class DescriptionResource extends JsonResource
{
    protected function addDescriptions(string $description, string $descriptionType)
    {
        return ['description' => $description, 'descriptionType' => $descriptionType];
    }
}
