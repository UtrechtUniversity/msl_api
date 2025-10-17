<?php

namespace App\Models\Ckan;

class EnrichedKeyword implements CkanArrayInterface
{
    public string $msl_enriched_keyword_label;

    public string $msl_enriched_keyword_uri;

    public string $msl_enriched_keyword_vocab_uri;

    public array $msl_enriched_keyword_associated_subdomains;

    public array $msl_enriched_keyword_match_locations;

    public array $msl_enriched_keyword_match_child_uris;

    public function __construct(string $label, string $uri = '', string $vocabUri = '', array $associatedSubdomains = [], array $matchLocations = [], array $matchChildUris = [])
    {
        $this->msl_enriched_keyword_label = $label;
        $this->msl_enriched_keyword_uri = $uri;
        $this->msl_enriched_keyword_vocab_uri = $vocabUri;
        $this->msl_enriched_keyword_associated_subdomains = $associatedSubdomains;
        $this->msl_enriched_keyword_match_locations = $matchLocations;
        $this->msl_enriched_keyword_match_child_uris = $matchChildUris;
    }

    public function toCkanArray(): array
    {
        return (array) $this;
    }
}
