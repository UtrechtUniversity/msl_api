<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SelectionGroupSeeder extends Seeder
{
    private $version = 1.3;

    private $includedVocabularies_group_1 = [
        'materials' => [],
        'geologicalsetting' => [],
        'subsurface' => [],
    ];

    private $includedVocabularies_group_2 = [
        'analogue' => ['apparatus', 'measured_property'],
        'geochemistry' => ['analysis'],
        'microscopy' => ['apparatus', 'technique', 'analyzed_feature', 'inferred_behavior'],
        'paleomagnetism' => ['apparatus', 'measured_property', 'inferred_behavior'],
        'rockphysics' => ['apparatus', 'measured_property', 'inferred_deformation_behavior'],
    ];

    private $excludedSearchKeywords = [];

    private $excludedKeywordsList = [
        'https://epos-msl.uu.nl/voc/materials/1.3/sedimentary_rock-limestone-chalk',
        'https://epos-msl.uu.nl/voc/materials/1.3/sedimentary_rock-coal',
        'https://epos-msl.uu.nl/voc/materials/1.3/minerals',
        'https://epos-msl.uu.nl/voc/materials/1.3/minerals-chemical_elements',
        'https://epos-msl.uu.nl/voc/materials/1.3/minerals-chemical_elements-aluminium',
        'https://epos-msl.uu.nl/voc/materials/1.3/minerals-chemical_elements-antimony',
        'https://epos-msl.uu.nl/voc/materials/1.3/minerals-chemical_elements-arsenic',
        'https://epos-msl.uu.nl/voc/materials/1.3/minerals-chemical_elements-bismuth',
        'https://epos-msl.uu.nl/voc/materials/1.3/minerals-chemical_elements-cadmium',
        'https://epos-msl.uu.nl/voc/materials/1.3/minerals-chemical_elements-carbon',
        'https://epos-msl.uu.nl/voc/materials/1.3/minerals-chemical_elements-chromium',
        'https://epos-msl.uu.nl/voc/materials/1.3/minerals-chemical_elements-copper',
        'https://epos-msl.uu.nl/voc/materials/1.3/minerals-chemical_elements-gold',
        'https://epos-msl.uu.nl/voc/materials/1.3/minerals-chemical_elements-iridium',
        'https://epos-msl.uu.nl/voc/materials/1.3/minerals-chemical_elements-iron',
        'https://epos-msl.uu.nl/voc/materials/1.3/minerals-chemical_elements-mercury',
        'https://epos-msl.uu.nl/voc/materials/1.3/minerals-chemical_elements-nickel',
        'https://epos-msl.uu.nl/voc/materials/1.3/minerals-chemical_elements-platinum',
        'https://epos-msl.uu.nl/voc/materials/1.3/minerals-chemical_elements-selenium',
        'https://epos-msl.uu.nl/voc/materials/1.3/minerals-chemical_elements-silicon',
        'https://epos-msl.uu.nl/voc/materials/1.3/minerals-chemical_elements-silver',
        'https://epos-msl.uu.nl/voc/materials/1.3/minerals-chemical_elements-sulfur',
        'https://epos-msl.uu.nl/voc/materials/1.3/minerals-chemical_elements-tellurium',
        'https://epos-msl.uu.nl/voc/materials/1.3/minerals-chemical_elements-tin',
        'https://epos-msl.uu.nl/voc/materials/1.3/minerals-chemical_elements-titanium',
        'https://epos-msl.uu.nl/voc/materials/1.3/minerals-chemical_elements-zinc',
        'https://epos-msl.uu.nl/voc/materials/1.3/minerals-oxide_mineral-ice',
        'https://epos-msl.uu.nl/voc/materials/1.3/minerals-silicate_minerals-phyllosilicates-clay',
        'https://epos-msl.uu.nl/voc/materials/1.3/unconsolidated_sediment-organic_rich_sediment-peat',
        'https://epos-msl.uu.nl/voc/materials/1.3/unconsolidated_sediment-clastic_sediment-gravel',
        'https://epos-msl.uu.nl/voc/materials/1.3/unconsolidated_sediment-clastic_sediment-mud',
        'https://epos-msl.uu.nl/voc/materials/1.3/unconsolidated_sediment-clastic_sediment-clay',
        'https://epos-msl.uu.nl/voc/materials/1.3/unconsolidated_sediment-clastic_sediment-silt',
        'https://epos-msl.uu.nl/voc/materials/1.3/unconsolidated_sediment-clastic_sediment-sand',
        'https://epos-msl.uu.nl/voc/materials/1.3/analogue_modelling_material-viscous_modelling_material-synthetic_viscous_material-silicone',
        'https://epos-msl.uu.nl/voc/rockphysics/1.3/measured_property-strain',
        'https://epos-msl.uu.nl/voc/rockphysics/1.3/measured_property-elastic_wave_velocity',
        'https://epos-msl.uu.nl/voc/rockphysics/1.3/measured_property-elastic_wave_velocity-p-wave',
        'https://epos-msl.uu.nl/voc/rockphysics/1.3/measured_property-elastic_wave_velocity-s-wave',
        'https://epos-msl.uu.nl/voc/rockphysics/1.3/measured_property-elastic_wave_velocity-s1-wave',
        'https://epos-msl.uu.nl/voc/rockphysics/1.3/measured_property-elastic_wave_velocity-s2-wave',
        'https://epos-msl.uu.nl/voc/rockphysics/1.3/measured_property-elastic_wave_velocity-wave_attenuation',
        'https://epos-msl.uu.nl/voc/rockphysics/1.3/measured_property-porosity',
        'https://epos-msl.uu.nl/voc/rockphysics/1.3/measured_property-thermal_properties',
        'https://epos-msl.uu.nl/voc/rockphysics/1.3/measured_property-thermal_properties-heat_capacity',
        'https://epos-msl.uu.nl/voc/rockphysics/1.3/measured_property-thermal_properties-thermal_conductivity',
        'https://epos-msl.uu.nl/voc/rockphysics/1.3/measured_property-electrical_properties',
        'https://epos-msl.uu.nl/voc/rockphysics/1.3/measured_property-electrical_properties-electrical_conductivity',
        'https://epos-msl.uu.nl/voc/rockphysics/1.3/measured_property-electrical_properties-electrical_resistivity',
        'https://epos-msl.uu.nl/voc/rockphysics/1.3/measured_property-electrical_properties-electrical_capacity',
        'https://epos-msl.uu.nl/voc/rockphysics/1.3/measured_property-electrical_properties-frequency_dependent_conductivity',
        'https://epos-msl.uu.nl/voc/rockphysics/1.3/measured_property-viscosity',
        'https://epos-msl.uu.nl/voc/rockphysics/1.3/measured_property-grain_size_distribution',
        'https://epos-msl.uu.nl/voc/analoguemodelling/1.3/measured_property-strain',
        'https://epos-msl.uu.nl/voc/analoguemodelling/1.3/measured_property-elastic_wave_velocity',
        'https://epos-msl.uu.nl/voc/analoguemodelling/1.3/measured_property-elastic_wave_velocity-p-wave',
        'https://epos-msl.uu.nl/voc/analoguemodelling/1.3/measured_property-elastic_wave_velocity-s-wave',
        'https://epos-msl.uu.nl/voc/analoguemodelling/1.3/measured_property-elastic_wave_velocity-s1-wave',
        'https://epos-msl.uu.nl/voc/analoguemodelling/1.3/measured_property-elastic_wave_velocity-s2-wave',
        'https://epos-msl.uu.nl/voc/analoguemodelling/1.3/measured_property-elastic_wave_velocity-wave_attenuation',
        'https://epos-msl.uu.nl/voc/analoguemodelling/1.3/measured_property-porosity',
        'https://epos-msl.uu.nl/voc/analoguemodelling/1.3/measured_property-thermal_properties',
        'https://epos-msl.uu.nl/voc/analoguemodelling/1.3/measured_property-thermal_properties-heat_capacity',
        'https://epos-msl.uu.nl/voc/analoguemodelling/1.3/measured_property-thermal_properties-thermal_conductivity',
        'https://epos-msl.uu.nl/voc/analoguemodelling/1.3/measured_property-viscosity',
        'https://epos-msl.uu.nl/voc/analoguemodelling/1.3/measured_property-grain_size_distribution',
        'https://epos-msl.uu.nl/voc/microscopy/1.3/analyzed_feature-grain_size_and_configuration-grain_size',
        'https://epos-msl.uu.nl/voc/microscopy/1.3/analyzed_feature-grain_size_and_configuration-grain_size-grain_size_distribution',
        'https://epos-msl.uu.nl/voc/materials/1.3/analogue_modelling_material-elastic_modelling_material-natural_elastic_material-natural_rubber',
        'https://epos-msl.uu.nl/voc/rockphysics/1.3/measured_property-electrical_properties-electrical_capacity',
        'https://epos-msl.uu.nl/voc/geologicalsetting/1.3/tectonic_plate_boundary',
        'https://epos-msl.uu.nl/voc/geologicalsetting/1.3/earths_structure',
        'https://epos-msl.uu.nl/voc/microscopy/1.3/technique-imaging_3d-computed_tomography_ct-absorption_contrast_tomography',
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // include all
        $vocabsBaseURI = 'https://epos-msl.uu.nl/voc';

        foreach ($this->includedVocabularies_group_1 as $vocabPrefix => $value) {
            DB::table('keywords')
                ->where('uri', 'LIKE', $vocabsBaseURI.'/'.$vocabPrefix.'/'.$this->version.'%')
                ->update(['selection_group_1' => 1]);
        }

        foreach ($this->includedVocabularies_group_2 as $vocabPrefix => $value) {
            foreach ($value as $subCategory) {
                DB::table('keywords')
                    ->where('uri', 'LIKE', $vocabsBaseURI.'/'.$vocabPrefix.'/'.$this->version.'/'.$subCategory.'%')
                    ->update(['selection_group_2' => 1]);
            }
        }

        // exclude the exeptions
        foreach ($this->excludedKeywordsList as $excludedKeywordUri) {
            DB::table('keywords')
                ->where('uri', '=', $excludedKeywordUri)
                ->update(['selection_group_1' => 0, 'selection_group_2' => 0]);
        }

        foreach ($this->excludedSearchKeywords as $excludedSearchKeyword) {
            DB::table('keywords_search')
                ->where('search_value', '=', $excludedSearchKeyword)
                ->where('version', '=', '1.3')
                ->update(['exclude_selection_group_1' => 1, 'exclude_selection_group_2' => 1]);
        }
    }
}
