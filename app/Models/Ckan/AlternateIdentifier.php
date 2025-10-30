<?php

namespace App\Models\Ckan;

class AlternateIdentifier implements CkanArrayInterface
{
    public string $msl_alternate_identifier;

    public string $msl_alternate_identifier_type;

    public function __construct($identifier, $type)
    {
        $this->msl_alternate_identifier = $identifier;
        $this->msl_alternate_identifier_type = $type;
    }

    public function toCkanArray(): array
    {
        return (array) $this;
    }
}
