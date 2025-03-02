<?php
namespace App\Models\Ckan;

class Affiliation
{
    public string $msl_creator_affiliation_name;

    public string $msl_creator_affiliation_identifier;

    public string $msl_creator_affiliation_identifier_scheme;

    public string $msl_creator_affiliation_scheme_uri;
    
    public function __construct(string $name, string $identifier = "", string $identifierScheme = "", string $schemeUri = "")
    {
        $this->msl_creator_affiliation_name = $name;
        $this->msl_creator_affiliation_identifier = $identifier;
        $this->msl_creator_affiliation_identifier_scheme = $identifierScheme;
        $this->msl_creator_affiliation_scheme_uri = $schemeUri;
    }

}