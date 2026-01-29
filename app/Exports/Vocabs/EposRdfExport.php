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
        $graph->addResource($this->convertVocabUriToEposUri($this->vocabulary->uri), 'rdf:type', 'skos:ConceptScheme');
        $graph->add($this->convertVocabUriToEposUri($this->vocabulary->uri), 'skos:prefLabel', $this->vocabulary->display_name);

        $topLevelKeywords = Keyword::where('vocabulary_id', $this->vocabulary->id)->where('level', 1)->get();
        foreach ($topLevelKeywords as $topLevelKeyword) {
            $graph->addResource($this->convertVocabUriToEposUri($this->vocabulary->uri), 'skos:hasTopConcept', $this->convertTermUriToEposUri($topLevelKeyword->uri));
        }

        $keywords = $this->vocabulary->keywords;

        foreach ($keywords as $keyword) {
            $graph->addResource($this->convertTermUriToEposUri($keyword->uri), 'rdf:type', 'skos:Concept');

            $children = $keyword->getChildren();
            foreach ($children as $child) {
                $graph->addResource($this->convertTermUriToEposUri($keyword->uri), 'skos:narrower', $this->convertTermUriToEposUri($child->uri));
            }

            $parent = $keyword->parent;
            if ($parent) {
                $graph->addResource($this->convertTermUriToEposUri($keyword->uri), 'skos:broader', $this->convertTermUriToEposUri($parent->uri));
            }

            $graph->add($this->convertTermUriToEposUri($keyword->uri), 'skos:prefLabel', $keyword->label);
            $graph->addResource($this->convertTermUriToEposUri($keyword->uri), 'owl:sameAs', $keyword->uri);
            $graph->addResource($this->convertTermUriToEposUri($keyword->uri), 'skos:inScheme', $this->convertVocabUriToEposUri($this->vocabulary->uri));
        }

        return $graph->serialise($type);
    }

    private function convertTermUriToEposUri($uri): string
    {
        $regex = '~https://epos-msl.uu.nl/voc/([^/]+)/1.3/([^"]+)~';

        preg_match_all($regex, $uri, $matches);
        return 'https://registry.epos-eu.org/ncl/FAIR/'.$matches[1][0].'/'.$matches[2][0];
    }

    private function convertVocabUriToEposUri($uri): string
    {
        $regex = '~https://epos-msl.uu.nl/voc/([^/]+)/1.3/~';

        preg_match_all($regex, $uri, $matches);
        return 'https://registry.epos-eu.org/ncl/FAIR/'.$matches[1][0];
    }
}
