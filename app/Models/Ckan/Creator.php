<?php

namespace App\Models\Ckan;

class Creator implements CkanArrayInterface
{
    public string $msl_creator_name;

    public string $msl_creator_given_name;

    public string $msl_creator_family_name;

    public string $msl_creator_name_type;

    public array $nameIdentifiers = [];

    public array $affiliations = [];

    public function __construct(string $name, string $givenName = '', string $familyName = '', string $nameType = '')
    {
        $this->msl_creator_name = $name;
        $this->msl_creator_given_name = $givenName;
        $this->msl_creator_family_name = $familyName;
        $this->msl_creator_name_type = $nameType;
    }

    public function getFullName(): string
    {
        if (strlen($this->msl_creator_name > 0)) {
            return $this->msl_creator_name;
        }

        return $this->msl_creator_given_name.' '.$this->msl_creator_family_name;
    }

    public function getAffilitationNames(): array
    {
        $affiliationNames = [];
        foreach ($this->affiliations as $affilitation) {
            $affiliationNames[] = $affilitation->msl_creator_affiliation_name;
        }

        return $affiliationNames;
    }

    public function addNameIdentifier(NameIdentifier $nameIdentifier): void
    {
        $this->nameIdentifiers[] = $nameIdentifier;
    }

    public function addAffiliation(Affiliation $affiliation): void
    {
        $this->affiliations[] = $affiliation;
    }

    public function toCkanArray(): array
    {
        $nameIdentifiers = [];
        $nameIdentifierSchemes = [];
        $nameIdentifierUris = [];

        foreach ($this->nameIdentifiers as $nameIdentifier) {
            $nameIdentifiers[] = $nameIdentifier->msl_creator_name_identifier;
            $nameIdentifierSchemes[] = $nameIdentifier->msl_creator_name_identifiers_scheme;
            $nameIdentifierUris[] = $nameIdentifier->msl_creator_name_identifiers_uri;
        }

        $affiliationNames = [];
        $affiliationIdentifiers = [];
        $affiliationIdentifierSchemes = [];
        $affiliationSchemeUris = [];

        foreach ($this->affiliations as $affiliation) {
            $affiliationNames[] = $affiliation->msl_creator_affiliation_name;
            $affiliationIdentifiers[] = $affiliation->msl_creator_affiliation_identifier;
            $affiliationIdentifierSchemes[] = $affiliation->msl_creator_affiliation_identifier_scheme;
            $affiliationSchemeUris[] = $affiliation->msl_creator_affiliation_scheme_uri;
        }

        return [
            'msl_creator_name' => $this->msl_creator_name,
            'msl_creator_given_name' => $this->msl_creator_given_name,
            'msl_creator_family_name' => $this->msl_creator_family_name,
            'msl_creator_name_type' => $this->msl_creator_name_type,
            'msl_creator_name_identifiers' => $nameIdentifiers,
            'msl_creator_name_identifiers_schemes' => $nameIdentifierSchemes,
            'msl_creator_name_identifiers_uris' => $nameIdentifierUris,
            'msl_creator_affiliations_names' => $affiliationNames,
            'msl_creator_affiliation_identifiers' => $affiliationIdentifiers,
            'msl_creator_affiliation_identifier_schemes' => $affiliationIdentifierSchemes,
            'msl_creator_affiliation_scheme_uris' => $affiliationSchemeUris,
        ];
    }
}
