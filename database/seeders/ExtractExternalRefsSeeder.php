<?php

namespace Database\Seeders;

use App\Enums\Schemes\VocabSchemes;
use App\Models\Keyword;
use Exception;
use Illuminate\Database\Seeder;

class ExtractExternalRefsSeeder extends Seeder
{
    private function doesExternalUriExist(Keyword $keyword, string $definitionLink): bool
    {

        $externalUri = $keyword->external_uri;
        if ($externalUri) {
            if ($externalUri !== $definitionLink) {
                throw new Exception("There is already a value for external_uri in {$keyword->uri}: $externalUri.\nBut definition link is: {$definitionLink}");
            }

            return true;
        }

        return false;
    }

    // private function (){}

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

                if ($this->doesExternalUriExist($keyword, $definitionLink)) {
                    $keyword->update(['external_vocab_scheme' => VocabSchemes::GEOSCIML->value]);

                    continue;
                }
                $keyword->update(['external_uri' => $definitionLink, 'external_vocab_scheme' => VocabSchemes::GEOSCIML->value]);
                // TODO do I need more accurate checks?
                if ($definition) {
                    $keyword->update(['notes' => $definition]);
                }

                continue;
            }
            if (str_starts_with($definitionLink, VocabSchemes::MINDAT->getUrlPrefix())) {
                if ($this->doesExternalUriExist($keyword, $definitionLink)) {
                    $keyword->update(['external_vocab_scheme' => VocabSchemes::MINDAT->value]);

                    continue;
                }
                $keyword->update(['external_uri' => $definitionLink, 'external_vocab_scheme' => VocabSchemes::MINDAT->value]);
                if ($definition) {
                    $keyword->update(['notes' => $definition]);
                }

                continue;
            }
            if (str_starts_with($definitionLink, VocabSchemes::INSPIRE->getUrlPrefix())) {
                if ($this->doesExternalUriExist($keyword, $definitionLink)) {
                    $keyword->update(['external_vocab_scheme' => VocabSchemes::INSPIRE->value]);

                    continue;
                }
                // Find and replace the first occurence of https
                $pos = strpos($definitionLink, 'https');
                if ($pos === false) {
                    throw new Exception("Position of 'https' could not be found. This is a bug.");
                }
                $cleanedDefinitionLink = substr_replace($definitionLink, 'http', $pos, strlen('https'));

                $keyword->update(['external_uri' => $cleanedDefinitionLink, 'external_vocab_scheme' => VocabSchemes::INSPIRE->value]);
                if ($definition) {
                    $keyword->update(['notes' => $definition]);
                }

                continue;
            }

            if (str_starts_with($definitionLink, VocabSchemes::INSPIRE->getUrlPrefix(isHttpProtocol: true))) {
                if ($this->doesExternalUriExist($keyword, $definitionLink)) {
                    $keyword->update(['external_vocab_scheme' => VocabSchemes::INSPIRE->value]);

                    continue;
                }
                $keyword->update(['external_uri' => $definitionLink, 'external_vocab_scheme' => VocabSchemes::INSPIRE->value]);
                if ($definition) {
                    $keyword->update(['notes' => $definition]);
                }

                continue;
            }

            $notes = implode(' ', array_filter([$definition, $definitionLink], fn ($v) => $v));
            if ($notes) {
                $keyword->update(['notes' => $notes]);
            }
        }
    }
}
