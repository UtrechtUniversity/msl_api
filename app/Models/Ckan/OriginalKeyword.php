<?php

namespace App\Models\Ckan;

class OriginalKeyword implements CkanArrayInterface
{
    public string $msl_original_keyword_label;

    public string $msl_original_keyword_uri;

    public string $msl_original_keyword_vocab_uri;

    public function __construct(string $label, string $uri, string $vocabUri)
    {
        $this->msl_original_keyword_label = $label;
        $this->msl_original_keyword_uri = $uri;
        $this->msl_original_keyword_vocab_uri = $vocabUri;
    }

    public function toCkanArray(): array
    {
        return (array) $this;
    }
}
