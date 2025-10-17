<?php

namespace App\Models\Ckan;

class Right implements CkanArrayInterface
{
    public string $msl_right;

    public string $msl_right_uri;

    public string $msl_right_identifier;

    public string $msl_right_identifier_scheme;

    public string $msl_right_scheme_uri;

    public function __construct($right, $uri = '', $identifier = '', $identifierScheme = '', $schemeUri = '')
    {
        $this->msl_right = $right;
        $this->msl_right_uri = $uri;
        $this->msl_right_identifier = $identifier;
        $this->msl_right_identifier_scheme = $identifierScheme;
        $this->msl_right_scheme_uri = $schemeUri;
    }

    public function toCkanArray(): array
    {
        return (array) $this;
    }
}
