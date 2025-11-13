<?php

namespace App\Models\Ckan;

class FundingReference implements CkanArrayInterface
{
    public string $msl_funding_reference_funder_name;

    public string $msl_funding_reference_funder_identifier;

    public string $msl_funding_reference_funder_identifier_type;

    public string $msl_funding_reference_scheme_uri;

    public string $msl_funding_reference_award_number;

    public string $msl_funding_reference_award_uri;

    public string $msl_funding_reference_award_title;

    public function __construct($funderName, $funderIdentifier = '', $funderIdentifierType = '', $schemeUri = '', $awardNumber = '', $awardUri = '', $awardTitle = '')
    {
        $this->msl_funding_reference_funder_name = $funderName;
        $this->msl_funding_reference_funder_identifier = $funderIdentifier;
        $this->msl_funding_reference_funder_identifier_type = $funderIdentifierType;
        $this->msl_funding_reference_scheme_uri = $schemeUri;
        $this->msl_funding_reference_award_number = $awardNumber;
        $this->msl_funding_reference_award_uri = $awardUri;
        $this->msl_funding_reference_award_title = $awardTitle;
    }

    public function toCkanArray(): array
    {
        return (array) $this;
    }
}
