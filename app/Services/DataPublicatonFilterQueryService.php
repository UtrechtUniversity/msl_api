<?php

namespace App\Services;

use App\Models\Vocabulary;

class DataPublicatonFilterQueryService
{
    public function getQueryTerms(string $group): array
    {
        if(!in_array($group, ['selection_group_1', 'selection_group_2', 'selection_group_3'])) {
            throw new \Exception('Invalid group');
        }

        $vocabularies = Vocabulary::where('version', config('vocabularies.vocabularies_current_version'))->get();
        $terms = [];

        foreach ($vocabularies as $vocabulary) {
            $keywords = $vocabulary->keywords->where($group, true);

            foreach ($keywords as $keyword) {
                foreach ($keyword->keywordSearch as $keywordSearch) {
                    if (! $keywordSearch->exclude_abstract_mapping) {
                        $terms[] = $this->createKeywordSearchRegex($keywordSearch->search_value);
                    }
                }
            }
        }

        return array_unique($terms);
    }

    private function createKeywordSearchRegex(string $searchValue): string
    {
        $term = $searchValue;

        $term = str_replace('(', '\\\\(', $term);
        $term = str_replace(')', '\\\\)', $term);
        $term = str_replace('.', '\\\\.', $term);
        $term = str_replace('*', '\\\\*', $term);

        if (str_ends_with($searchValue, ',')) {
            return '"\\\\b'.$term.'"';
        }

        return '"\\\\b'.$term.'\\\\b"';
    }
}
