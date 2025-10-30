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
        $abstract = $this->msl_description_abstract;
        if ($abstract) {
            $descriptions[] = $this->addDescriptions($abstract, DescriptionType::ABSTRACT->value);
        }
        $methods = $this->msl_description_methods;
        if ($methods) {
            $descriptions[] = $this->addDescriptions($methods, DescriptionType::METHODS->value);
        }
        $seriesInformation = $this->msl_description_series_information;
        if ($seriesInformation) {
            $descriptions[] = $this->addDescriptions($seriesInformation, DescriptionType::SERIES_INFORMATION->value);
        }
        $tableOfContents = $this->msl_description_table_of_contents;
        if ($tableOfContents) {
            $descriptions[] = $this->addDescriptions($tableOfContents, DescriptionType::TABLE_OF_CONTENTS->value);
        }
        $technicalInfo = $this->msl_description_technical_info;
        if ($technicalInfo) {
            $descriptions[] = $this->addDescriptions($technicalInfo, DescriptionType::TECHNICAL_INFO->value);
        }
        $other = $this->msl_description_other;
        if ($other) {
            $descriptions[] = $this->addDescriptions($other, DescriptionType::OTHER->value);
        }

        return $descriptions;
    }
}
