<?php
namespace App\Models\Ckan;

class NameIdentifier
{

    public string $msl_creator_name_identifier;

    public string $msl_creator_name_identifiers_scheme;

    public string $msl_creator_name_identifiers_uri;


    public function __construct(string $nameIdentifier, string $nameIdentifierScheme = "", string $nameIdentifierUri = "")
    {
        $this->msl_creator_name_identifier = $nameIdentifier;
        $this->msl_creator_name_identifiers_scheme = $nameIdentifierScheme;
        $this->msl_creator_name_identifiers_uri = $nameIdentifierUri;
    }

    
}