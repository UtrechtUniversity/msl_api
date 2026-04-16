<?php

namespace Database\Seeders;

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
            if (str_starts_with($definitionLink, 'http://resource.geosciml.org')) {

                if ($this->doesExternalUriExist($keyword, $definitionLink)) {
                    $keyword->update(['external_vocab_scheme' => 'geosciml']);

                    continue;
                }
                $keyword->update(['external_uri' => $definitionLink, 'external_vocab_scheme' => 'geosciml']);
                // TODO do I need more accurate checks?
                if ($definition) {
                    $keyword->update(['notes' => $definition]);
                }

                continue;
            }
            if (str_starts_with($definitionLink, 'https://www.mindat.org')) {
                if ($this->doesExternalUriExist($keyword, $definitionLink)) {
                    $keyword->update(['external_vocab_scheme' => 'mindat']);

                    continue;
                }
                $keyword->update(['external_uri' => $definitionLink, 'external_vocab_scheme' => 'mindat']);
                if ($definition) {
                    $keyword->update(['notes' => $definition]);
                }

                continue;
            }
            if (str_starts_with($definitionLink, 'https://inspire.ec.europa.eu')) {
                if ($this->doesExternalUriExist($keyword, $definitionLink)) {
                    $keyword->update(['external_vocab_scheme' => 'inspire']);

                    continue;
                }
                // Find and replace the first occurence of https
                $pos = strpos($definitionLink, 'https');
                if ($pos === false) {
                    throw new Exception("Position of 'https' could not be found. This is a bug.");
                }
                $cleanedDefinitionLink = substr_replace($definitionLink, 'http', $pos, strlen('https'));

                $keyword->update(['external_uri' => $cleanedDefinitionLink, 'external_vocab_scheme' => 'inspire']);
                if ($definition) {
                    $keyword->update(['notes' => $definition]);
                }

                continue;
            }

            if (str_starts_with($definitionLink, 'http://inspire.ec.europa.eu')) {
                if ($this->doesExternalUriExist($keyword, $definitionLink)) {
                    $keyword->update(['external_vocab_scheme' => 'inspire']);

                    continue;
                }
                $keyword->update(['external_uri' => $definitionLink, 'external_vocab_scheme' => 'inspire']);
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
