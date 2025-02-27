<?php

namespace Tests\Unit;

use App\Models\SourceDataset;
use PHPUnit\Framework\TestCase;
use App\Models\Ckan\DataPublication;
use App\Mappers\Datacite\Datacite4Mapper;

class Datacite4Test extends TestCase
{

        /**
     * test if description is correctly mapped
     */
    public function test_alternateIdentifier_mapping(): void
    {
        $sourceData = new SourceDataset();

        $sourceData->source_dataset = '
            {
                "data": {
                    "id": "10.1594/pangaea.937090",
                    "type": "dois",
                    "attributes": {
                        "alternateIdentifiers": [
                            {
                                "alternateIdentifierType": "Local accession number",
                                "alternateIdentifier": "12345"
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
        
        $dataset = $dataciteMapper->mapAlternateIdentifier($metadata, $dataset);

        $this->assertEquals($dataset->msl_alternate_identifiers[0]['msl_alternate_identifier'] , "12345");
        $this->assertEquals($dataset->msl_alternate_identifiers[0]['msl_alternate_identifier_type'] , "Local accession number");

        //new test
        $sourceData = new SourceDataset();

        $sourceData->source_dataset = '
            {
                "data": {
                    "id": "10.1594/pangaea.937090",
                    "type": "dois",
                    "attributes": {
                        "alternateIdentifiers": [
                           
                        ]
                    }
                }
            }';
        $dataciteMapper = new Datacite4Mapper();

        // create empty data publication
        $dataset = new DataPublication;

        // read json text
        $metadata = json_decode($sourceData->source_dataset, true);
        
        $dataset = $dataciteMapper->mapAlternateIdentifier($metadata, $dataset);

        $this->assertEquals($dataset->msl_alternate_identifiers , []);

        //new test
        $sourceData = new SourceDataset();

        $sourceData->source_dataset = '
            {
                "data": {
                    "id": "10.1594/pangaea.937090",
                    "type": "dois",
                    "attributes": {
                        "alternateIdentifiers": [
                            {
                                "alternateIdentifierType": "citation",
                                "alternateIdentifier": "Maestro-Guijarro, L., Martínez-Ramírez, S.; Sánchez-Cortés, S., Marco, J.F., de la Figuera, J., Castillejo, M.,Oujja, M., Carmona-Quiroga, P. 2024. Dataset for the paper \"Maestro-Guijarro, L., Martínez-Ramírez, S.; Sánchez-Cortés, S., Marco, J.F., de la Figuera, J., Castillejo, M.,Oujja, M., Carmona-Quiroga, P. 2024. Assessment of silver-based calcium silicate hydrate as a novel SERS sensor. Applied Surface Science 662: 160107\"; DIGITAL CSIC; https://doi.org/10.1016/j.apsusc.2024.160107"
                            },
                            {
                                "alternateIdentifierType": "uri",
                                "alternateIdentifier": "http://hdl.handle.net/10261/371208"
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
        
        $dataset = $dataciteMapper->mapAlternateIdentifier($metadata, $dataset);
        
        $this->assertEquals($dataset->msl_alternate_identifiers[0]['msl_alternate_identifier'] , "Maestro-Guijarro, L., Martínez-Ramírez, S.; Sánchez-Cortés, S., Marco, J.F., de la Figuera, J., Castillejo, M.,Oujja, M., Carmona-Quiroga, P. 2024. Dataset for the paper \"Maestro-Guijarro, L., Martínez-Ramírez, S.; Sánchez-Cortés, S., Marco, J.F., de la Figuera, J., Castillejo, M.,Oujja, M., Carmona-Quiroga, P. 2024. Assessment of silver-based calcium silicate hydrate as a novel SERS sensor. Applied Surface Science 662: 160107\"; DIGITAL CSIC; https://doi.org/10.1016/j.apsusc.2024.160107");
        $this->assertEquals($dataset->msl_alternate_identifiers[0]['msl_alternate_identifier_type'] , "citation");
        $this->assertEquals($dataset->msl_alternate_identifiers[1]['msl_alternate_identifier'] , "http://hdl.handle.net/10261/371208");
        $this->assertEquals($dataset->msl_alternate_identifiers[1]['msl_alternate_identifier_type'] , "uri");
    }   

    /**
     * test if rights are correctly mapped
     */
    public function test_identifier_mapping(): void
    {
        $sourceData = new SourceDataset();

        $sourceData->source_dataset = '
        {
            "data": {
                "id": "10.1594/pangaea.937090",
                "type": "dois",
                "attributes": {
                    "doi": "10.82433/b09z-4k37"
                }
            }
        }';

        $dataciteMapper = new Datacite4Mapper();

        // create empty data publication
        $dataset = new DataPublication;

        // read json text
        $metadata = json_decode($sourceData->source_dataset, true);
        
        $dataset = $dataciteMapper->mapIdentifier($metadata, $dataset);

        $this->assertEquals($dataset->msl_doi, "10.82433/b09z-4k37");
    }

    /**
     * test if publicationYear is correctly mapped
     */
    public function test_publicationYear_mapping(): void
    {
        $sourceData = new SourceDataset();

        $sourceData->source_dataset = '
            {
                "data": {
                    "id": "10.1594/pangaea.937090",
                    "type": "dois",
                    "attributes": {
                        "publicationYear": 2023
                    }
                }
            }';
        $dataciteMapper = new Datacite4Mapper();

        // create empty data publication
        $dataset = new DataPublication;

        // read json text
        $metadata = json_decode($sourceData->source_dataset, true);
        
        $dataset = $dataciteMapper->mapPublicationYear($metadata, $dataset);

        $this->assertEquals($dataset->msl_publication_year, "2023");

    } 


    /**
     * test if rights are correctly mapped
     */
    public function test_rights_mapping(): void
    {
        $sourceData = new SourceDataset();

        $sourceData->source_dataset = '
        {
            "data": {
                "id": "10.1594/pangaea.937090",
                "type": "dois",
                "attributes": {
                    "rightsList": [
                        {
                            "lang": "en",
                            "rights": "Creative Commons Attribution 4.0 International",
                            "rightsUri": "https://creativecommons.org/licenses/by/4.0/legalcode",
                            "schemeUri": "https://spdx.org/licenses/",
                            "rightsIdentifier": "cc-by-4.0",
                            "rightsIdentifierScheme": "SPDX"
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
        
        $dataset = $dataciteMapper->mapRights($metadata, $dataset);

        $this->assertEquals($dataset->msl_rights[0]['msl_right'], "Creative Commons Attribution 4.0 International");
        $this->assertEquals($dataset->msl_rights[0]['msl_right_uri'], "https://creativecommons.org/licenses/by/4.0/legalcode");
        $this->assertEquals($dataset->msl_rights[0]['msl_right_scheme_uri'], "https://spdx.org/licenses/");
        $this->assertEquals($dataset->msl_rights[0]['msl_right_identifier'], "cc-by-4.0");
        $this->assertEquals($dataset->msl_rights[0]['msl_right_identifier_scheme'], "SPDX");

        // new test

        $sourceData = new SourceDataset();

        $sourceData->source_dataset = '
        {
            "data": {
                "id": "10.1594/pangaea.937090",
                "type": "dois",
                "attributes": {
                    "rightsList": [
                        {
                            "rights": "openAccess"
                        },
                        {
                            "rights": "Creative Commons Attribution 4.0 International",
                            "rightsUri": "https://creativecommons.org/licenses/by/4.0/legalcode",
                            "schemeUri": "https://spdx.org/licenses/",
                            "rightsIdentifier": "cc-by-4.0",
                            "rightsIdentifierScheme": "SPDX"
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
        
        $dataset = $dataciteMapper->mapRights($metadata, $dataset);


        $this->assertEquals($dataset->msl_rights[0]['msl_right'], "openAccess");
        $this->assertEquals($dataset->msl_rights[1]['msl_right'], "Creative Commons Attribution 4.0 International");

        $this->assertEquals($dataset->msl_rights[1]['msl_right_uri'], "https://creativecommons.org/licenses/by/4.0/legalcode");
        $this->assertEquals($dataset->msl_rights[1]['msl_right_scheme_uri'], "https://spdx.org/licenses/");
        $this->assertEquals($dataset->msl_rights[1]['msl_right_identifier'], "cc-by-4.0");
        $this->assertEquals($dataset->msl_rights[1]['msl_right_identifier_scheme'], "SPDX");
    }   

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


    /**
     * test if related identifier are correctly mapped
     */
    public function test_relatedIdentifier_mapping(): void
    {
        $sourceData = new SourceDataset();

        $sourceData->source_dataset = '
            {
                "data": {
                    "id": "10.1594/pangaea.937090",
                    "type": "dois",
                    "attributes": {
                        "relatedIdentifiers": [
                            {
                                "relationType": "IsCitedBy",
                                "relatedIdentifier": "ark:/13030/tqb3kh97gh8w",
                                "resourceTypeGeneral": "Audiovisual",
                                "relatedIdentifierType": "ARK"
                            },
                            {
                                "relationType": "Cites",
                                "relatedIdentifier": "arXiv:0706.0001",
                                "resourceTypeGeneral": "Book",
                                "relatedIdentifierType": "arXiv"
                            },
                            {
                                "relationType": "IsSupplementedBy",
                                "relatedIdentifier": "10.1016/j.epsl.2011.11.037",
                                "resourceTypeGeneral": "Collection",
                                "relatedIdentifierType": "DOI"
                            },
                            {
                                "relationType": "IsContinuedBy",
                                "relatedIdentifier": "9783468111242",
                                "resourceTypeGeneral": "ComputationalNotebook",
                                "relatedIdentifierType": "EAN13"
                            },
                            {
                                "relationType": "Continues",
                                "relatedIdentifier": "1562-6865",
                                "resourceTypeGeneral": "ConferencePaper",
                                "relatedIdentifierType": "EISSN"
                            },
                            {
                                "relationType": "Describes",
                                "relatedIdentifier": "10013/epic.10033",
                                "resourceTypeGeneral": "ConferenceProceeding",
                                "relatedIdentifierType": "Handle"
                            },
                            {
                                "relationType": "IsDescribedBy",
                                "relatedIdentifier": "IECUR0097",
                                "resourceTypeGeneral": "DataPaper",
                                "relatedIdentifierType": "IGSN"
                            },
                            {
                                "relationType": "HasMetadata",
                                "relatedIdentifier": "978-3-905673-82-1",
                                "resourceTypeGeneral": "Dataset",
                                "relatedIdentifierType": "ISBN"
                            },
                            {
                                "relationType": "IsMetadataFor",
                                "relatedIdentifier": "0077-5606",
                                "resourceTypeGeneral": "Dissertation",
                                "relatedIdentifierType": "ISSN"
                            },
                            {
                                "relationType": "IsNewVersionOf",
                                "relatedIdentifier": "urn:lsid:ubio.org:namebank:11815",
                                "resourceTypeGeneral": "InteractiveResource",
                                "relatedIdentifierType": "LSID"
                            },
                            {
                                "relationType": "IsPreviousVersionOf",
                                "relatedIdentifier": "12082125",
                                "resourceTypeGeneral": "Journal",
                                "relatedIdentifierType": "PMID"
                            },
                            {
                                "relationType": "IsPartOf",
                                "relatedIdentifier": "http://purl.oclc.org/foo/bar",
                                "resourceTypeGeneral": "JournalArticle",
                                "relatedIdentifierType": "PURL"
                            },
                            {
                                "relationType": "HasPart",
                                "relatedIdentifier": "123456789999",
                                "resourceTypeGeneral": "Model",
                                "relatedIdentifierType": "UPC"
                            },
                            {
                                "relationType": "IsPublishedIn",
                                "relatedIdentifier": "http://www.heatflow.und.edu/index2.html",
                                "resourceTypeGeneral": "OutputManagementPlan",
                                "relatedIdentifierType": "URL"
                            },
                            {
                                "relationType": "IsReferencedBy",
                                "relatedIdentifier": "urn:nbn:de:101:1-201102033592",
                                "resourceTypeGeneral": "PeerReview",
                                "relatedIdentifierType": "URN"
                            },        
                            {
                                "relationType": "IsDerivedFrom",
                                "relatedIdentifier": "10.1016/j.epsl.2011.11.037",
                                "resourceTypeGeneral": "Other",
                                "relatedIdentifierType": "DOI"
                            },
                            {
                                "relationType": "IsSourceOf",
                                "relatedIdentifier": "10.1016/j.epsl.2011.11.037",
                                "resourceTypeGeneral": "Other",
                                "relatedIdentifierType": "DOI"
                            },
                            {
                                "relationType": "IsRequiredBy",
                                "relatedIdentifier": "10.1016/j.epsl.2011.11.037",
                                "resourceTypeGeneral": "Other",
                                "relatedIdentifierType": "DOI"
                            },
                            {
                                "relationType": "Requires",
                                "relatedIdentifier": "10.1016/j.epsl.2011.11.037",
                                "resourceTypeGeneral": "Other",
                                "relatedIdentifierType": "DOI"
                            },
                            {
                                "relationType": "Obsoletes",
                                "relatedIdentifier": "10.1016/j.epsl.2011.11.037",
                                "resourceTypeGeneral": "Other",
                                "relatedIdentifierType": "DOI"
                            },
                            {
                                "relationType": "IsObsoletedBy",
                                "relatedIdentifier": "10.1016/j.epsl.2011.11.037",
                                "resourceTypeGeneral": "Other",
                                "relatedIdentifierType": "DOI"
                            },
                            {
                                "relationType": "Collects",
                                "relatedIdentifier": "10.1016/j.epsl.2011.11.037",
                                "resourceTypeGeneral": "Other",
                                "relatedIdentifierType": "DOI"
                            },
                            {
                                "relationType": "IsCollectedBy",
                                "relatedIdentifier": "10.1016/j.epsl.2011.11.037",
                                "resourceTypeGeneral": "Other",
                                "relatedIdentifierType": "DOI"
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
        
        $dataset = $dataciteMapper->mapRelatedIdentifier($metadata, $dataset);

        $counter = 0;
        foreach ($dataset->msl_related_identifiers as $entry) {
            if(isset($entry['msl_related_identifier'])){
                ($entry['msl_related_identifier'] != "" ? $counter++ : false);
            }
        }

        $this->assertEquals(sizeof($dataset->msl_related_identifiers), $counter);

        $this->assertEquals($dataset->msl_related_identifiers [0]["msl_related_identifier_relation_type"            ],    "IsCitedBy");
        $this->assertEquals($dataset->msl_related_identifiers [0]["msl_related_identifier"                          ],    "ark:/13030/tqb3kh97gh8w");
        $this->assertEquals($dataset->msl_related_identifiers [0]["msl_related_identifier_resource_type_general"    ],    "Audiovisual");
        $this->assertEquals($dataset->msl_related_identifiers [0]["msl_related_identifier_type"                     ],    "ARK");
        $this->assertEquals($dataset->msl_related_identifiers [1]["msl_related_identifier_relation_type"            ],    "Cites");
        $this->assertEquals($dataset->msl_related_identifiers [1]["msl_related_identifier"                          ],    "arXiv:0706.0001");
        $this->assertEquals($dataset->msl_related_identifiers [1]["msl_related_identifier_resource_type_general"    ],    "Book");
        $this->assertEquals($dataset->msl_related_identifiers [1]["msl_related_identifier_type"                     ],    "arXiv");
        $this->assertEquals($dataset->msl_related_identifiers [2]["msl_related_identifier_relation_type"            ],    "IsSupplementedBy");
        $this->assertEquals($dataset->msl_related_identifiers [2]["msl_related_identifier"                          ],    "10.1016/j.epsl.2011.11.037");
        $this->assertEquals($dataset->msl_related_identifiers [2]["msl_related_identifier_resource_type_general"    ],    "Collection");
        $this->assertEquals($dataset->msl_related_identifiers [2]["msl_related_identifier_type"                     ],    "DOI");
        $this->assertEquals($dataset->msl_related_identifiers [3]["msl_related_identifier_relation_type"            ],    "IsContinuedBy");
        $this->assertEquals($dataset->msl_related_identifiers [3]["msl_related_identifier"                          ],    "9783468111242");
        $this->assertEquals($dataset->msl_related_identifiers [3]["msl_related_identifier_resource_type_general"    ],    "ComputationalNotebook");
        $this->assertEquals($dataset->msl_related_identifiers [3]["msl_related_identifier_type"                     ],    "EAN13");
        $this->assertEquals($dataset->msl_related_identifiers [4]["msl_related_identifier_relation_type"            ],    "Continues");
        $this->assertEquals($dataset->msl_related_identifiers [4]["msl_related_identifier"                          ],    "1562-6865");
        $this->assertEquals($dataset->msl_related_identifiers [4]["msl_related_identifier_resource_type_general"    ],    "ConferencePaper");
        $this->assertEquals($dataset->msl_related_identifiers [4]["msl_related_identifier_type"                     ],    "EISSN");
        $this->assertEquals($dataset->msl_related_identifiers [5]["msl_related_identifier_relation_type"            ],    "Describes");
        $this->assertEquals($dataset->msl_related_identifiers [5]["msl_related_identifier"                          ],    "10013/epic.10033");
        $this->assertEquals($dataset->msl_related_identifiers [5]["msl_related_identifier_resource_type_general"    ],    "ConferenceProceeding");
        $this->assertEquals($dataset->msl_related_identifiers [5]["msl_related_identifier_type"                     ],    "Handle");
        $this->assertEquals($dataset->msl_related_identifiers [6]["msl_related_identifier_relation_type"            ],    "IsDescribedBy");
        $this->assertEquals($dataset->msl_related_identifiers [6]["msl_related_identifier"                          ],    "IECUR0097");
        $this->assertEquals($dataset->msl_related_identifiers [6]["msl_related_identifier_resource_type_general"    ],    "DataPaper");
        $this->assertEquals($dataset->msl_related_identifiers [6]["msl_related_identifier_type"                     ],    "IGSN");
        $this->assertEquals($dataset->msl_related_identifiers [7]["msl_related_identifier_relation_type"            ],    "HasMetadata");
        $this->assertEquals($dataset->msl_related_identifiers [7]["msl_related_identifier"                          ],    "978-3-905673-82-1");
        $this->assertEquals($dataset->msl_related_identifiers [7]["msl_related_identifier_resource_type_general"    ],    "Dataset");
        $this->assertEquals($dataset->msl_related_identifiers [7]["msl_related_identifier_type"                     ],    "ISBN");
        $this->assertEquals($dataset->msl_related_identifiers [8]["msl_related_identifier_relation_type"            ],    "IsMetadataFor");
        $this->assertEquals($dataset->msl_related_identifiers [8]["msl_related_identifier"                          ],    "0077-5606");
        $this->assertEquals($dataset->msl_related_identifiers [8]["msl_related_identifier_resource_type_general"    ],    "Dissertation");
        $this->assertEquals($dataset->msl_related_identifiers [8]["msl_related_identifier_type"                     ],    "ISSN");
        $this->assertEquals($dataset->msl_related_identifiers [9]["msl_related_identifier_relation_type"            ],    "IsNewVersionOf");
        $this->assertEquals($dataset->msl_related_identifiers [9]["msl_related_identifier"                          ],    "urn:lsid:ubio.org:namebank:11815");
        $this->assertEquals($dataset->msl_related_identifiers [9]["msl_related_identifier_resource_type_general"    ],    "InteractiveResource");
        $this->assertEquals($dataset->msl_related_identifiers [9]["msl_related_identifier_type"                     ],    "LSID");
        $this->assertEquals($dataset->msl_related_identifiers [10]["msl_related_identifier_relation_type"           ],    "IsPreviousVersionOf");
        $this->assertEquals($dataset->msl_related_identifiers [10]["msl_related_identifier"                         ],    "12082125");
        $this->assertEquals($dataset->msl_related_identifiers [10]["msl_related_identifier_resource_type_general"   ],    "Journal");
        $this->assertEquals($dataset->msl_related_identifiers [10]["msl_related_identifier_type"                    ],    "PMID");
        $this->assertEquals($dataset->msl_related_identifiers [11]["msl_related_identifier_relation_type"           ],    "IsPartOf");
        $this->assertEquals($dataset->msl_related_identifiers [11]["msl_related_identifier"                         ],    "http://purl.oclc.org/foo/bar");
        $this->assertEquals($dataset->msl_related_identifiers [11]["msl_related_identifier_resource_type_general"   ],    "JournalArticle");
        $this->assertEquals($dataset->msl_related_identifiers [11]["msl_related_identifier_type"                    ],    "PURL");
        $this->assertEquals($dataset->msl_related_identifiers [12]["msl_related_identifier_relation_type"           ],    "HasPart");
        $this->assertEquals($dataset->msl_related_identifiers [12]["msl_related_identifier"                         ],    "123456789999");
        $this->assertEquals($dataset->msl_related_identifiers [12]["msl_related_identifier_resource_type_general"   ],    "Model");
        $this->assertEquals($dataset->msl_related_identifiers [12]["msl_related_identifier_type"                    ],    "UPC");
        $this->assertEquals($dataset->msl_related_identifiers [13]["msl_related_identifier_relation_type"           ],    "IsPublishedIn");
        $this->assertEquals($dataset->msl_related_identifiers [13]["msl_related_identifier"                         ],    "http://www.heatflow.und.edu/index2.html");
        $this->assertEquals($dataset->msl_related_identifiers [13]["msl_related_identifier_resource_type_general"   ],    "OutputManagementPlan");
        $this->assertEquals($dataset->msl_related_identifiers [13]["msl_related_identifier_type"                    ],    "URL");
        $this->assertEquals($dataset->msl_related_identifiers [14]["msl_related_identifier_relation_type"           ],    "IsReferencedBy");
        $this->assertEquals($dataset->msl_related_identifiers [14]["msl_related_identifier"                         ],    "urn:nbn:de:101:1-201102033592");
        $this->assertEquals($dataset->msl_related_identifiers [14]["msl_related_identifier_resource_type_general"   ],    "PeerReview");
        $this->assertEquals($dataset->msl_related_identifiers [14]["msl_related_identifier_type"                    ],    "URN"    );
        $this->assertEquals($dataset->msl_related_identifiers [15]["msl_related_identifier_relation_type"           ],    "IsDerivedFrom");
        $this->assertEquals($dataset->msl_related_identifiers [15]["msl_related_identifier"                         ],    "10.1016/j.epsl.2011.11.037");
        $this->assertEquals($dataset->msl_related_identifiers [15]["msl_related_identifier_resource_type_general"   ],    "Other");
        $this->assertEquals($dataset->msl_related_identifiers [15]["msl_related_identifier_type"                    ],    "DOI");
        $this->assertEquals($dataset->msl_related_identifiers [16]["msl_related_identifier_relation_type"           ],    "IsSourceOf");
        $this->assertEquals($dataset->msl_related_identifiers [16]["msl_related_identifier"                         ],    "10.1016/j.epsl.2011.11.037");
        $this->assertEquals($dataset->msl_related_identifiers [16]["msl_related_identifier_resource_type_general"   ],    "Other");
        $this->assertEquals($dataset->msl_related_identifiers [16]["msl_related_identifier_type"                    ],    "DOI");
        $this->assertEquals($dataset->msl_related_identifiers [17]["msl_related_identifier_relation_type"           ],    "IsRequiredBy");
        $this->assertEquals($dataset->msl_related_identifiers [17]["msl_related_identifier"                         ],    "10.1016/j.epsl.2011.11.037");
        $this->assertEquals($dataset->msl_related_identifiers [17]["msl_related_identifier_resource_type_general"   ],    "Other");
        $this->assertEquals($dataset->msl_related_identifiers [17]["msl_related_identifier_type"                    ],    "DOI");
        $this->assertEquals($dataset->msl_related_identifiers [18]["msl_related_identifier_relation_type"           ],    "Requires");
        $this->assertEquals($dataset->msl_related_identifiers [18]["msl_related_identifier"                         ],    "10.1016/j.epsl.2011.11.037");
        $this->assertEquals($dataset->msl_related_identifiers [18]["msl_related_identifier_resource_type_general"   ],    "Other");
        $this->assertEquals($dataset->msl_related_identifiers [18]["msl_related_identifier_type"                    ],    "DOI");
        $this->assertEquals($dataset->msl_related_identifiers [19]["msl_related_identifier_relation_type"           ],    "Obsoletes");
        $this->assertEquals($dataset->msl_related_identifiers [19]["msl_related_identifier"                         ],    "10.1016/j.epsl.2011.11.037");
        $this->assertEquals($dataset->msl_related_identifiers [19]["msl_related_identifier_resource_type_general"   ],    "Other");
        $this->assertEquals($dataset->msl_related_identifiers [19]["msl_related_identifier_type"                    ],    "DOI");
        $this->assertEquals($dataset->msl_related_identifiers [20]["msl_related_identifier_relation_type"           ],    "IsObsoletedBy");
        $this->assertEquals($dataset->msl_related_identifiers [20]["msl_related_identifier"                         ],    "10.1016/j.epsl.2011.11.037");
        $this->assertEquals($dataset->msl_related_identifiers [20]["msl_related_identifier_resource_type_general"   ],    "Other");
        $this->assertEquals($dataset->msl_related_identifiers [20]["msl_related_identifier_type"                    ],    "DOI");
        $this->assertEquals($dataset->msl_related_identifiers [21]["msl_related_identifier_relation_type"           ],    "Collects");
        $this->assertEquals($dataset->msl_related_identifiers [21]["msl_related_identifier"                         ],    "10.1016/j.epsl.2011.11.037");
        $this->assertEquals($dataset->msl_related_identifiers [21]["msl_related_identifier_resource_type_general"   ],    "Other");
        $this->assertEquals($dataset->msl_related_identifiers [21]["msl_related_identifier_type"                    ],    "DOI");
        $this->assertEquals($dataset->msl_related_identifiers [22]["msl_related_identifier_relation_type"           ],    "IsCollectedBy");
        $this->assertEquals($dataset->msl_related_identifiers [22]["msl_related_identifier"                         ],    "10.1016/j.epsl.2011.11.037");
        $this->assertEquals($dataset->msl_related_identifiers [22]["msl_related_identifier_resource_type_general"   ],    "Other");
        $this->assertEquals($dataset->msl_related_identifiers [22]["msl_related_identifier_type"                    ],    "DOI");


        // new test
        $sourceData = new SourceDataset();

        $sourceData->source_dataset = '
            {
                "data": {
                    "id": "10.1594/pangaea.937090",
                    "type": "dois",
                    "attributes": {
                        "relatedIdentifiers": [
                            {
                                "relationType": "IsSupplementTo",
                                "relatedIdentifier": "10.1007/s10346-021-01787-2",
                                "relatedIdentifierType": "DOI"
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
        
        $dataset = $dataciteMapper->mapRelatedIdentifier($metadata, $dataset);

        $this->assertEquals($dataset->msl_related_identifiers [0]["msl_related_identifier_relation_type"           ],    "IsSupplementTo");
        $this->assertEquals($dataset->msl_related_identifiers [0]["msl_related_identifier"                         ],    "10.1007/s10346-021-01787-2");
        $this->assertEquals($dataset->msl_related_identifiers [0]["msl_related_identifier_type"                    ],    "DOI");


                // new test
                $sourceData = new SourceDataset();

                $sourceData->source_dataset = '
                    {
                        "data": {
                            "id": "10.1594/pangaea.937090",
                            "type": "dois",
                            "attributes": {
                                "relatedIdentifiers": [
                                ]
                            }
                        }
                    }';
        
                $dataciteMapper = new Datacite4Mapper();
        
                // create empty data publication
                $dataset = new DataPublication;
        
                // read json text
                $metadata = json_decode($sourceData->source_dataset, true);
                
                $dataset = $dataciteMapper->mapRelatedIdentifier($metadata, $dataset);
        
                $this->assertEquals($dataset->msl_related_identifiers,    []);
                
        }

            /**
     * test if description is correctly mapped
     */
    public function test_language_mapping(): void
    {
        $sourceData = new SourceDataset();

        $sourceData->source_dataset = '
            {
                "data": {
                    "id": "10.1594/pangaea.937090",
                    "type": "dois",
                    "attributes": {
                        "language": "en"
                    }
                }
            }';

        $dataciteMapper = new Datacite4Mapper();

        // create empty data publication
        $dataset = new DataPublication;

        // read json text
        $metadata = json_decode($sourceData->source_dataset, true);
        
        $dataset = $dataciteMapper->mapLanguage($metadata, $dataset);

        $this->assertEquals($dataset->msl_language , "en");
        
    }   

    /**
     * test if description is correctly mapped
     */
    public function test_date_mapping(): void
    {
        $sourceData = new SourceDataset();

        $sourceData->source_dataset = '
            {
                "data": {
                    "id": "10.1594/pangaea.937090",
                    "type": "dois",
                    "attributes": {
                        "dates": [
                        {
                            "date": "2023-01-01",
                            "dateType": "Accepted"
                        },
                        {
                            "date": "2023-01-01",
                            "dateType": "Available"
                        },
                        {
                            "date": "2023-01-01",
                            "dateType": "Copyrighted"
                        },
                        {
                            "date": "2022-01-01/2022-12-31",
                            "dateType": "Collected"
                        },
                        {
                            "date": "2023-01-01",
                            "dateType": "Created"
                        },
                        {
                            "date": "2023-01-01",
                            "dateType": "Issued"
                        },
                        {
                            "date": "2023-01-01",
                            "dateType": "Submitted"
                        },
                        {
                            "date": "2023-01-01",
                            "dateType": "Updated"
                        },
                        {
                            "date": "2023-01-01",
                            "dateType": "Valid"
                        },
                        {
                            "date": "2023-01-01",
                            "dateType": "Withdrawn"
                        },
                        {
                            "date": "2023-01-01",
                            "dateType": "Other",
                            "dateInformation": "ExampleDateInformation"
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

        $dataset = $dataciteMapper->mapDates($metadata, $dataset);

        $this->assertEquals($dataset->msl_dates[0] ["msl_date_date"       ], "2023-01-01");
        $this->assertEquals($dataset->msl_dates[0] ["msl_date_type"       ], "Accepted");
        $this->assertEquals($dataset->msl_dates[1] ["msl_date_date"       ], "2023-01-01");
        $this->assertEquals($dataset->msl_dates[1] ["msl_date_type"       ], "Available");
        $this->assertEquals($dataset->msl_dates[2] ["msl_date_date"       ], "2023-01-01");
        $this->assertEquals($dataset->msl_dates[2] ["msl_date_type"       ], "Copyrighted");
        $this->assertEquals($dataset->msl_dates[3] ["msl_date_date"       ], "2022-01-01/2022-12-31");
        $this->assertEquals($dataset->msl_dates[3] ["msl_date_type"       ], "Collected");
        $this->assertEquals($dataset->msl_dates[4] ["msl_date_date"       ], "2023-01-01");
        $this->assertEquals($dataset->msl_dates[4] ["msl_date_type"       ], "Created");
        $this->assertEquals($dataset->msl_dates[5] ["msl_date_date"       ], "2023-01-01");
        $this->assertEquals($dataset->msl_dates[5] ["msl_date_type"       ], "Issued");
        $this->assertEquals($dataset->msl_dates[6] ["msl_date_date"       ], "2023-01-01");
        $this->assertEquals($dataset->msl_dates[6] ["msl_date_type"       ], "Submitted");
        $this->assertEquals($dataset->msl_dates[7] ["msl_date_date"       ], "2023-01-01");
        $this->assertEquals($dataset->msl_dates[7] ["msl_date_type"       ], "Updated");
        $this->assertEquals($dataset->msl_dates[8] ["msl_date_date"       ], "2023-01-01");
        $this->assertEquals($dataset->msl_dates[8] ["msl_date_type"       ], "Valid");
        $this->assertEquals($dataset->msl_dates[9] ["msl_date_date"       ], "2023-01-01");
        $this->assertEquals($dataset->msl_dates[9] ["msl_date_type"       ], "Withdrawn");
        $this->assertEquals($dataset->msl_dates[10]["msl_date_date"       ], "2023-01-01");
        $this->assertEquals($dataset->msl_dates[10]["msl_date_type"       ], "Other");
        $this->assertEquals($dataset->msl_dates[10]["msl_date_information"], "ExampleDateInformation");


        //new test

        $sourceData = new SourceDataset();

        $sourceData->source_dataset = '
            {
                "data": {
                    "id": "10.1594/pangaea.937090",
                    "type": "dois",
                    "attributes": {
                        "dates": [
                        {
                            "date": "2021",
                            "dateType": "Issued"
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

        $dataset = $dataciteMapper->mapDates($metadata, $dataset);

        $this->assertEquals($dataset->msl_dates[0] ["msl_date_date"       ], "2021");
        $this->assertEquals($dataset->msl_dates[0] ["msl_date_type"       ], "Issued");


        //new test

        $sourceData = new SourceDataset();

        $sourceData->source_dataset = '
            {
                "data": {
                    "id": "10.1594/pangaea.937090",
                    "type": "dois",
                    "attributes": {
                        "dates": [
                        ]
                    }
                }
            }';

        $dataciteMapper = new Datacite4Mapper();

        // create empty data publication
        $dataset = new DataPublication;

        // read json text
        $metadata = json_decode($sourceData->source_dataset, true);

        $dataset = $dataciteMapper->mapDates($metadata, $dataset);

        $this->assertEquals($dataset->msl_dates, []);
        

    }   

}
