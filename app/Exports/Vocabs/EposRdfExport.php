<?php

namespace App\Exports\Vocabs;

use App\Models\Keyword;
use App\Models\Vocabulary;
use EasyRdf\Graph;
use EasyRdf\RdfNamespace;

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

        RdfNamespace::set('reg', 'http://purl.org/linked-data/registry#');
        RdfNamespace::set('ui', 'http://purl.org/linked-data/registry-ui#');

        // Create concept scheme top level class
        $graph->addResource($this->convertVocabUriToEposUri($this->vocabulary), 'rdf:type', 'skos:ConceptScheme');
        $graph->add($this->convertVocabUriToEposUri($this->vocabulary), 'skos:prefLabel', $this->vocabulary->display_name);

        $topLevelKeywords = Keyword::where('vocabulary_id', $this->vocabulary->id)->where('level', 1)->get();
        foreach ($topLevelKeywords as $topLevelKeyword) {
            $graph->addResource($this->convertVocabUriToEposUri($this->vocabulary), 'skos:hasTopConcept', $this->convertTermUriToEposUri($topLevelKeyword->uri, $this->vocabulary));
        }

        $graph->addResource($this->convertVocabUriToEposUri($this->vocabulary), 'reg:inverseMembershipPredicate', 'skos:inScheme');
        $graph->addResource($this->convertVocabUriToEposUri($this->vocabulary), 'ui:hierarchyChildProperty', 'skos:narrower');
        $graph->addResource($this->convertVocabUriToEposUri($this->vocabulary), 'ui:hierarchyRootProperty', 'skos:topConceptOf');
        $graph->addResource($this->convertVocabUriToEposUri($this->vocabulary), 'ldp:isMemberOfRelation', 'skos:inScheme');


        $keywords = $this->vocabulary->keywords;

        foreach ($keywords as $keyword) {
            $graph->addResource($this->convertTermUriToEposUri($keyword->uri, $this->vocabulary), 'rdf:type', 'skos:Concept');

            $children = $keyword->getChildren();
            foreach ($children as $child) {
                $graph->addResource($this->convertTermUriToEposUri($keyword->uri, $this->vocabulary), 'skos:narrower', $this->convertTermUriToEposUri($child->uri, $this->vocabulary));
            }

            $parent = $keyword->parent;
            if ($parent) {
                $graph->addResource($this->convertTermUriToEposUri($keyword->uri, $this->vocabulary), 'skos:broader', $this->convertTermUriToEposUri($parent->uri, $this->vocabulary));
            }

            if($keyword->level == 1) {
                $graph->addResource($this->convertTermUriToEposUri($keyword->uri, $this->vocabulary), 'skos:topConceptOf', $this->convertVocabUriToEposUri($this->vocabulary));
            }

            $graph->add($this->convertTermUriToEposUri($keyword->uri, $this->vocabulary), 'skos:prefLabel', $keyword->label);
            $graph->addResource($this->convertTermUriToEposUri($keyword->uri, $this->vocabulary), 'owl:sameAs', $keyword->uri);
            $graph->addResource($this->convertTermUriToEposUri($keyword->uri, $this->vocabulary), 'skos:inScheme', $this->convertVocabUriToEposUri($this->vocabulary));
        }

        return $graph->serialise($type);
    }

    private function convertTermUriToEposUri(string $uri, Vocabulary $vocabulary): string
    {
        $regex = '~https://epos-msl.uu.nl/voc/([^/]+)/' . $vocabulary->version . '/([^"]+)~';

        if(preg_match_all($regex, $uri, $matches))
        {
            return 'https://registry.epos-eu.org/ncl/FAIR-Incubator/tcs-MSL/'.$matches[1][0].'/'.$matches[2][0];
        }

        return $uri;
    }

    private function convertVocabUriToEposUri(Vocabulary $vocabulary): string
    {
        $regex = '~https://epos-msl.uu.nl/voc/([^/]+)/' . $vocabulary->version . '/~';

        if(preg_match_all($regex, $vocabulary->uri, $matches)) {
            return 'https://registry.epos-eu.org/ncl/FAIR-Incubator/tcs-MSL/'.$matches[1][0];
        }

        return $vocabulary->uri;
    }
}
