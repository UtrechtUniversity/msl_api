<?php

namespace App\Exports\Vocabs;

use App\Models\Keyword;
use App\Models\Vocabulary;

class JsonExport
{
    public Vocabulary $vocabulary;

    public function __construct(Vocabulary $vocabulary)
    {
        $this->vocabulary = $vocabulary;
    }

    public function export(): false|string
    {
        $topKeywords = $this->vocabulary->keywords->where('level', 1);

        $tree = $this->getTree($topKeywords);

        return json_encode($tree, JSON_PRETTY_PRINT);
    }

    private function getSynonyms(Keyword $keyword): array
    {
        $synonyms = $keyword->getSynonyms();
        $return = [];

        foreach ($synonyms as $synonym) {
            $item = [
                'value' => $synonym->search_value,
            ];

            $return[] = $item;
        }

        return $return;
    }

    private function getChildren(Keyword $keyword): array
    {
        $children = $keyword->getChildren();
        $tree = $this->getTree($children);

        return $tree;
    }

    /**
     * @param iterable<int, Keyword> $Keywords
     * @return array
     */
    private function getTree(iterable $Keywords): array
    {
        $tree = [];
        foreach ($Keywords as $topKeyword) {
            $element = [
                'uri' => $topKeyword->uri,
                'vocab_uri' => $this->vocabulary->uri,
                'value' => $topKeyword->value,
                'label' => $topKeyword->label,
                'synonyms' => $this->getSynonyms($topKeyword),
                'children' => $this->getChildren($topKeyword),
                'external_uri' => $topKeyword->external_uri,
                'external_vocab_scheme' => $topKeyword->external_vocab_scheme,
            ];

            $tree[] = $element;
        }
        return $tree;
    }
}
