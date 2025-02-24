<?php

namespace Tests\Unit;

use App\Models\SourceDataset;
use PHPUnit\Framework\TestCase;
use App\Models\Ckan\DataPublication;
use App\Mappers\Datacite\Datacite4Mapper;
use SebastianBergmann\Type\VoidType;

class Datacite4Test extends TestCase
{

    /**
     * test if description is correctly mapped
     */
    public function test_description_mapping(): void
    {
        $sourceData = new SourceDataset();

        $sourceData->source_dataset = '
            {
                "data": {
                    "id": "10.1594/pangaea.937090",
                    "type": "dois",
                    "attributes": {
                        "descriptions": [
                            {
                                "lang": "en",
                                "description": "Example Abstract",
                                "descriptionType": "Abstract"
                            },
                            {
                                "lang": "en",
                                "description": "Example Methods",
                                "descriptionType": "Methods"
                            },
                            {
                                "lang": "en",
                                "description": "Example SeriesInformation",
                                "descriptionType": "SeriesInformation"
                            },
                            {
                                "lang": "en",
                                "description": "Example TableOfContents",
                                "descriptionType": "TableOfContents"
                            },
                            {
                                "lang": "en",
                                "description": "Example TechnicalInfo",
                                "descriptionType": "TechnicalInfo"
                            },
                            {
                                "lang": "en",
                                "description": "Example Other",
                                "descriptionType": "Other"
                            }
                        ]
                    }
                }
            }';
        $dataciteMapper = new Datacite4Mapper();

        // create empty data publication
        $dataset = new DataPublication;

        // read json text
        $metadata = json_decode($sourceData->source_dataset, true);
        
        $dataset = $dataciteMapper->mapDescription($metadata, $dataset);

        $this->assertEquals($dataset->msl_description_abstract          , "Example Abstract");
        $this->assertEquals($dataset->msl_description_methods           , "Example Methods");
        $this->assertEquals($dataset->msl_description_series_information, "Example SeriesInformation");
        $this->assertEquals($dataset->msl_description_table_of_contents , "Example TableOfContents");
        $this->assertEquals($dataset->msl_description_technical_info    , "Example TechnicalInfo");
        $this->assertEquals($dataset->msl_description_other             , "Example Other");


        // new test
        $sourceData = new SourceDataset();

        $sourceData->source_dataset = '
            {
                "data": {
                    "id": "10.1594/pangaea.937090",
                    "type": "dois",
                    "attributes": {
                        "descriptions": [
                            {
                                "description": "The timing of the Monte Peron Landslide is revised to 2890 cal. BP based on a radiocarbon-dated sediment stratigraphy of Lago di Vedana. This age fosters the importance of hydroclimatic triggers in the light of accelerating global warming with a predicted increase of precipitation enhancing the regional predisposition to large landslides. Moreover, a layer enriched in allochthonous organic and minerogenic detritus dating to the same wet period is interpreted as response to a younger and yet unidentified mass wasting event in the catchment of Lago di Vedana. Rock debris of the Monte Peron Landslide impounded the Cordevole River valley and created a landslide-dammed lake. Around AD 1150, eutrophication of this lacustrine ecosystem started with intensified human occupation – a process that ended 150 years later, when the river was diverted back into its original bed. Most likely, this occurred due to artificial opening of the river dam. In consequence, Lago di Vedana was isolated from an open and minerogenic to an endorheic and carbonaceous lacustrine system. After a monastery was established nearby in AD 1457, a second eutrophication process was initiated due to intensified land use linked with deforestation. Only in the 18th and 19th century, deposition of organic matter decreased coinciding with climatic (Little Ice Age) and cultural changes. Conversational measures are the likely reasons for a trend towards less eutrophic conditions since AD 1950.",
                                "descriptionType": "Abstract"
                            }
                        ]
                    }
                }
            }';
        $dataciteMapper = new Datacite4Mapper();

        // create empty data publication
        $dataset = new DataPublication;

        // read json text
        $metadata = json_decode($sourceData->source_dataset, true);
        
        $dataset = $dataciteMapper->mapDescription($metadata, $dataset);

        $this->assertEquals($dataset->msl_description_abstract, "The timing of the Monte Peron Landslide is revised to 2890 cal. BP based on a radiocarbon-dated sediment stratigraphy of Lago di Vedana. This age fosters the importance of hydroclimatic triggers in the light of accelerating global warming with a predicted increase of precipitation enhancing the regional predisposition to large landslides. Moreover, a layer enriched in allochthonous organic and minerogenic detritus dating to the same wet period is interpreted as response to a younger and yet unidentified mass wasting event in the catchment of Lago di Vedana. Rock debris of the Monte Peron Landslide impounded the Cordevole River valley and created a landslide-dammed lake. Around AD 1150, eutrophication of this lacustrine ecosystem started with intensified human occupation – a process that ended 150 years later, when the river was diverted back into its original bed. Most likely, this occurred due to artificial opening of the river dam. In consequence, Lago di Vedana was isolated from an open and minerogenic to an endorheic and carbonaceous lacustrine system. After a monastery was established nearby in AD 1457, a second eutrophication process was initiated due to intensified land use linked with deforestation. Only in the 18th and 19th century, deposition of organic matter decreased coinciding with climatic (Little Ice Age) and cultural changes. Conversational measures are the likely reasons for a trend towards less eutrophic conditions since AD 1950.");


        // new Test
        $sourceData = new SourceDataset();

        $sourceData->source_dataset = '
            {
                "data": {
                    "id": "10.1594/pangaea.937090",
                    "type": "dois",
                    "attributes": {
                        "descriptions": [
                            {
                                "lang": "es-ES",
                                "description": "C-S-H and C-S-Ag-H gel solutions were analysed by UV/Vis/NIR spectroscopy with a Shimadzu 3600 UV/Vis/NIR spectrometer equipped with 2H and Wlamps, a photomultiplier (UV/Vis), and a InGaAs and a PbS (NIR) detectors. The baseline reference sample was prepared with 1000 µL of MilliQ water. The samples were diluted 1:19 (50 μL of gel solution to 950 μL of of MilliQ water) and the measurements were recorded in the range of 190–850 nm, with slow scan speed. The sampling interval and the slit width were 0.5 nm and 1 nm, respectively. For the 29Si NMR analysis of dried C-S-H and C-S-Ag-H, a Bruker AV-400 (9.4 T and νR of 4 kHz) (Bruker, Germany) NMR spectrometer was used in the following conditions: pulse width of 7 μs and relaxation delay of 60 s with typically 3000 scans. The 29Si chemical shifts were determined relative to tetramethylsilane. The spectra were treated for band deconvolution into Gaussian peaks and fitted using Origin 2022 software. The micro-Raman spectra were carried out on pressed pellets of the samples with a Raman microscope Renishaw RM1000 (Renishaw, Wotton-under-Endge, UK) equipped with a Leica microscope and an electrically refrigerated CCD camera. Laser excitation line was provided by a He:Ne (633 nm wavelength, 25 mW output power with approximately 2 mW at the sample). The spectra were obtained using a 50× magnification objective, a spectral resolution of 4 cm−1, a 10 s exposure time, 5 accumulations, and 2mW laser power per spectra in the range of 2000–100 cm−1 in order to increase signal/noise ratio. The frequencies were calibrated with silicon at 520 cm−1. X-ray photoelectron (XP) and SERS spectra were taken on pressed C-S-Ag-H pellets before and after irradiation at 355 nm, and 532 nm (and of the reference C-S-H pellet). For the former technique, the analysis was performed under a base pressure lower than 7.5 · 10−9 Torr using a PHOIBOS-150 (Specs) electron analyzer, Mg Kα radiation and a constant pass energy of 100 eV and 20 eV for the wide and narrow scans, respectively. The pellets were fixed to the sample holder using double-sided conductive carbon tape to ensure a good conductivity and the X-ray gun was used at 100 W power in order to avoid any possible sample degradation induced by the X-ray irradiation. The binding energy scale was referenced to the main C 1s signal of the adventitious carbon contamination layer which was set at 284.8 eV. All the spectra were fitted using pseudo-Voigt lines (30 % Gaussian/70 % Lorentzian) and a Shirley-type background. For the SERS analysis, the same experimental conditions were used as for obtaining the Raman spectra using Rhodamine B as a probe molecule (an aliquot of 2 µl of a 5 × 10−6 M aqueous solution was dropped on the irradiated and on the non-irradiated silver-containing pellets). Additionally, different reference RhB spectra were acquired on hydroxylamine-reduced silver nanoparticles (NPs), 2 µl dropped on top of both C-S-H and C-S-Ag-H pellets for the comparison of the SERS activities. More details about samples preparation and irradiation conditions are available in Materials folder.",
                                "descriptionType": "Other"
                            },
                            {
                                "lang": "",
                                "description": "Data life: 2024- (unlimited validity)",
                                "descriptionType": "Other"
                            },
                            {
                                "lang": "es-ES",
                                "description": "This is the experimental dataset used in the paper Applied Surface Science, 662: 160107 (2024) (https://doi.org/10.1016/j.apsusc.2024.160107) in which a novel Surface-Enhanced Raman Spectroscopy (SERS) sensor based on a nanostructured substrate, calcium silicate hydrate (C-S-H), the main hydration product of Portland cement, was synthetized. The procedure involves first the incorporation of silver within the nanostructure of the gel (C-S-Ag-H) and second the modification of the surface of pellets of the newly synthesized material by laser irradiation at 532 nm or 355 nm. This data set includes the results of the effect of silver on the gel structure via visible UV spectroscopy, micro-Raman and 29Si Magic Angle Spinning Nuclear Magnetic Resonance (MAS NMR). It also includes the characterization analyses of the C-S-Ag-H pellets by X-ray Photoelectron Spectroscopy (XPS) to determine the silver oxidation state and the assessment of their SERS activity before and after laser irradiation using for the latter Rhodamine B (RhB) as a probe.",
                                "descriptionType": "Abstract"
                            },
                            {
                                "lang": "",
                                "description": "Materials folder: “Sample_synthesis”; “Laser_irradiation_conditions” NMR folder: \"NMR_readme\"; \"NMR_C-S-H\"; \"NMR_C-S-Ag-H\" Raman folder: “Raman_readme\"; \"Raman_C-S-H\"; \"Raman_C-S-Ag-H\"; \"Raman_C-S-H_RhB\"; \"Raman_C-S-Ag-H_RhB\" SERS folder: \"SERS_readme\"; \"SERS_RhB_C-S-Ag-H_355\"; \"SERS_RhB_C-S-Ag-H_355_NPs\"; \"SERS_RhB_C-S-Ag-H_532\"; \"SERS_RhB_C-S-Ag-H_532_NPs\"; \"SERS_RhB_C-S-Ag-H_NPs\"; \"SERS_RhB_C-S-H_NPs\" XPS folder: \"XPS_readme\"; \"XPS_AgAuger_C-S-Ag-H_532\"; \"XPS_AgAuger_C-S-Ag-H_355\"; \"XPS_Ag3d_C-S-Ag-H_532\"; \"XPS_Ag3d_C-S-Ag-H_355\"; \"XPS_Ag3d_C-S-Ag-H\"; \"XPS_Ag3d_C-S-H\"; \"XPS_Ca2p_C-S-Ag-H_532\"; \"XPS_C1s_C-S-Ag-H_532\"; \"XPS_wide_C-S-Ag-H_532\"; \"XPS_wide_C-S-Ag-H_355\"; \"XPS_wide_C-S-Ag-H\"; \"XPS_wide_C-S-H\" UV-Vis folder: \"UV-VIS_readme\"; \"UV-VIS_C-S-H\"; \"UV-VIS_C-S-Ag-H\"",
                                "descriptionType": "TableOfContents"
                            }
                        ]
                    }
                }
            }';
        $dataciteMapper = new Datacite4Mapper();

        // create empty data publication
        $dataset = new DataPublication;

        // read json text
        $metadata = json_decode($sourceData->source_dataset, true);
        
        $dataset = $dataciteMapper->mapDescription($metadata, $dataset);

        $this->assertEquals($dataset->msl_description_abstract          , "This is the experimental dataset used in the paper Applied Surface Science, 662: 160107 (2024) (https://doi.org/10.1016/j.apsusc.2024.160107) in which a novel Surface-Enhanced Raman Spectroscopy (SERS) sensor based on a nanostructured substrate, calcium silicate hydrate (C-S-H), the main hydration product of Portland cement, was synthetized. The procedure involves first the incorporation of silver within the nanostructure of the gel (C-S-Ag-H) and second the modification of the surface of pellets of the newly synthesized material by laser irradiation at 532 nm or 355 nm. This data set includes the results of the effect of silver on the gel structure via visible UV spectroscopy, micro-Raman and 29Si Magic Angle Spinning Nuclear Magnetic Resonance (MAS NMR). It also includes the characterization analyses of the C-S-Ag-H pellets by X-ray Photoelectron Spectroscopy (XPS) to determine the silver oxidation state and the assessment of their SERS activity before and after laser irradiation using for the latter Rhodamine B (RhB) as a probe.");
        $this->assertEquals($dataset->msl_description_methods           , "");
        $this->assertEquals($dataset->msl_description_series_information, "");
        $this->assertEquals($dataset->msl_description_table_of_contents , "Materials folder: “Sample_synthesis”; “Laser_irradiation_conditions” NMR folder: \"NMR_readme\"; \"NMR_C-S-H\"; \"NMR_C-S-Ag-H\" Raman folder: “Raman_readme\"; \"Raman_C-S-H\"; \"Raman_C-S-Ag-H\"; \"Raman_C-S-H_RhB\"; \"Raman_C-S-Ag-H_RhB\" SERS folder: \"SERS_readme\"; \"SERS_RhB_C-S-Ag-H_355\"; \"SERS_RhB_C-S-Ag-H_355_NPs\"; \"SERS_RhB_C-S-Ag-H_532\"; \"SERS_RhB_C-S-Ag-H_532_NPs\"; \"SERS_RhB_C-S-Ag-H_NPs\"; \"SERS_RhB_C-S-H_NPs\" XPS folder: \"XPS_readme\"; \"XPS_AgAuger_C-S-Ag-H_532\"; \"XPS_AgAuger_C-S-Ag-H_355\"; \"XPS_Ag3d_C-S-Ag-H_532\"; \"XPS_Ag3d_C-S-Ag-H_355\"; \"XPS_Ag3d_C-S-Ag-H\"; \"XPS_Ag3d_C-S-H\"; \"XPS_Ca2p_C-S-Ag-H_532\"; \"XPS_C1s_C-S-Ag-H_532\"; \"XPS_wide_C-S-Ag-H_532\"; \"XPS_wide_C-S-Ag-H_355\"; \"XPS_wide_C-S-Ag-H\"; \"XPS_wide_C-S-H\" UV-Vis folder: \"UV-VIS_readme\"; \"UV-VIS_C-S-H\"; \"UV-VIS_C-S-Ag-H\"");
        $this->assertEquals($dataset->msl_description_technical_info    , "");
        $this->assertEquals($dataset->msl_description_other             , "Data life: 2024- (unlimited validity)");
    }   
    /**
     * test if title is correctly mapped
     */
    public function test_title_mapping(): void
    {
        $sourceData = new SourceDataset();

        $sourceData->source_dataset = '
            {
                "data": {
                    "id": "10.1594/pangaea.937090",
                    "type": "dois",
                    "attributes": {
                        "titles": [                            
                            {
                                "lang": "es",
                                "title": "Example Title spanish"
                            },
                            {   
                                "lang": "",
                                "title": "Example Title no lang"
                            },
                            {
                                "title": "Sedimentological and geochemical data of Lago di Vedana, north-eastern Italy"
                            },
                            {   
                                "lang": "en",
                                "title": "Example Title with lang english"
                            },
                            {
                                "lang": "en",
                                "title": "Example Subtitle",
                                "titleType": "Subtitle"
                            },
                            {
                                "lang": "fr",
                                "title": "Example TranslatedTitle",
                                "titleType": "TranslatedTitle"
                            },
                            {
                                "lang": "en",
                                "title": "Example AlternativeTitle",
                                "titleType": "AlternativeTitle"
                            }
                        ]
                    }
                }
            }';


        $dataciteMapper = new Datacite4Mapper();

        // create empty data publication
        $dataset = new DataPublication;

        // read json text
        $metadata = json_decode($sourceData->source_dataset, true);
        
        $dataset = $dataciteMapper->mapTitle($metadata, $dataset);

        $this->assertEquals($dataset->title, "Sedimentological and geochemical data of Lago di Vedana, north-eastern Italy");
        

        // Next test
        $sourceData = new SourceDataset();

        $sourceData->source_dataset = '
            {
                "data": {
                    "id": "10.1594/pangaea.937090",
                    "type": "dois",
                    "attributes": {
                        "titles": [
                            {
                                "lang": "en",
                                "title": "Example Title"
                            },
                            {
                                "lang": "en",
                                "title": "Example Subtitle",
                                "titleType": "Subtitle"
                            },
                            {
                                "lang": "fr",
                                "title": "Example TranslatedTitle",
                                "titleType": "TranslatedTitle"
                            },
                            {
                                "lang": "en",
                                "title": "Example AlternativeTitle",
                                "titleType": "AlternativeTitle"
                            }
                        ]
                    }
                }
            }';

        $dataciteMapper = new Datacite4Mapper();

        // create empty data publication
        $dataset = new DataPublication;

        // read json text
        $metadata = json_decode($sourceData->source_dataset, true);
        
        $dataset = $dataciteMapper->mapTitle($metadata, $dataset);

        $this->assertEquals($dataset->title, "Example Title");


        // Next test
        $sourceData = new SourceDataset();

        $sourceData->source_dataset = '
            {
                "data": {
                    "id": "10.1594/pangaea.937090",
                    "type": "dois",
                    "attributes": {
                        "titles": [
                            {
                                "title": "Sedimentological and geochemical data of Lago di Vedana, north-eastern Italy"
                            }
                        ]
                    }
                }
            }';

        $dataciteMapper = new Datacite4Mapper();

        // create empty data publication
        $dataset = new DataPublication;

        // read json text
        $metadata = json_decode($sourceData->source_dataset, true);
        
        $dataset = $dataciteMapper->mapTitle($metadata, $dataset);

        $this->assertEquals($dataset->title, "Sedimentological and geochemical data of Lago di Vedana, north-eastern Italy");
    }
}
