<?php

namespace App\Http\Resources\V2\Elements;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'date' => $this->msl_date_date,
            'dateType' => $this->msl_date_type,
            'dateInformation' => $this->msl_date_information,

        ];
    }
}
