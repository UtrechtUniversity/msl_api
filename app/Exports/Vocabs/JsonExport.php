<?php

namespace App\Exports\Vocabs;

use App\Models\Keyword;
use App\Models\Vocabulary;

class JsonExport
{
    public $vocabulary;

    public function __construct(Vocabulary $vocabulary)
    {
        $this->vocabulary = $vocabulary;
    }

    public function export()
    {
        $topKeywords = $this->vocabulary->keywords->where('level', 1);

        $tree = [];
        foreach ($topKeywords as $topKeyword) {
            $element = [
                'uri' => $topKeyword->uri,
                'vocab_uri' => $this->vocabulary->uri,
                'value' => $topKeyword->value,
                'label' => $topKeyword->label,
                'synonyms' => $this->getSynonyms($topKeyword),
                'children' => $this->getChildren($topKeyword),
            ];

            $tree[] = $element;
        }

        return json_encode($tree, JSON_PRETTY_PRINT);
    }

    private function getSynonyms(Keyword $keyword)
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

    private function getChildren(Keyword $keyword)
    {
        $children = $keyword->getChildren();
        $tree = [];

        foreach ($children as $child) {
            $childTree = [
                'uri' => $child->uri,
                'vocab_uri' => $this->vocabulary->uri,
                'value' => $child->value,
                'label' => $child->label,
                'synonyms' => $this->getSynonyms($child),
                'children' => $this->getChildren($child),
            ];

            $tree[] = $childTree;
        }

        return $tree;
    }
}
