<?php
namespace App\Exports;

use App\Models\Keyword;
use App\Models\Vocabulary;

class FilterTreeExport
{
    public function exportEquipment() {
        $fastVocab = Vocabulary::where('name', 'fast')->where('version', '1.0')->first();

        $tree = [
            [
                'text' => 'Equipment',
                'state' => [
                    'opened' => false,
                    'disabled' => false,
                    'selected' => false,
                    'checked' => false
                ],            
                'extra' => [
                    'type' => 'filter',
                    'url' => '',
                    'filterName' => '',
                    'filterValue' => ''
                ],
                'children' => $this->getVocabAsFilters($fastVocab->id, 'msl_original_keyword_uri', false, true)
            ],
            [
                'text' => 'Organization',
                'state' => [
                    'opened' => false,
                    'disabled' => false,
                    'selected' => false,
                    'checked' => false
                ],
                'extra' => [
                    'type' => 'filter',
                    'url' => '',
                    'filterName' => '',
                    'filterValue' => 'tru',
                    'includeFacet' => true,
                    'facetName' => 'msl_organization_name'
                ],
                'children' => []
            ]
        ];

        return (json_encode($tree, JSON_PRETTY_PRINT));
    }


    
    public function exportInterpreted() {
        $vocabVersion = config('vocabularies.vocabularies_current_version');
        
        //retrieve current vocabularies
        $materialVocab = Vocabulary::where('name', 'materials')->where('version', $vocabVersion)->first();
        $geologicalAgeVocab = Vocabulary::where('name', 'geologicalage')->where('version', $vocabVersion)->first();
        $poreFluidVocab = Vocabulary::where('name', 'porefluids')->where('version', $vocabVersion)->first();
        $geologicalSettingVocab = Vocabulary::where('name', 'geologicalsetting')->where('version', $vocabVersion)->first();
        $analogueVocab = Vocabulary::where('name', 'analogue')->where('version', $vocabVersion)->first();
        $geochemistryVocab = Vocabulary::where('name', 'geochemistry')->where('version', $vocabVersion)->first();
        $microscopyVocab = Vocabulary::where('name', 'microscopy')->where('version', $vocabVersion)->first();
        $paleomagnetismVocab = Vocabulary::where('name', 'paleomagnetism')->where('version', $vocabVersion)->first();
        $rockphysicsVocab = Vocabulary::where('name', 'rockphysics')->where('version', $vocabVersion)->first();
        $subsurfaceVocab = Vocabulary::where('name', 'subsurface')->where('version', $vocabVersion)->first();
        $geoenergyVocab = Vocabulary::where('name', 'testbeds')->where('version', $vocabVersion)->first();
                
        
        $tree = [
            [
                'text' => 'Material',
                'state' => [
                    'opened' => false,
                    'disabled' => false,
                    'selected' => false,
                    'checked' => false
                ],            
                'extra' => [
                    'type' => 'filter',
                    'url' => '',
                    'filterName' => 'msl_has_material',
                    'filterValue' => 'true'
                ],
                'children' => $this->getVocabAsFilters($materialVocab->id, 'msl_enriched_keyword_uri', true)
            ],
            [
                'text' => 'Geological age',
                'state' => [
                    'opened' => false,
                    'disabled' => false,
                    'selected' => false,
                    'checked' => false
                ],
                'extra' => [
                    'type' => 'filter',
                    'url' => '',
                    'filterName' => 'msl_has_geologicalage',
                    'filterValue' => 'true'
                ],
                'children' => $this->getVocabAsFilters($geologicalAgeVocab->id, 'msl_enriched_keyword_uri', false, false)
            ],
            [
                'text' => 'Pore fluid',
                'state' => [
                    'opened' => false,
                    'disabled' => false,
                    'selected' => false,
                    'checked' => false
                ],
                'extra' => [
                    'type' => 'filter',
                    'url' => '',
                    'filterName' => 'msl_has_porefluid',
                    'filterValue' => 'true'
                ],
                'children' => $this->getVocabAsFilters($poreFluidVocab->id, 'msl_enriched_keyword_uri')
            ],
            [
                'text' => 'Geological setting',
                'state' => [
                    'opened' => false,
                    'disabled' => false,
                    'selected' => false,
                    'checked' => false
                ],
                'extra' => [
                    'type' => 'filter',
                    'url' => '',
                    'filterName' => 'msl_has_geologicalsetting',
                    'filterValue' => 'true'
                ],
                'children' => $this->getVocabAsFilters($geologicalSettingVocab->id, 'msl_enriched_keyword_uri')
            ],
            [
                'text' => '(sub)surface utilization setting',
                'state' => [
                    'opened' => false,
                    'disabled' => false,
                    'selected' => false,
                    'checked' => false
                ],
                'extra' => [
                    'type' => 'filter',
                    'url' => '',
                    'filterName' => 'msl_has_subsurface',
                    'filterValue' => 'true'
                ],
                'children' => $this->getVocabAsFilters($subsurfaceVocab->id, 'msl_enriched_keyword_uri')
            ],
            [
                'text' => 'Analogue modelling of geological processes',
                'state' => [
                    'opened' => false,
                    'disabled' => false,
                    'selected' => false,
                    'checked' => false
                ],
                'extra' => [
                    'type' => 'filter',
                    'url' => '',
                    'filterName' => 'msl_has_analogue',
                    'filterValue' => 'true'
                ],
                'children' => $this->getVocabAsFilters($analogueVocab->id, 'msl_enriched_keyword_uri')
            ],
            [
                'text' => 'Geochemistry',
                'state' => [
                    'opened' => false,
                    'disabled' => false,
                    'selected' => false,
                    'checked' => false
                ],
                'extra' => [
                    'type' => 'filter',
                    'url' => '',
                    'filterName' => 'msl_has_geochemistry',
                    'filterValue' => 'true'
                ],
                'children' => $this->getVocabAsFilters($geochemistryVocab->id, 'msl_enriched_keyword_uri')
            ],
            [
                'text' => 'Geo-energy test beds',
                'state' => [
                    'opened' => false,
                    'disabled' => false,
                    'selected' => false,
                    'checked' => false
                ],
                'extra' => [
                    'type' => 'filter',
                    'url' => '',
                    'filterName' => 'msl_has_geoenergy',
                    'filterValue' => 'true'
                ],
                'children' => $this->getVocabAsFilters($geoenergyVocab->id, 'msl_enriched_keyword_uri')
            ],
            [
                'text' => 'Microscopy and tomography',
                'state' => [
                    'opened' => false,
                    'disabled' => false,
                    'selected' => false,
                    'checked' => false
                ],
                'extra' => [
                    'type' => 'filter',
                    'url' => '',
                    'filterName' => 'msl_has_microscopy',
                    'filterValue' => 'true'
                ],
                'children' => $this->getVocabAsFilters($microscopyVocab->id, 'msl_enriched_keyword_uri')
            ],
            [
                'text' => 'Paleomagnetism',
                'state' => [
                    'opened' => false,
                    'disabled' => false,
                    'selected' => false,
                    'checked' => false
                ],
                'extra' => [
                    'type' => 'filter',
                    'url' => '',
                    'filterName' => 'msl_has_paleomagnetism',
                    'filterValue' => 'true'
                ],
                'children' => $this->getVocabAsFilters($paleomagnetismVocab->id, 'msl_enriched_keyword_uri')
            ],
            [
                'text' => 'Rock and melt physics',
                'state' => [
                    'opened' => false,
                    'disabled' => false,
                    'selected' => false,
                    'checked' => false
                ],
                'extra' => [
                    'type' => 'filter',
                    'url' => '',
                    'filterName' => 'msl_has_rockphysic',
                    'filterValue' => 'true'
                ],
                'children' => $this->getVocabAsFilters($rockphysicsVocab->id, 'msl_enriched_keyword_uri')
            ],
            [
                'text' => 'Research Institute',
                'state' => [
                    'opened' => false,
                    'disabled' => false,
                    'selected' => false,
                    'checked' => false
                ],
                'extra' => [
                    'type' => 'filter',
                    'url' => '',
                    'filterName' => 'msl_has_lab',
                    'filterValue' => 'true',
                    'includeFacet' => true,
                    'facetName' => 'msl_lab_name'
                ],
                'children' => []
            ],
            [
                'text' => 'Data repository',
                'state' => [
                    'opened' => false,
                    'disabled' => false,
                    'selected' => false,
                    'checked' => false
                ],
                'extra' => [
                    'type' => 'filter',
                    'url' => '',
                    'filterName' => 'msl_has_organization',
                    'filterValue' => 'true',
                    'includeFacet' => true,
                    'facetName' => 'organization'
                ],
                'children' => []
            ],
        ];
                
        return (json_encode($tree, JSON_PRETTY_PRINT));
    }
    
