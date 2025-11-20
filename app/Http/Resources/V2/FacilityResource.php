<?php

namespace App\Http\Resources\V2;

use App\GeoJson\Feature\Feature;
use App\GeoJson\Geometry\Point;
use App\Http\Resources\V2\Elements\DescriptionResource;
use App\Http\Resources\V2\Elements\EquipmentResource;
use App\Http\Resources\V2\Helpers\Descriptions;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FacilityResource extends JsonResource
{
    private function getGeoJsonFromPoint(): ?array
    {
        $x = is_numeric($this->longitude) ? (float) $this->longitude : null;
        $y = is_numeric($this->latitude) ? (float) $this->latitude : null;
        $z = is_numeric($this->altitude) ? (float) $this->altitude : null;

        if (! ($x && $y)) {
            return null;
        }
        $point = new Point($x, $y, $z);

        return (new Feature(geometry: $point, properties: ['city' => $this->address_city, 'country' => $this->address_country_name]))->jsonSerialize();
    }

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
            'title' => $this->name,
            'portalLink' => route('lab-detail', ['id' => $this->msl_identifier]),
            'organisation' => $this->laboratoryOrganization->name,
            'domain' => $this->fast_domain_name,
            'descriptions' => new DescriptionResource(new Descriptions(genericDescription: $genericDescription, genericDescriptionHtml: $genericDescriptionHtml)),
            'equipment' => EquipmentResource::collection($this->laboratoryEquipment),
            'geojson' => $this->getGeoJsonFromPoint(),
            'contact' => route('laboratory-contact-person', $this->msl_identifier),
        ];
    }
}
