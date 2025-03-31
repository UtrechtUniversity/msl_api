<?php
namespace App\Models\Ckan;

class Tag implements CkanArrayInterface
{
    public string $msl_tag_string;

    public string $msl_tag_scheme_uri;

    public string $msl_tag_value_uri;

    public string $msl_tag_subject_scheme;

    public array $msl_tag_msl_uris;

    public function __construct($tagString, $schemeUri = "", $valueUri = "", $subjectScheme = "", $mslUris = [])
    {
        $this->msl_tag_string = $tagString;
        $this->msl_tag_scheme_uri = $schemeUri;
        $this->msl_tag_value_uri = $valueUri;
        $this->msl_tag_subject_scheme = $subjectScheme;
        $this->msl_tag_msl_uris = $mslUris;
    }

    public function toCkanArray(): array
    {        
        return (array) $this;
    }
}