<?php

namespace App\Exports\Vocabs;

use App\Models\Keyword;
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

        $graph->addResource($this->vocabulary->uri, 'rdf:type', 'skos:ConceptScheme');
        $graph->add($this->vocabulary->uri, 'skos:prefLabel', $this->vocabulary->display_name);

        $topLevelKeywords = Keyword::where('vocabulary_id', $this->vocabulary->id)->where('level', 1)->get();
        foreach ($topLevelKeywords as $topLevelKeyword) {
            $graph->addResource($this->vocabulary->uri, 'skos:hasTopConcept', $topLevelKeyword->uri);
        }

        foreach ($keywords as $keyword) {
            $graph->addResource($keyword->uri, 'rdf:type', 'skos:Concept');

            $children = $keyword->getChildren();
            foreach ($children as $child) {
                $graph->addResource($keyword->uri, 'skos:narrower', $child->uri);
            }

            $parent = $keyword->parent;
            if ($parent) {
                $graph->addResource($keyword->uri, 'skos:broader', $parent->uri);
            }

            if ($keyword->level == 1) {
                $graph->addResource($keyword->uri, 'skos:topConceptOf', $this->vocabulary->uri);
            }

            $graph->add($keyword->uri, 'skos:prefLabel', $keyword->label);

            if($keyword->external_uri !== "") {
                $graph->addResource($keyword->uri, 'rdfs:seeAlso', $keyword->external_uri);
                $graph->addResource($keyword->uri, 'skos:exactMatch', $keyword->external_uri);
                if($keyword->external_vocab_scheme !== "") {
                    $graph->add($keyword->uri, 'dc:source', $keyword->external_vocab_scheme);
                }
            }

            $graph->addResource($keyword->uri, 'skos:inScheme', $this->vocabulary->uri);
        }

        return $graph->serialise($type);
    }
}
