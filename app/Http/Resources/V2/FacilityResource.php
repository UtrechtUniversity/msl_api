<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FacilityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        // dd($this);
        return [
            'name' => $this->name,
            'portalLink' => '',
            'organisation' => '',
            // here we want to include the addons
            'equipment' => [],
            'description' => '',
            'descriptionHtml' => '',
            'domain' => '',
            // do we still want them after including geojson?
            'latitude' => '',
            'longitude' => '',
            'altitude' => '',
            'geojson' => [],
            'organization' => '',

            //             public $name = '';

            // public $description = '';

            // public $descriptionHtml = '';

            // public $domain = '';

            // public $latitude = '';

            // public $longitude = '';

            // public $altitude = '';

            // public $portalLink = '';

            // public $organization = '';

            // public $equipment = [];
        ];
    }
}
