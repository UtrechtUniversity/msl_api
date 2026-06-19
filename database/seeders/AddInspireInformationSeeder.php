<?php

namespace Database\Seeders;

use App\Enums\Schemes\VocabSchemes;
use App\Models\Keyword;
use Exception;
use Illuminate\Database\Seeder;

class AddInspireInformationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fileString = file_get_contents(base_path('database/seeders/datafiles/vocabularies/geologicalsettingConverted.json'));
        $vocabEntries = json_decode($fileString, true);

        foreach ($vocabEntries as $entry) {
            $hyperLinkInEntry = $entry['hyperlink'];
            if (str_starts_with($hyperLinkInEntry, VocabSchemes::INSPIRE->getUrlPrefix())) {

                $uriInEntry = $entry['uri'];
                $keyword = Keyword::where('uri', $uriInEntry)->first();
                if (! $keyword) {
                    throw new Exception('Not keyword found with uri '.$uriInEntry);
                }
                $extractedDefLinkInKeyword = $keyword->extracted_definition_link;
                if ($extractedDefLinkInKeyword) {
                    if ($extractedDefLinkInKeyword !== $hyperLinkInEntry) {
                        throw new Exception('Two values were found for the same uri " '.$uriInEntry.'" : '.$extractedDefLinkInKeyword.' and '.$hyperLinkInEntry);
                    }

                    continue;
                }
                $keyword->extracted_definition_link = $hyperLinkInEntry;
                $keyword->save();
            }
        }
    }
}
