<?php

namespace App\Models\Ckan;

use App\Enums\Schemes\IdSchemes\PersonalIdScheme;

class NameIdentifier
{
    public string $msl_creator_name_identifier;

    public string $msl_creator_name_identifiers_scheme;

    public string $msl_creator_name_identifiers_uri;

    public function __construct(string $nameIdentifier, string $nameIdentifierScheme = '', string $nameIdentifierUri = '')
    {
        $this->msl_creator_name_identifier = $nameIdentifier;
        $this->msl_creator_name_identifiers_scheme = $nameIdentifierScheme;
        $this->msl_creator_name_identifiers_uri = $nameIdentifierUri;
    }

    private function normalizeCreatorIdentifier(): string
    {
        $creatorNameIdentifier = $this->msl_creator_name_identifier;
        $nameIdentifierScheme = $this->msl_creator_name_identifiers_scheme;
        if (str_starts_with($creatorNameIdentifier, 'http')) {

            return $creatorNameIdentifier;
        }
        $scheme = PersonalIdScheme::tryFromScheme($nameIdentifierScheme);
        if (! $scheme) {
            return $creatorNameIdentifier;
        }

        return $scheme->getUrlPrefix().$creatorNameIdentifier;
    }

    /**
     * @return array{isURL:bool, value:string}
     */
    public function getCreatorIdentifierWithMetadata(): array
    {
        $creatorNameIdentifier = $this->normalizeCreatorIdentifier();

        return ['isURL' => (bool) filter_var($creatorNameIdentifier, FILTER_VALIDATE_URL), 'value' => $creatorNameIdentifier];
    }
}
