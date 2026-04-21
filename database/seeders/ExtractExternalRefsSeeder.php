<?php

namespace Database\Seeders;

use App\Enums\Schemes\VocabSchemes;
use App\Models\Keyword;
use Exception;
use Illuminate\Database\Seeder;

class ExtractExternalRefsSeeder extends Seeder
{
    private function throwExternalUriConflictError(string $keywordUri, string $externalUri, string $definitionLink)
    {

        throw new Exception("There is already a value for external_uri in {$keywordUri}: $externalUri.\nBut definition link is: {$definitionLink}");
    }

    private function doesExternalUriExist(Keyword $keyword, string $definitionLink): bool
    {

        $externalUri = $keyword->external_uri;
        if ($externalUri) {
            if ($externalUri !== $definitionLink) {
                $this->throwExternalUriConflictError(keywordUri: $keyword->uri, externalUri: $externalUri, definitionLink: $definitionLink);
            }

            return true;
        }

        return false;
    }

    private function updateExternalFields(Keyword $keyword, string $definitionLink, string $definition, VocabSchemes $vocabScheme): void
    {
        $keyword->update(['external_uri' => $definitionLink, 'external_vocab_scheme' => $vocabScheme->value]);
        if ($definition) {
            $keyword->update(['notes' => $definition]);
        }
    }

    private function processPerScheme(Keyword $keyword, string $definitionLink, string $definition, VocabSchemes $vocabScheme): void
    {
        if ($this->doesExternalUriExist($keyword, $definitionLink)) {
            $keyword->update(['external_vocab_scheme' => $vocabScheme]);

            return;
        }
        $this->updateExternalFields(
            keyword: $keyword,
            definition: $definition,
            definitionLink: $definitionLink,
            vocabScheme: $vocabScheme
        );
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $keywordEntries = Keyword::all();

        foreach ($keywordEntries as $keyword) {
            $definitionLink = $keyword->extracted_definition_link;
            $definition = $keyword->extracted_definition;

            if (str_starts_with($definitionLink, VocabSchemes::GEOSCIML->getUrlPrefix())) {
                $this->processPerScheme(
                    keyword: $keyword,
                    definition: $definition,
                    definitionLink: $definitionLink,
                    vocabScheme: VocabSchemes::GEOSCIML
                );

                continue;
            }
            if (str_starts_with($definitionLink, VocabSchemes::MINDAT->getUrlPrefix())) {
                $this->processPerScheme(
                    keyword: $keyword,
                    definition: $definition,
                    definitionLink: $definitionLink,
                    vocabScheme: VocabSchemes::MINDAT
                );

                continue;
            }
            if (str_starts_with($definitionLink, VocabSchemes::INSPIRE->getUrlPrefix(isHttpsProtocol: true))) {
                // We should update links even when they exist in external_uri!
                $externalUri = $keyword->external_uri;
                if ($externalUri && $externalUri !== $definitionLink) {
                    $this->throwExternalUriConflictError(keywordUri: $keyword->uri, externalUri: $externalUri, definitionLink: $definitionLink);
                }

                $pos = strpos($definitionLink, 'https');
                if ($pos === false) {
                    throw new Exception("Position of 'https' could not be found. This is a bug.");
                }
                $cleanedDefinitionLink = substr_replace($definitionLink, 'http', $pos, strlen('https'));

                // We have to update the extracted definition link with the correct http url
                $keyword->update(['extracted_definition_link' => $cleanedDefinitionLink]);
                $this->updateExternalFields(
                    keyword: $keyword,
                    definition: $definition,
                    definitionLink: $cleanedDefinitionLink,
                    vocabScheme: VocabSchemes::INSPIRE
                );

                continue;
            }

            if (str_starts_with($definitionLink, VocabSchemes::INSPIRE->getUrlPrefix())) {
                $this->processPerScheme(
                    keyword: $keyword,
                    definition: $definition,
                    definitionLink: $definitionLink,
                    vocabScheme: VocabSchemes::INSPIRE
                );

                continue;
            }
            // If not any relevant vocabulary links have been found, we should still populate 'notes' field
            $notes = implode(' ', array_filter([$definition, $definitionLink], fn ($v) => $v));
            if ($notes) {
                $keyword->update(['notes' => $notes]);
            }
        }
    }
}
