<?php

namespace App\Http\Resources\V2\Helpers;

class Descriptions
{
    // Descriptions for data-publications
    public string $abstract;

    public string $methods;

    public string $seriesInformation;

    public string $tableOfContents;

    public string $technicalInfo;

    public string $other;

    // Descriptions for facilities
    public string $genericDescription;

    public string $genericDescriptionHtml;

    public function __construct(
        string $abstract = '',
        string $methods = '',
        string $seriesInformation = '',
        string $tableOfContents = '',
        string $technicalInfo = '',
        string $other = '',
        string $genericDescription = '',
        string $genericDescriptionHtml = ''

    ) {
        $this->abstract = $abstract;
        $this->methods = $methods;
        $this->seriesInformation = $seriesInformation;
        $this->tableOfContents = $tableOfContents;
        $this->technicalInfo = $technicalInfo;
        $this->other = $other;
        $this->genericDescription = $genericDescription;
        $this->genericDescriptionHtml = $genericDescriptionHtml;
    }
}
