<?php

namespace App\Http\Resources\V2\Elements;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

enum DataPublicationsDescriptionType: string
{
    case ABSTRACT = 'Abstract';
    case METHODS = 'Methods';
    case SERIES_INFORMATION = 'SeriesInformation';
    case TABLE_OF_CONTENTS = 'TableOfContents';
    case TECHNICAL_INFO = 'TechnicalInfo';
    case OTHER = 'Other';
}
class DataPublicationsDescriptionResource extends DescriptionResource
{

    public function toArray(Request $request): array
    {

        $descriptions = [];
        $abstract = $this->abstract;
        if ($abstract) {
            $descriptions[] = $this->addDescriptions($abstract, DataPublicationsDescriptionType::ABSTRACT->value);
        }
        $methods = $this->methods;
        if ($methods) {
            $descriptions[] = $this->addDescriptions($methods, DataPublicationsDescriptionType::METHODS->value);
        }
        $seriesInformation = $this->seriesInformation;
        if ($seriesInformation) {
            $descriptions[] = $this->addDescriptions($seriesInformation, DataPublicationsDescriptionType::SERIES_INFORMATION->value);
        }
        $tableOfContents = $this->tableOfContents;
        if ($tableOfContents) {
            $descriptions[] = $this->addDescriptions($tableOfContents, DataPublicationsDescriptionType::TABLE_OF_CONTENTS->value);
        }
        $technicalInfo = $this->technicalInfo;
        if ($technicalInfo) {
            $descriptions[] = $this->addDescriptions($technicalInfo, DataPublicationsDescriptionType::TECHNICAL_INFO->value);
        }
        $other = $this->other;
        if ($other) {
            $descriptions[] = $this->addDescriptions($other, DataPublicationsDescriptionType::OTHER->value);
        };

        return $descriptions;
    }
}
