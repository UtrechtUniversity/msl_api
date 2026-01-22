<?php

namespace App\Exports\Vocabs;

use App\Models\Keyword;
use App\Models\Vocabulary;
use EasyRdf\Graph;

class CombinedRdfExport
{
    public string $version;

    public function __construct(string $version)
    {
        $this->version = $version;
    }

    public function export($type = 'turtle')
    {
        $vocabularies = Vocabulary::where('version', $this->version)->get();
        $graph = new Graph();

        foreach ($vocabularies as $vocabulary) {
            $graph->addResource($vocabulary->uri, 'rdf:type', 'skos:ConceptScheme');

            $graph->add($vocabulary->uri, 'skos:prefLabel', $vocabulary->display_name);

            $topLevelKeywords = Keyword::where('vocabulary_id', $vocabulary->id)->where('level', 1)->get();
            foreach ($topLevelKeywords as $topLevelKeyword) {
                $graph->add($vocabulary->uri, 'skos:hasTopConcept', $topLevelKeyword->uri);
            }

            $keywords = $vocabulary->keywords;

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

                if($keyword->level === 1) {
                    $graph->add($keyword->uri, 'skos:topConceptOf', $vocabulary->uri);
                }

                $graph->add($keyword->uri, 'skos:inScheme', $vocabulary->uri);

                $graph->add($keyword->uri, 'skos:prefLabel', $keyword->label);
            }
        }

        return $graph->serialise($type);
    }
}
