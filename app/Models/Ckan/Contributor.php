<?php

namespace App\Models\Ckan;

class Contributor implements CkanArrayInterface
{
    public string $msl_contributor_name;

    public string $msl_contributor_type;

    public string $msl_contributor_given_name;

    public string $msl_contributor_family_name;

    public string $msl_contributor_name_type;

    /**
     * @var array<int,NameIdentifier>
     */
    public array $nameIdentifiers = [];

    /**
     * @var array<int,Affiliation>
     */
    public array $affiliations = [];

    public function __construct(string $name, string $type, string $givenName = '', string $familyName = '', string $nameType = '')
    {
        $this->msl_contributor_name = $name;
        $this->msl_contributor_type = $type;
        $this->msl_contributor_given_name = $givenName;
        $this->msl_contributor_family_name = $familyName;
        $this->msl_contributor_name_type = $nameType;
    }

    public function getFullName(): string
    {
        if (strlen($this->msl_contributor_name > 0)) {
            return $this->msl_contributor_name;
        }

        return $this->msl_contributor_given_name.' '.$this->msl_contributor_family_name;
    }

    public function getAffilitationNames(): array
    {
        return array_column($this->affiliations, 'msl_creator_affiliation_name');
    }

    /**
     * @return array<int,array{isURL:bool, value:string}>
     */
    public function getNameIdentifiersWithMetaData(): array
    {

        $nameIdentifiersWithMetadata = array_map(fn ($nameIdentifier) => (
            $nameIdentifier->getCreatorIdentifierWithMetadata()), $this->nameIdentifiers);

        return $nameIdentifiersWithMetadata;
    }

    /**
     * @return array{
     *      name: string,
     *      nameType: string,
     *      nameidentifiers: array<int,array{isURL:bool, value:string}>,
     *      affiliations: array{
     *          name:string,
     *          identifier:array{
     *              isURL:booelean,
     *              value:string
     *              }
     *          }
     *      }
     */
    public function getContributorInfo(): array
    {
        return [
            'name' => $this->getFullName(),
            'nameType' => $this->msl_contributor_name_type,
            'nameIdentifiers' => $this->getNameIdentifiersWithMetaData(),
            'affiliations' => array_map(function (Affiliation $affiliation) {
                return ['name' => $affiliation->msl_creator_affiliation_name, 'identifier' => $affiliation->getAffilitiationIdentifierWithMetadata()];
            }, $this->affiliations),
        ];
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
            'msl_contributor_name' => $this->msl_contributor_name,
            'msl_contributor_type' => $this->msl_contributor_type,
            'msl_contributor_given_name' => $this->msl_contributor_given_name,
            'msl_contributor_family_name' => $this->msl_contributor_family_name,
            'msl_contributor_name_type' => $this->msl_contributor_name_type,
            'msl_contributor_name_identifiers' => $nameIdentifiers,
            'msl_contributor_name_identifiers_schemes' => $nameIdentifierSchemes,
            'msl_contributor_name_identifiers_uris' => $nameIdentifierUris,
            'msl_contributor_affiliations_names' => $affiliationNames,
            'msl_contributor_affiliation_identifiers' => $affiliationIdentifiers,
            'msl_contributor_affiliation_identifier_schemes' => $affiliationIdentifierSchemes,
            'msl_contributor_affiliation_scheme_uris' => $affiliationSchemeUris,
        ];
    }
}
