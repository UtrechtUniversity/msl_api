<?php

namespace App\Exports\Vocabs;

use App\Models\Keyword;
use App\Models\Vocabulary;
use EasyRdf\Graph;

class EposRdfExport
{
    public $vocabulary;

    public function __construct(Vocabulary $vocabulary)
    {
        $this->vocabulary = $vocabulary;
    }

    public function export($type = 'turtle')
    {
        $graph = new Graph;

        // Create concept scheme top level class
        $graph->addResource($this->vocabulary->uri, 'rdf:type', 'skos:ConceptScheme');
        $graph->add($this->vocabulary->uri, 'skos:prefLabel', $this->vocabulary->display_name);

        $topLevelKeywords = Keyword::where('vocabulary_id', $this->vocabulary->id)->where('level', 1)->get();
        foreach ($topLevelKeywords as $topLevelKeyword) {
            $graph->add($this->vocabulary->uri, 'skos:hasTopConcept', $topLevelKeyword->uri);
        }

        $keywords = $this->vocabulary->keywords;

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

            $graph->add($keyword->uri, 'skos:prefLabel', $keyword->label);
        }

        return $graph->serialise($type);
    }
}