    public function exportOriginal() {
        $vocabVersion = config('vocabularies.vocabularies_current_version');
        
        //retrieve current vocabularies
        $materialVocab = Vocabulary::where('name', 'materials')->where('version', $vocabVersion)->first();
        $geologicalAgeVocab = Vocabulary::where('name', 'geologicalage')->where('version', $vocabVersion)->first();
        $poreFluidVocab = Vocabulary::where('name', 'porefluids')->where('version', $vocabVersion)->first();
        $geologicalSettingVocab = Vocabulary::where('name', 'geologicalsetting')->where('version', $vocabVersion)->first();
        $analogueVocab = Vocabulary::where('name', 'analogue')->where('version', $vocabVersion)->first();
        $geochemistryVocab = Vocabulary::where('name', 'geochemistry')->where('version', $vocabVersion)->first();
        $microscopyVocab = Vocabulary::where('name', 'microscopy')->where('version', $vocabVersion)->first();
        $paleomagnetismVocab = Vocabulary::where('name', 'paleomagnetism')->where('version', $vocabVersion)->first();
        $rockphysicsVocab = Vocabulary::where('name', 'rockphysics')->where('version', $vocabVersion)->first();
        $subsurfaceVocab = Vocabulary::where('name', 'subsurface')->where('version', $vocabVersion)->first();
        $geoenergyVocab = Vocabulary::where('name', 'testbeds')->where('version', $vocabVersion)->first();
        
        $tree = [
            [
                'text' => 'Material',
                'state' => [
                    'opened' => false,
                    'disabled' => false,
                    'selected' => false,
                    'checked' => false
                ],
                'extra' => [
                    'type' => 'filter',
                    'url' => '',
                    'filterName' => 'msl_has_material_original',
                    'filterValue' => 'true'
                ],
                'children' => $this->getVocabAsFilters($materialVocab->id, 'msl_original_keyword_uri', true, true)
            ],
            [
                'text' => 'Geological age',
                'state' => [
                    'opened' => false,
                    'disabled' => false,
                    'selected' => false,
                    'checked' => false
                ],
                'extra' => [
                    'type' => 'filter',
                    'url' => '',
                    'filterName' => 'msl_has_geologicalage_original',
                    'filterValue' => 'true'
                ],
                'children' => $this->getVocabAsFilters($geologicalAgeVocab->id, 'msl_original_keyword_uri', false, false)
            ],
            [
                'text' => 'Pore fluid',
                'state' => [
                    'opened' => false,
                    'disabled' => false,
                    'selected' => false,
                    'checked' => false
                ],
                'extra' => [
                    'type' => 'filter',
                    'url' => '',
                    'filterName' => 'msl_has_porefluid_original',
                    'filterValue' => 'true'
                ],
                'children' => $this->getVocabAsFilters($poreFluidVocab->id, 'msl_original_keyword_uri', false, true)
            ],
            [
                'text' => 'Geological setting',
                'state' => [
                    'opened' => false,
                    'disabled' => false,
                    'selected' => false,
                    'checked' => false
                ],
                'extra' => [
                    'type' => 'filter',
                    'url' => '',
                    'filterName' => 'msl_has_geologicalsetting_original',
                    'filterValue' => 'true'
                ],
                'children' => $this->getVocabAsFilters($geologicalSettingVocab->id, 'msl_original_keyword_uri', false, true)
            ],
            [
                'text' => '(sub)surface utilization setting',
                'state' => [
                    'opened' => false,
                    'disabled' => false,
                    'selected' => false,
                    'checked' => false
                ],
                'extra' => [
                    'type' => 'filter',
                    'url' => '',
                    'filterName' => 'msl_has_subsurface_original',
                    'filterValue' => 'true'
                ],
                'children' => $this->getVocabAsFilters($subsurfaceVocab->id, 'msl_original_keyword_uri', false, true)
            ],
            [
                'text' => 'Analogue modelling of geological processes',
                'state' => [
                    'opened' => false,
                    'disabled' => false,
                    'selected' => false,
                    'checked' => false
                ],
                'extra' => [
                    'type' => 'filter',
                    'url' => '',
                    'filterName' => 'msl_has_analogue_original',
                    'filterValue' => 'true'
                ],
                'children' => $this->getVocabAsFilters($analogueVocab->id, 'msl_original_keyword_uri', false, true)
            ],
            [
                'text' => 'Geochemistry',
                'state' => [
                    'opened' => false,
                    'disabled' => false,
                    'selected' => false,
                    'checked' => false
                ],
                'extra' => [
                    'type' => 'filter',
                    'url' => '',
                    'filterName' => 'msl_has_geochemistry_original',
                    'filterValue' => 'true'
                ],
                'children' => $this->getVocabAsFilters($geochemistryVocab->id, 'msl_original_keyword_uri', false, true)
            ],
            [
                'text' => 'Geo-energy test beds',
                'state' => [
                    'opened' => false,
                    'disabled' => false,
                    'selected' => false,
                    'checked' => false
                ],
                'extra' => [
                    'type' => 'filter',
                    'url' => '',
                    'filterName' => 'msl_has_geoenergy_original',
                    'filterValue' => 'true'
                ],
                'children' => $this->getVocabAsFilters($geoenergyVocab->id, 'msl_original_keyword_uri', false, true)
            ],
            [
                'text' => 'Microscopy and tomography',
                'state' => [
                    'opened' => false,
                    'disabled' => false,
                    'selected' => false,
                    'checked' => false
                ],
                'extra' => [
                    'type' => 'filter',
                    'url' => '',
                    'filterName' => 'msl_has_microscopy_original',
                    'filterValue' => 'true'
                ],
                'children' => $this->getVocabAsFilters($microscopyVocab->id, 'msl_original_keyword_uri', false, true)
            ],
            [
                'text' => 'Paleomagnetism',
                'state' => [
                    'opened' => false,
                    'disabled' => false,
                    'selected' => false,
                    'checked' => false
                ],
                'extra' => [
                    'type' => 'filter',
                    'url' => '',
                    'filterName' => 'msl_has_paleomagnetism_original',
                    'filterValue' => 'true'
                ],
                'children' => $this->getVocabAsFilters($paleomagnetismVocab->id, 'msl_original_keyword_uri', false, true)
            ],
            [
                'text' => 'Rock and melt physics',
                'state' => [
                    'opened' => false,
                    'disabled' => false,
                    'selected' => false,
                    'checked' => false
                ],
                'extra' => [
                    'type' => 'filter',
                    'url' => '',
                    'filterName' => 'msl_has_rockphysic_original',
                    'filterValue' => 'true'
                ],
                'children' => $this->getVocabAsFilters($rockphysicsVocab->id, 'msl_original_keyword_uri', false, true)
            ],
            [
                'text' => 'Research Institute',
                'state' => [
                    'opened' => false,
                    'disabled' => false,
                    'selected' => false,
                    'checked' => false
                ],
                'extra' => [
                    'type' => 'filter',
                    'url' => '',
                    'filterName' => 'msl_has_lab',
                    'filterValue' => 'true',
                    'includeFacet' => true,
                    'facetName' => 'msl_lab_name'
                ],
                'children' => []
            ],
            [
                'text' => 'Data repository',
                'state' => [
                    'opened' => false,
                    'disabled' => false,
                    'selected' => false,
                    'checked' => false
                ],
                'extra' => [
                    'type' => 'filter',
                    'url' => '',
                    'filterName' => 'msl_has_organization',
                    'filterValue' => 'true',
                    'includeFacet' => true,
                    'facetName' => 'organization'
                ],
                'children' => []
            ],
        ];
        
        return (json_encode($tree, JSON_PRETTY_PRINT));
    }
    
    
    private function getVocabAsFilters($vocabId, $filterPrefix = "", $sortToplevel = false, $sortChildren = true, $filterSuffix = "") {
        if($sortToplevel) {
            $topNodes = Keyword::where(['vocabulary_id' => $vocabId, 'level' => 1])->orderBy('value', 'asc')->get();
        } else {
            $topNodes = Keyword::where(['vocabulary_id' => $vocabId, 'level' => 1])->get();
        }        
        $vocabFilters = [];
        
        foreach ($topNodes as $node) {
            $filter = [
                'id' => $filterPrefix . $node->id . $filterSuffix,
                'text' => $node->value,
                'state' => [
                    'opened' => false,
                    'disabled' => true,
                    'selected' => false,
                    'checked' => false
                ],
                'extra' => [
                    'type' => 'filter',
                    'url' => '',
                    'filterName' => $filterPrefix,
                    'filterValue' => $node->uri
                ],
                'children' => $this->getChildren($node, $filterPrefix, $sortChildren, $filterSuffix)                
            ];
            
            $vocabFilters[] = $filter;
        }
        
        return $vocabFilters;        
    }
    
    private function getChildren($keyword, $filterPrefix = "", $sort = true, $filterSuffix = "")
    {
        $children = $keyword->getChildren($sort);
        $childFilters = [];
        
        foreach ($children as $child) {
            $filter = [
                'id' => $filterPrefix . $child->id . $filterSuffix,
                'text' => $child->value,
                'state' => [
                    'opened' => false,
                    'disabled' => true,
                    'selected' => false,
                    'checked' => false
                ],
                'extra' => [
                    'type' => 'filter',
                    'url' => '',
                    'filterName' => $filterPrefix,
                    'filterValue' => $child->uri
                ],
                'children' => $this->getChildren($child, $filterPrefix, $sort, $filterSuffix)
            ];
            
            $childFilters[] = $filter;
        }
        
        return $childFilters;
    }
}

