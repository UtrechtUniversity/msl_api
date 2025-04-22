<?php

namespace Database\Seeders;

use App\Models\Keyword;
use App\Models\Vocabulary;
use App\Models\KeywordSearch;

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use function Laravel\Prompts\error;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SelectionGroupSeeder extends Seeder
{

    private $version = 1.3;


    private $includedVocabularies_group_1 = array (
        "materials" => [], //empty array = all uri's in this vocabulary
        "geologicalsetting" => [],
        "subsurface" => []
    );

    private $includedVocabularies_group_2 = array (
        'analogue' => ['apparatus', 'measured_property'],
        'geochemistry' => ['analysis'],
        'microscopy' => ['apparatus', 'technique', 'analyzed_feature'. 'inferred_behavior'],
        'paleomagnetism' => ['apparatus', 'measured_property', 'inferred_behavior'],
        'rockphysics' => ['apparatus', 'measured_property', 'inferred_deformation_behavior']
    );

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vocabularies = Vocabulary::where('version', $this->version)->get();

        $names = [];
        foreach ($vocabularies as $vocabulary) {
            $names[] = $vocabulary->name;

            if (in_array($vocabulary->name, array_keys($this->includedVocabularies_group_1), true)){

                $this->assignSelectionGroup(1, $vocabulary, $this->includedVocabularies_group_1[$vocabulary->name]);

            } else if (in_array($vocabulary->name, array_keys($this->includedVocabularies_group_2), true)){

                $this->assignSelectionGroup(2, $vocabulary, $this->includedVocabularies_group_2[$vocabulary->name]);

            }
        }
        dd($names);
    }

    private function assignSelectionGroup(int $groupNumber, $vocabulary, $uriSearchValues){

        if($groupNumber == 1 || $groupNumber == 2){

            $keywordsFullArray = [];

            if (sizeof($uriSearchValues) == 0){
                $keywordsFullArray[] = Keyword::where('uri', 'LIKE', $vocabulary->uri.'%')->get();
            } else {
                foreach ($uriSearchValues as $uriSearchValue) {
                    $keywordsFullArray[] = Keyword::where('uri', 'LIKE', $vocabulary->uri.$uriSearchValue.'%')->get();
                }
            }

            foreach ($keywordsFullArray as $keywords) {

                foreach ($keywords as $keyword) {

                    $excludeKeyword = in_array($keyword->uri, $this->excludedKeywordsList, true);

                    if($groupNumber == 1){
                        $keyword->selection_group_1 = $excludeKeyword ? 0 : 1;
                        $keyword->selection_group_2 = 0;
                    } else {
                        $keyword->selection_group_1 = 0;
                        $keyword->selection_group_2 = $excludeKeyword ? 0 : 1;
                    }
                    // $keyword->save();


                    $keyword_search_equivalents = KeywordSearch::where('keyword_id', $keyword->id)->get();

                    foreach ($keyword_search_equivalents as $keyword_search_equivalent) {

                        $excludeKeywordSearch = in_array($keyword_search_equivalent->search_value, $this->excludedSearchKeywords, true);
                        $excludeKeywordSearch ? dd($keyword_search_equivalent->search_value): false;

                        if($groupNumber == 1){
                            $keyword_search_equivalent->exclude_selection_group_1 = $excludeKeywordSearch ? 1 : 0;
                            $keyword_search_equivalent->exclude_selection_group_2 = 1;
                        } else {
                            $keyword_search_equivalent->exclude_selection_group_1 = 1;
                            $keyword_search_equivalent->exclude_selection_group_2 = $excludeKeywordSearch ? 1 : 0;
                        }
                    // $keyword_search_equivalent->save();

                    }

                       
                
                }


            }

        } else {
            return error("SelectionGroupSeeder.php/assignSelectionGroup: variable 'groupNumber' must be either 1 or 2, since there are only two selection groups");
        }
    }


    private $excludedSearchKeywords = array (
        "rubber",
        "spontaneous potential",
        "tectonic plate boundary",
        "earth shells",
        "absorption contrast tomography"
    );

    private $excludedKeywordsList = array(
        "https://epos-msl.uu.nl/voc/materials/1.3/sedimentary_rock-limestone-chalk",
        "https://epos-msl.uu.nl/voc/materials/1.3/sedimentary_rock-coal",
        "https://epos-msl.uu.nl/voc/materials/1.3/minerals",
        "https://epos-msl.uu.nl/voc/materials/1.3/minerals-chemical_elements",
        "https://epos-msl.uu.nl/voc/materials/1.3/minerals-chemical_elements-aluminium",
        "https://epos-msl.uu.nl/voc/materials/1.3/minerals-chemical_elements-antimony",
        "https://epos-msl.uu.nl/voc/materials/1.3/minerals-chemical_elements-arsenic",
        "https://epos-msl.uu.nl/voc/materials/1.3/minerals-chemical_elements-bismuth",
        "https://epos-msl.uu.nl/voc/materials/1.3/minerals-chemical_elements-cadmium",
        "https://epos-msl.uu.nl/voc/materials/1.3/minerals-chemical_elements-carbon",
        "https://epos-msl.uu.nl/voc/materials/1.3/minerals-chemical_elements-chromium",
        "https://epos-msl.uu.nl/voc/materials/1.3/minerals-chemical_elements-copper",
        "https://epos-msl.uu.nl/voc/materials/1.3/minerals-chemical_elements-gold",
        "https://epos-msl.uu.nl/voc/materials/1.3/minerals-chemical_elements-iridium",
        "https://epos-msl.uu.nl/voc/materials/1.3/minerals-chemical_elements-iron",
        "https://epos-msl.uu.nl/voc/materials/1.3/minerals-chemical_elements-mercury",
        "https://epos-msl.uu.nl/voc/materials/1.3/minerals-chemical_elements-nickel",
        "https://epos-msl.uu.nl/voc/materials/1.3/minerals-chemical_elements-platinum",
        "https://epos-msl.uu.nl/voc/materials/1.3/minerals-chemical_elements-selenium",
        "https://epos-msl.uu.nl/voc/materials/1.3/minerals-chemical_elements-silicon",
        "https://epos-msl.uu.nl/voc/materials/1.3/minerals-chemical_elements-silver",
        "https://epos-msl.uu.nl/voc/materials/1.3/minerals-chemical_elements-sulfur",
        "https://epos-msl.uu.nl/voc/materials/1.3/minerals-chemical_elements-tellurium",
        "https://epos-msl.uu.nl/voc/materials/1.3/minerals-chemical_elements-tin",
        "https://epos-msl.uu.nl/voc/materials/1.3/minerals-chemical_elements-titanium",
        "https://epos-msl.uu.nl/voc/materials/1.3/minerals-chemical_elements-zinc",
        "https://epos-msl.uu.nl/voc/materials/1.3/minerals-oxide_mineral-ice",
        "https://epos-msl.uu.nl/voc/materials/1.3/minerals-silicate_minerals-phyllosilicates-clay",
        "https://epos-msl.uu.nl/voc/materials/1.3/unconsolidated_sediment-organic_rich_sediment-peat",
        "https://epos-msl.uu.nl/voc/materials/1.3/unconsolidated_sediment-clastic_sediment-gravel",
        "https://epos-msl.uu.nl/voc/materials/1.3/unconsolidated_sediment-clastic_sediment-mud",
        "https://epos-msl.uu.nl/voc/materials/1.3/unconsolidated_sediment-clastic_sediment-clay",
        "https://epos-msl.uu.nl/voc/materials/1.3/unconsolidated_sediment-clastic_sediment-silt",
        "https://epos-msl.uu.nl/voc/materials/1.3/unconsolidated_sediment-clastic_sediment-sand",
        "https://epos-msl.uu.nl/voc/materials/1.3/analogue_modelling_material-viscous_modelling_material-synthetic_viscous_material-silicone",
        "https://epos-msl.uu.nl/voc/rockphysics/1.3/measured_property-strain",
        "https://epos-msl.uu.nl/voc/rockphysics/1.3/measured_property-elastic_wave_velocity",
        "https://epos-msl.uu.nl/voc/rockphysics/1.3/measured_property-elastic_wave_velocity-p-wave",
        "https://epos-msl.uu.nl/voc/rockphysics/1.3/measured_property-elastic_wave_velocity-s-wave",
        "https://epos-msl.uu.nl/voc/rockphysics/1.3/measured_property-elastic_wave_velocity-s1-wave",
        "https://epos-msl.uu.nl/voc/rockphysics/1.3/measured_property-elastic_wave_velocity-s2-wave",
        "https://epos-msl.uu.nl/voc/rockphysics/1.3/measured_property-elastic_wave_velocity-wave_attenuation",
        "https://epos-msl.uu.nl/voc/rockphysics/1.3/measured_property-porosity",
        "https://epos-msl.uu.nl/voc/rockphysics/1.3/measured_property-thermal_properties",
        "https://epos-msl.uu.nl/voc/rockphysics/1.3/measured_property-thermal_properties-heat_capacity",
        "https://epos-msl.uu.nl/voc/rockphysics/1.3/measured_property-thermal_properties-thermal_conductivity",
        "https://epos-msl.uu.nl/voc/rockphysics/1.3/measured_property-electrical_properties",
        "https://epos-msl.uu.nl/voc/rockphysics/1.3/measured_property-electrical_properties-electrical_conductivity",
        "https://epos-msl.uu.nl/voc/rockphysics/1.3/measured_property-electrical_properties-electrical_resistivity",
        "https://epos-msl.uu.nl/voc/rockphysics/1.3/measured_property-electrical_properties-electrical_capacity",
        "https://epos-msl.uu.nl/voc/rockphysics/1.3/measured_property-electrical_properties-frequency_dependent_conductivity",
        "https://epos-msl.uu.nl/voc/rockphysics/1.3/measured_property-viscosity",
        "https://epos-msl.uu.nl/voc/rockphysics/1.3/measured_property-grain_size_distribution",
        "https://epos-msl.uu.nl/voc/analoguemodelling/1.3/measured_property-strain",
        "https://epos-msl.uu.nl/voc/analoguemodelling/1.3/measured_property-elastic_wave_velocity",
        "https://epos-msl.uu.nl/voc/analoguemodelling/1.3/measured_property-elastic_wave_velocity-p-wave",
        "https://epos-msl.uu.nl/voc/analoguemodelling/1.3/measured_property-elastic_wave_velocity-s-wave",
        "https://epos-msl.uu.nl/voc/analoguemodelling/1.3/measured_property-elastic_wave_velocity-s1-wave",
        "https://epos-msl.uu.nl/voc/analoguemodelling/1.3/measured_property-elastic_wave_velocity-s2-wave",
        "https://epos-msl.uu.nl/voc/analoguemodelling/1.3/measured_property-elastic_wave_velocity-wave_attenuation",
        "https://epos-msl.uu.nl/voc/analoguemodelling/1.3/measured_property-porosity",
        "https://epos-msl.uu.nl/voc/analoguemodelling/1.3/measured_property-thermal_properties",
        "https://epos-msl.uu.nl/voc/analoguemodelling/1.3/measured_property-thermal_properties-heat_capacity",
        "https://epos-msl.uu.nl/voc/analoguemodelling/1.3/measured_property-thermal_properties-thermal_conductivity",
        "https://epos-msl.uu.nl/voc/analoguemodelling/1.3/measured_property-viscosity",
        "https://epos-msl.uu.nl/voc/analoguemodelling/1.3/measured_property-grain_size_distribution",
        "https://epos-msl.uu.nl/voc/microscopy/1.3/analyzed_feature-grain_size_and_configuration-grain_size",
        "https://epos-msl.uu.nl/voc/microscopy/1.3/analyzed_feature-grain_size_and_configuration-grain_size-grain_size_distribution"
    );


}
