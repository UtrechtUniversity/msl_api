<?php

namespace App\Models\Ckan;

class RelatedIdentifier implements CkanArrayInterface
{
    public string $msl_related_identifier;

    public string $msl_related_identifier_type;

    public string $msl_related_identifier_relation_type;

    public string $msl_related_identifier_metadata_scheme;

    public string $msl_related_identifier_metadata_scheme_uri;

    public string $msl_related_identifier_metadata_scheme_type;

    public string $msl_related_identifier_resource_type_general;

    public function __construct($identifier, $identifierType, $relationType, $metadataScheme = '', $metadataSchemeUri = '', $metadataSchemeType = '', $resourceType = '')
    {
        $this->msl_related_identifier = $identifier;
        $this->msl_related_identifier_type = $identifierType;
        $this->msl_related_identifier_relation_type = $relationType;
        $this->msl_related_identifier_metadata_scheme = $metadataScheme;
        $this->msl_related_identifier_metadata_scheme_uri = $metadataSchemeUri;
        $this->msl_related_identifier_metadata_scheme_type = $metadataSchemeType;
        $this->msl_related_identifier_resource_type_general = $resourceType;
    }

    public function getDisplayInformation(): array{
        $dataList = [];
        if ($this->msl_related_identifier_type == 'DOI') {
            $dataList[] = "https://doi.org/".$this->msl_related_identifier;
        }else {
            $dataList[] = $this->msl_related_identifier;

            if($this->msl_related_identifier_type != ''){
                $dataList[] = $this->msl_related_identifier_type;
            }

            if($this->msl_related_identifier_relation_type != ''){
                $dataList[] = $this->msl_related_identifier_relation_type;
            }
        }
        return $dataList;
    }

    public function toCkanArray(): array
    {
        return (array) $this;
    }
}
