<?php

namespace App\Http\Resources\V2\Elements;

use Illuminate\Http\Request;

enum FacilitiesDescriptionType: string
{
    case GENERIC = 'Description';
    case GENERIC_HTML = 'Description in HTML';
}

class DescriptionForFacilitiesResource extends DescriptionResource
{
    public function toArray(Request $request): array
    {

        $descriptions = [];

        $generic = $this->genericDescription;
        if ($generic) {
            $descriptions[] = $this->addDescriptions($generic, FacilitiesDescriptionType::GENERIC->value);
        }

        $genericHTML = $this->genericDescriptionHtml;
        if ($genericHTML) {
            $descriptions[] = $this->addDescriptions($genericHTML, FacilitiesDescriptionType::GENERIC_HTML->value);
        }

        return $descriptions;
    }
}
