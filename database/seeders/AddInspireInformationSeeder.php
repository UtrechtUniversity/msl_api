<?php

namespace Database\Seeders;

use App\Models\Keyword;
use Error;
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
            if (str_starts_with($hyperLinkInEntry, 'http://inspire.ec.europa.eu')) {
                $uriInEntry = $entry['uri'];
                $keyword = Keyword::where('uri', $uriInEntry)->first();
                if (! $keyword) {
                    throw new Error('Not keyword found with uri '.$uriInEntry);
                }
                $extractedDefLink = $keyword->extracted_definition_link;
                if ($extractedDefLink) {
                    if ($extractedDefLink === $hyperLinkInEntry) {
                        continue;
                    }
                    throw new Error('Two values were found for the same uri " '.$uriInEntry.'" : '.$extractedDefLink.' and '.$hyperLinkInEntry);
                }
                $keyword->extracted_definition_link = $hyperLinkInEntry;
                $keyword->save();
            }
        }
    }
}
