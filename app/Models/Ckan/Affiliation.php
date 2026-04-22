<?php

namespace App\Models\Ckan;

use App\Enums\Schemes\IdSchemes\OrganizationIdScheme;

class Affiliation
{
    public string $msl_creator_affiliation_name;

    public string $msl_creator_affiliation_identifier;

    public string $msl_creator_affiliation_identifier_scheme;

    public string $msl_creator_affiliation_scheme_uri;

    public function __construct(string $name, string $identifier = '', string $identifierScheme = '', string $schemeUri = '')
    {
        $this->msl_creator_affiliation_name = $name;
        $this->msl_creator_affiliation_identifier = $identifier;
        $this->msl_creator_affiliation_identifier_scheme = $identifierScheme;
        $this->msl_creator_affiliation_scheme_uri = $schemeUri;
    }

    private function normalizeAffiliationIdentifier(): string
    {
        $affiliationIdentifier = $this->msl_creator_affiliation_identifier;
        $affiliationIdentifierScheme = $this->msl_creator_affiliation_identifier_scheme;
        if (str_starts_with($affiliationIdentifier, 'http')) {

            return $affiliationIdentifier;
        }
        $scheme = OrganizationIdScheme::tryFromScheme($affiliationIdentifierScheme);
        if (! $scheme) {
            return $affiliationIdentifier;
        }

        return $scheme->getUrlPrefix().$affiliationIdentifier;
    }

    /**
     * @return array{isURL:bool, value:string}
     */
    public function getAffilitiationIdentifierWithMetadata(): array
    {
        $affiliationIdentifier = $this->normalizeAffiliationIdentifier();

        return ['isURL' => (bool) filter_var($affiliationIdentifier, FILTER_VALIDATE_URL), 'value' => $affiliationIdentifier];
    }
}
