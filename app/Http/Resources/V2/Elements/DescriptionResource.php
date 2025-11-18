<?php

namespace App\Http\Resources\V2\Elements;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

enum DescriptionType: string
{
    case ABSTRACT = 'Abstract';
    case METHODS = 'Methods';
    case SERIES_INFORMATION = 'SeriesInformation';
    case TABLE_OF_CONTENTS = 'TableOfContents';
    case TECHNICAL_INFO = 'TechnicalInfo';
    case OTHER = 'Other';
    case GENERIC = 'Description';
    case GENERICHTML = 'Description in HTML';
}
class DescriptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    private function addDescriptions(string $description, string $descriptionType)
    {
        return ['description' => $description, 'descriptionType' => $descriptionType];
    }

    public function toArray(Request $request): array
    {

        $descriptions = [];
        $abstract = $this->abstract;
        if ($abstract) {
            $descriptions[] = $this->addDescriptions($abstract, DescriptionType::ABSTRACT->value);
        }
        $methods = $this->methods;
        if ($methods) {
            $descriptions[] = $this->addDescriptions($methods, DescriptionType::METHODS->value);
        }
        $seriesInformation = $this->seriesInformation;
        if ($seriesInformation) {
            $descriptions[] = $this->addDescriptions($seriesInformation, DescriptionType::SERIES_INFORMATION->value);
        }
        $tableOfContents = $this->tableOfContents;
        if ($tableOfContents) {
            $descriptions[] = $this->addDescriptions($tableOfContents, DescriptionType::TABLE_OF_CONTENTS->value);
        }
        $technicalInfo = $this->technicalInfo;
        if ($technicalInfo) {
            $descriptions[] = $this->addDescriptions($technicalInfo, DescriptionType::TECHNICAL_INFO->value);
        }
        $other = $this->other;
        if ($other) {
            $descriptions[] = $this->addDescriptions($other, DescriptionType::OTHER->value);
        }

        $generic = $this->genericDescription;
        if ($generic) {
            $descriptions[] = $this->addDescriptions($generic, DescriptionType::GENERIC->value);
        }

        $genericHTML = $this->genericDescriptionHtml;
        if ($genericHTML) {
            $descriptions[] = $this->addDescriptions($genericHTML, DescriptionType::GENERICHTML->value);
        }

        return $descriptions;
    }
}
