<?php

namespace App\Exports\Vocabs;

use App\Models\Vocabulary;
use EasyRdf\Graph;

class RdfExport
{
    public $vocabulary;

    public function __construct(Vocabulary $vocabulary)
    {
        $this->vocabulary = $vocabulary;
    }

    public function export($type = 'turtle')
    {
        $keywords = $this->vocabulary->keywords;
        $graph = new Graph;

        foreach ($keywords as $keyword) {
            $graph->addResource($keyword->uri, 'rdf:type', 'skos:Concept');

            $children = $keyword->getChildren();
            foreach ($children as $child) {
                $graph->add($keyword->uri, 'skos:narrower', $child->uri);
            }

            $parent = $keyword->parent;
            if ($parent) {
                $graph->add($keyword->uri, 'skos:broader', $parent->uri);
            }

            $graph->add($keyword->uri, 'rdfs:label', $keyword->label);
        }

        return $graph->serialise($type);
    }
}
