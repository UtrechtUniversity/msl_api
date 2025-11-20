# EPOS MSL API
The EPOS MSL API offers access to data available within our CKAN portal. This document describes the API per available endpoint.
 A Hoppscotch collection file is available [here](./docs/api/hoppscotch/collections/MSL%20API.json).

## Available resources
The API offers 6 domain specific endpoints, per facilities and data-publications information. All data is open accessible, no authorization is required.
+ [Data-publications](#data-publications)
   + [rock_physics](#dp_rock_physics)
   + [analogue](#dp_analogue)
   + [paleo](#dp_paleo)
   + [microscopy](#dp_microscopy)
   + [geochemistry](#dp_geochemistry)
   + [geoenergy](#dp_geochemistry)
   + [all](#dp_all)
+ [Facilities](#facilities)
   + [rock_physics](#f_rock_physics)
   + [analogue](#f_analogue)
   + [paleo](#f_paleo)
   + [microscopy](#f_microscopy)
   + [geochemistry](#f_geochemistry)
   + [geoenergy](#f_geochemistry)
   + [all](#f_all)

## Base url

The base url for the API:
- For data-publications:
```
https://epos-msl.uu.nl/api/v2/datapublications
```
- For facilities:
```
https://epos-msl.uu.nl/api/v2/facilities
```



- ## Data-Publications {#data-publications}

### /rock_physics {#dp_rock_physics}
This endpoint gives access to all data-publications available that are marked as belonging to the rock physics (sub)domain. 

### Search all rock physics data-publications [GET]
+ Parameters

    + limit (number, optional) - The number of results to return.
        + Default: `10`
    + offset (number, optional) - The number to start results from. 
        + Default: `0`
    + query (text, optional) - Words to search for. 
        + Default: ``
    + authorName (text, optional) - Author names to search for. 
        + Default: ``
    + labName (text, optional) - Lab names to search for. 
        + Default: ``
    + title (text, optional) - Title to search for. 
        + Default: ``
    + tags (text, optional) - Tags to search for. 
        + Default: ``
    + hasDownloads (boolean, optional) - Filter results to only include results with download links.
        + Default: `false`
   + boundingBox 	(minx,miny,maxx,maxy) decimals, optional - Bounding box geographically filtering the results. If provided the bounding box must be valid. bounds: (-180, -90, 180, 90). 
        + Default: ``

        
+ Response 200 (application/json)

<details>
  <summary>view response</summary>
  
```json
 {
    "success": "true",
    "messages": [],
    "meta": {
        "resultCount": 10,
        "totalCount": 321,
        "limit": 10,
        "offset": 0
    },
    "links": {
        "current_url": "https://epos-msl.uu.nl/api/v2/datapublications/rock_physics?offset=0&limit=10"
    },
    "data": [
        {
            "title": "Contact model and numerical modelling results: “Compaction of the Groningen Gas Reservoir Sandstone: Discrete Element Modelling Using Microphysically Based Grain-Scale Interaction Laws”",
            "doi": "10.24416/uu01-575ewu",
            "source": "https://public.yoda.uu.nl/geo/UU01/575EWU.html",
            "portalLink": "http://localhost/data-publication/ad60ac76a738f696d9de7acc1506d491",
            "name": "ad60ac76a738f696d9de7acc1506d491",
            "creators": [
                {
                    "name": "Mohammad Hadi Mehranpour",
                    "nameType": "Personal",
                    "givenName": "Mohammad Hadi",
                    "familyName": "Mehranpour",
                    "nameIdentifiers": [
                        {
                            "nameIdentifier": "0000-0001-7336-0435",
                            "nameIdentifierScheme": "ORCID",
                            "nameIdentifierUri": ""
                        }
                    ],
                    "affiliation": [
                        {
                            "name": "Utrecht University",
                            "affiliationIdentifier": "",
                            "affiliationIdentifierScheme": "",
                            "schemeUri": ""
                        }
                    ]
                }
            ],
            "descriptions": [
                {
                    "description": "Reservoir compaction, surface subsidence and induced seismicity are often associated with prolonged hydrocarbon production. Recent experiments conducted on the Groningen gas field’s Slochteren sandstone reservoir rock, at in-situ conditions, have shown that compaction involves both poro-elastic strain and time-independent, permanent strain caused by consolidation and shear of clay films coating the sandstone grains, with grain failure occurring at higher stresses. To model compaction of the reservoir in space and time, numerical approaches, such as the Discrete Element Method (DEM) , populated with realistic grain-scale mechanisms are needed. We developed a new particle-interaction law (contact model) for the classic DEM to explicitly account for the experimentally observed mechanisms of non-linear elasticity, intergranular clay film deformation, and grain breakage. It was calibrated against both hydrostatic and conventional triaxial compression experiments and validated against an independent set of pore pressure depletion experiments conducted under uniaxial strain conditions, using a range of sample porosities, grain size distributions and clay contents. The model obtained was used to predict compaction of the Groningen reservoir. These results were compared with field measurements of in-situ compaction and matched favorably, within field measurement uncertainties. The new model allows systematic investigation of the effects of mineralogy, microstructure, boundary conditions and loading path on compaction behavior of the reservoir. It also offers a means of generating a data bank suitable for developing generalized constitutive models and for predicting reservoir response to different scenarios of gas extraction, or of fluid injection for stabilization or storage purposes.\n\nThe data provided in this dataset include the contact model (source codes and the contact model library) developed for the Particle Flow Code (PFC) software, Fish code package for running PFC models, numerical modeling results (tabulated) obtained in the calibration procedure and uniaxial compaction prediction.\n",
                    "descriptionType": "Abstract"
                }
            ],
            "contributors": [
                {
                    "name": "Mehranpour, Mohammad Hadi",
                    "contributorType": "DataCollector",
                    "nameType": "Personal",
                    "givenName": "",
                    "familyName": "",
                    "nameIdentifiers": [
                        {
                            "nameIdentifier": "0000-0001-7336-0435",
                            "nameIdentifierScheme": "ORCID",
                            "nameIdentifierUri": ""
                        }
                    ],
                    "affiliation": [
                        {
                            "name": "Utrecht University",
                            "affiliationIdentifier": "",
                            "affiliationIdentifierScheme": "",
                            "schemeUri": ""
                        }
                    ]
                },
                {
                    "name": "Hangx, Suzanne J.T.",
                    "contributorType": "ProjectManager",
                    "nameType": "Personal",
                    "givenName": "",
                    "familyName": "",
                    "nameIdentifiers": [
                        {
                            "nameIdentifier": "0000-0003-2253-3273",
                            "nameIdentifierScheme": "ORCID",
                            "nameIdentifierUri": ""
                        }
                    ],
                    "affiliation": [
                        {
                            "name": "Utrecht University",
                            "affiliationIdentifier": "",
                            "affiliationIdentifierScheme": "",
                            "schemeUri": ""
                        }
                    ]
                },
                {
                    "name": "Spiers, Christopher James",
                    "contributorType": "ProjectLeader",
                    "nameType": "Personal",
                    "givenName": "",
                    "familyName": "",
                    "nameIdentifiers": [
                        {
                            "nameIdentifier": "0000-0002-3436-8941",
                            "nameIdentifierScheme": "ORCID",
                            "nameIdentifierUri": ""
                        }
                    ],
                    "affiliation": [
                        {
                            "name": "Utrecht University",
                            "affiliationIdentifier": "",
                            "affiliationIdentifierScheme": "",
                            "schemeUri": ""
                        }
                    ]
                }
            ],
            "materials": [
                "sedimentary rock",
                "sandstone",
                "minerals",
                "silicate minerals",
                "tectosilicates",
                "quartz",
                "wacke",
                "Slochteren sandstone",
                "phyllosilicates",
                "clay",
                "unconsolidated sediment",
                "clastic sediment",
                "clay"
            ],
            "researchAspects": [
                "deformation behaviour",
                "inelastic deformation",
                "strain",
                "microphysical deformation mechanism",
                "intragranular cracking",
                "mechanical strength",
                "triaxial compressive strength (s1>s2=s3)",
                "elasticity",
                "bulk modulus",
                "poroelastic deformation",
                "elastic strain",
                "inelastic strain",
                "pore fluid pressure"
            ],
            "files": [
                {
                    "fileName": "calibration",
                    "downloadLink": "https://geo.public.data.uu.nl:443/vault-sandstone-compaction/Mehranpour_et_al_2021_DEModeling[1621322230]/original/calibration/",
                    "extension": ""
                },
                {
                    "fileName": "contact model",
                    "downloadLink": "https://geo.public.data.uu.nl:443/vault-sandstone-compaction/Mehranpour_et_al_2021_DEModeling[1621322230]/original/contact model/",
                    "extension": ""
                },
                {
                    "fileName": "PFC code",
                    "downloadLink": "https://geo.public.data.uu.nl:443/vault-sandstone-compaction/Mehranpour_et_al_2021_DEModeling[1621322230]/original/PFC code/",
                    "extension": ""
                },
                {
                    "fileName": "uniaxial compaction",
                    "downloadLink": "https://geo.public.data.uu.nl:443/vault-sandstone-compaction/Mehranpour_et_al_2021_DEModeling[1621322230]/original/uniaxial compaction/",
                    "extension": ""
                },
                {
                    "fileName": "Mehranpour_et-al_2021-description.docx",
                    "downloadLink": "https://geo.public.data.uu.nl:443/vault-sandstone-compaction/Mehranpour_et_al_2021_DEModeling[1621322230]/original/Mehranpour_et-al_2021-description.docx",
                    "extension": "docx"
                },
                {
                    "fileName": "yoda-metadata.json",
                    "downloadLink": "https://geo.public.data.uu.nl:443/vault-sandstone-compaction/Mehranpour_et_al_2021_DEModeling[1621322230]/original/yoda-metadata.json",
                    "extension": "json"
                }
            ],
            "resource_type": "Research Data",
            "resource_type_general": "Dataset",
            "publication_year": "2021",
            "language": "en",
            "publisher": "Utrecht University",
            "citation": "Mehranpour, M. H. (2021). <i>Contact model and numerical modelling results: “Compaction of the Groningen Gas Reservoir Sandstone: Discrete Element Modelling Using Microphysically Based Grain-Scale Interaction Laws”</i> (Version 1.0) [Data set]. Utrecht University. https://doi.org/10.24416/UU01-575EWU",
            "geojson": {
                "type": "FeatureCollection",
                "features": [
                    {
                        "type": "Feature",
                        "geometry": {
                            "type": "Polygon",
                            "coordinates": [
                                [
                                    [
                                        6.76325824029,
                                        53.3471390085
                                    ],
                                    [
                                        6.76325824029,
                                        53.3504181107
                                    ],
                                    [
                                        6.76068433733,
                                        53.3504181107
                                    ],
                                    [
                                        6.76068433733,
                                        53.3471390085
                                    ],
                                    [
                                        6.76325824029,
                                        53.3471390085
                                    ]
                                ]
                            ]
                        },
                        "properties": {
                            "name": "Groningen gas field’s Slochteren sandstone reservoir rock"
                        }
                    }
                ]
            },
            "surface_area": 0,
            "rightsList": [
                {
                    "rights": "Open - freely retrievable",
                    "rightsUri": "info:eu-repo/semantics/openAccess",
                    "rightsIdentifier": "",
                    "rightsIdentifierScheme": "",
                    "rightsSchemeUri": ""
                },
                {
                    "rights": "Creative Commons Attribution 4.0 International Public License",
                    "rightsUri": "https://creativecommons.org/licenses/by/4.0/legalcode",
                    "rightsIdentifier": "",
                    "rightsIdentifierScheme": "",
                    "rightsSchemeUri": ""
                }
            ],
            "alternateIdentifier": [],
            "fundingReferences": [
                {
                    "funderName": "Netherlands Research Council (NWO)",
                    "funderIdentifier": "",
                    "funderIdentifierType": "",
                    "schemeUri": "",
                    "awardNumber": "",
                    "awardUri": "",
                    "awardTitle": ""
                }
            ],
            "dates": [
                {
                    "date": "2021-05-18T11:05:08",
                    "dateType": "Updated",
                    "dateInformation": ""
                },
                {
                    "date": "2019-06-01/2020-01-01",
                    "dateType": "Collected",
                    "dateInformation": ""
                }
            ],
            "sizes": [],
            "formats": [],
            "laboratories": [],
            "relatedIdentifiers": [
                {
                    "relatedIdentifier": "https://doi.org/10.1029/2019JB018702",
                    "relatedIdentifierType": "DOI",
                    "relationType": "IsSupplementedBy",
                    "relatedMetadataScheme": "",
                    "schemeUri": "",
                    "schemeType": "",
                    "resourceTypeGeneral": ""
                },
                {
                    "relatedIdentifier": "https://doi.org/10.1029/2019JB017366",
                    "relatedIdentifierType": "DOI",
                    "relationType": "IsSupplementedBy",
                    "relatedMetadataScheme": "",
                    "schemeUri": "",
                    "schemeType": "",
                    "resourceTypeGeneral": ""
                }
            ],
            "subjects": [
                {
                    "subject": "Rock and melt physical properties",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "OECD FOS 2007",
                    "classificationCode": "",
                    "EPOS_Uris": []
                },
                {
                    "subject": "Discrete Element Method (DEM)",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "Keyword",
                    "classificationCode": "",
                    "EPOS_Uris": []
                },
                {
                    "subject": "Particle Flow Code (PFC)",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "Keyword",
                    "classificationCode": "",
                    "EPOS_Uris": []
                },
                {
                    "subject": "Inelastic deformation",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "Keyword",
                    "classificationCode": "",
                    "EPOS_Uris": [
                        "https://epos-msl.uu.nl/voc/rockphysics/1.3/inferred_deformation_behavior-deformation_behaviour-inelastic_deformation"
                    ]
                },
                {
                    "subject": "Compaction",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "Keyword",
                    "classificationCode": "",
                    "EPOS_Uris": [
                        "https://epos-msl.uu.nl/voc/rockphysics/1.3/measured_property-strain",
                        "https://epos-msl.uu.nl/voc/analoguemodelling/1.3/measured_property-strain"
                    ]
                },
                {
                    "subject": "Sandstone",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "Keyword",
                    "classificationCode": "",
                    "EPOS_Uris": [
                        "https://epos-msl.uu.nl/voc/materials/1.3/sedimentary_rock-sandstone"
                    ]
                },
                {
                    "subject": "EPOS",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "Keyword",
                    "classificationCode": "",
                    "EPOS_Uris": []
                },
                {
                    "subject": "Multi-scale laboratories",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "Keyword",
                    "classificationCode": "",
                    "EPOS_Uris": []
                },
                {
                    "subject": "Clay film",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "Keyword",
                    "classificationCode": "",
                    "EPOS_Uris": []
                },
                {
                    "subject": "Quartz",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "Keyword",
                    "classificationCode": "",
                    "EPOS_Uris": [
                        "https://epos-msl.uu.nl/voc/materials/1.3/minerals-silicate_minerals-tectosilicates-quartz"
                    ]
                },
                {
                    "subject": "Gas reservoir",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "Keyword",
                    "classificationCode": "",
                    "EPOS_Uris": [
                        "https://epos-msl.uu.nl/voc/subsurface/1.3/subsurface_energy_production-hydrocarbon_energy_production-gas_field"
                    ]
                },
                {
                    "subject": "Subsidence",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "Keyword",
                    "classificationCode": "",
                    "EPOS_Uris": [
                        "https://epos-msl.uu.nl/voc/subsurface/1.3/surface_subsidence"
                    ]
                },
                {
                    "subject": "Induced seismicity",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "Keyword",
                    "classificationCode": "",
                    "EPOS_Uris": [
                        "https://epos-msl.uu.nl/voc/subsurface/1.3/induced_seismicity"
                    ]
                },
                {
                    "subject": "Contact model",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "Keyword",
                    "classificationCode": "",
                    "EPOS_Uris": []
                },
                {
                    "subject": "Nonlinear elasticity",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "Keyword",
                    "classificationCode": "",
                    "EPOS_Uris": []
                },
                {
                    "subject": "permanent time-independent deformation",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "Keyword",
                    "classificationCode": "",
                    "EPOS_Uris": []
                },
                {
                    "subject": "Intragranular failure",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "Keyword",
                    "classificationCode": "",
                    "EPOS_Uris": [
                        "https://epos-msl.uu.nl/voc/rockphysics/1.3/inferred_deformation_behavior-microphysical_deformation_mechanism-intragranular_cracking",
                        "https://epos-msl.uu.nl/voc/microscopy/1.3/analyzed_feature-deformation_microstructure-brittle_microstructure-intragranular_crack"
                    ]
                },
                {
                    "subject": "Numerical modeling",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "Keyword",
                    "classificationCode": "",
                    "EPOS_Uris": []
                },
                {
                    "subject": "Grain failure",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "Keyword",
                    "classificationCode": "",
                    "EPOS_Uris": [
                        "https://epos-msl.uu.nl/voc/rockphysics/1.3/inferred_deformation_behavior-microphysical_deformation_mechanism-intragranular_cracking",
                        "https://epos-msl.uu.nl/voc/microscopy/1.3/analyzed_feature-deformation_microstructure-brittle_microstructure-intragranular_crack"
                    ]
                },
                {
                    "subject": "sandstone",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "Material",
                    "classificationCode": "",
                    "EPOS_Uris": [
                        "https://epos-msl.uu.nl/voc/materials/1.3/sedimentary_rock-sandstone"
                    ]
                },
                {
                    "subject": "Triaxial",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "Apparatus",
                    "classificationCode": "",
                    "EPOS_Uris": []
                },
                {
                    "subject": "Strain gauge",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "Monitoring",
                    "classificationCode": "",
                    "EPOS_Uris": []
                },
                {
                    "subject": "Triaxial Compressive Strength",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "Measured_Property",
                    "classificationCode": "",
                    "EPOS_Uris": [
                        "https://epos-msl.uu.nl/voc/rockphysics/1.3/measured_property-mechanical_strength-triaxial_compressive_strength_s1-s2s3"
                    ]
                },
                {
                    "subject": "Young’s Modulus",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "Measured_Property",
                    "classificationCode": "",
                    "EPOS_Uris": []
                },
                {
                    "subject": "Bulk Modulus",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "Measured_Property",
                    "classificationCode": "",
                    "EPOS_Uris": [
                        "https://epos-msl.uu.nl/voc/rockphysics/1.3/measured_property-elasticity-bulk_modulus",
                        "https://epos-msl.uu.nl/voc/rockphysics/1.3/inferred_deformation_behavior-deformation_behaviour-poroelastic_deformation",
                        "https://epos-msl.uu.nl/voc/analoguemodelling/1.3/measured_property-elasticity-bulk_modulus"
                    ]
                }
            ],
            "subdomains": [
                "rock and melt physics",
                "analogue modelling of geologic processes",
                "microscopy and tomography"
            ]
        }
    ]
}
```

</details>

### /analogue {#dp_analogue}
This endpoint gives access to all data-publications available that are marked as belonging to the analogue modelling (sub)domain. 

### Search all analogue modelling data-publications [GET]
+ Parameters

    + limit (number, optional) - The number of results to return.
        + Default: `10`
    + offset (number, optional) - The number to start results from. 
        + Default: `0`
    + query (text, optional) - Words to search for. 
        + Default: ``
    + authorName (text, optional) - Author names to search for. 
        + Default: ``
    + labName (text, optional) - Lab names to search for. 
        + Default: ``
    + title (text, optional) - Title to search for. 
        + Default: ``
    + tags (text, optional) - Tags to search for. 
        + Default: ``
    + hasDownloads (boolean, optional) - Filter results to only include results with download links.
        + Default: `false`
    + boundingBox 	(minx,miny,maxx,maxy) decimals, optional - Bounding box geographically filtering the results. If provided the bounding box must be valid. bounds: (-180, -90, 180, 90). 
        + Default: ``

        
+ Response 200 (application/json)

<details>
  <summary>view response</summary>
  
```json
{
    "success": "true",
    "messages": [],
    "meta": {
        "resultCount": 10,
        "totalCount": 321,
        "limit": 10,
        "offset": 0
    },
    "links": {
        "current_url": "https://epos-msl.uu.nl/api/v2/datapublications/analogue?offset=0&limit=10"
    },
    "data": [
        {
            "title": "Contact model and numerical modelling results: “Compaction of the Groningen Gas Reservoir Sandstone: Discrete Element Modelling Using Microphysically Based Grain-Scale Interaction Laws”",
            "doi": "10.24416/uu01-575ewu",
            "source": "https://public.yoda.uu.nl/geo/UU01/575EWU.html",
            "portalLink": "http://localhost/data-publication/ad60ac76a738f696d9de7acc1506d491",
            "name": "ad60ac76a738f696d9de7acc1506d491",
            "creators": [
                {
                    "name": "Mohammad Hadi Mehranpour",
                    "nameType": "Personal",
                    "givenName": "Mohammad Hadi",
                    "familyName": "Mehranpour",
                    "nameIdentifiers": [
                        {
                            "nameIdentifier": "0000-0001-7336-0435",
                            "nameIdentifierScheme": "ORCID",
                            "nameIdentifierUri": ""
                        }
                    ],
                    "affiliation": [
                        {
                            "name": "Utrecht University",
                            "affiliationIdentifier": "",
                            "affiliationIdentifierScheme": "",
                            "schemeUri": ""
                        }
                    ]
                }
            ],
            "descriptions": [
                {
                    "description": "Reservoir compaction, surface subsidence and induced seismicity are often associated with prolonged hydrocarbon production. Recent experiments conducted on the Groningen gas field’s Slochteren sandstone reservoir rock, at in-situ conditions, have shown that compaction involves both poro-elastic strain and time-independent, permanent strain caused by consolidation and shear of clay films coating the sandstone grains, with grain failure occurring at higher stresses. To model compaction of the reservoir in space and time, numerical approaches, such as the Discrete Element Method (DEM) , populated with realistic grain-scale mechanisms are needed. We developed a new particle-interaction law (contact model) for the classic DEM to explicitly account for the experimentally observed mechanisms of non-linear elasticity, intergranular clay film deformation, and grain breakage. It was calibrated against both hydrostatic and conventional triaxial compression experiments and validated against an independent set of pore pressure depletion experiments conducted under uniaxial strain conditions, using a range of sample porosities, grain size distributions and clay contents. The model obtained was used to predict compaction of the Groningen reservoir. These results were compared with field measurements of in-situ compaction and matched favorably, within field measurement uncertainties. The new model allows systematic investigation of the effects of mineralogy, microstructure, boundary conditions and loading path on compaction behavior of the reservoir. It also offers a means of generating a data bank suitable for developing generalized constitutive models and for predicting reservoir response to different scenarios of gas extraction, or of fluid injection for stabilization or storage purposes.\n\nThe data provided in this dataset include the contact model (source codes and the contact model library) developed for the Particle Flow Code (PFC) software, Fish code package for running PFC models, numerical modeling results (tabulated) obtained in the calibration procedure and uniaxial compaction prediction.\n",
                    "descriptionType": "Abstract"
                }
            ],
            "contributors": [
                {
                    "name": "Mehranpour, Mohammad Hadi",
                    "contributorType": "DataCollector",
                    "nameType": "Personal",
                    "givenName": "",
                    "familyName": "",
                    "nameIdentifiers": [
                        {
                            "nameIdentifier": "0000-0001-7336-0435",
                            "nameIdentifierScheme": "ORCID",
                            "nameIdentifierUri": ""
                        }
                    ],
                    "affiliation": [
                        {
                            "name": "Utrecht University",
                            "affiliationIdentifier": "",
                            "affiliationIdentifierScheme": "",
                            "schemeUri": ""
                        }
                    ]
                },
                {
                    "name": "Hangx, Suzanne J.T.",
                    "contributorType": "ProjectManager",
                    "nameType": "Personal",
                    "givenName": "",
                    "familyName": "",
                    "nameIdentifiers": [
                        {
                            "nameIdentifier": "0000-0003-2253-3273",
                            "nameIdentifierScheme": "ORCID",
                            "nameIdentifierUri": ""
                        }
                    ],
                    "affiliation": [
                        {
                            "name": "Utrecht University",
                            "affiliationIdentifier": "",
                            "affiliationIdentifierScheme": "",
                            "schemeUri": ""
                        }
                    ]
                },
            ],
            "materials": [
                "sedimentary rock",
                "sandstone",
                "minerals",
                "silicate minerals",
                "tectosilicates",
                "quartz",
                "wacke",
                "Slochteren sandstone",
                "phyllosilicates",
                "clay",
                "unconsolidated sediment",
                "clastic sediment",
                "clay"
            ],
            "researchAspects": [
                "strain",
                "elasticity",
                "bulk modulus",
                "elastic strain",
                "inelastic strain"
            ],
            "files": [
                {
                    "fileName": "calibration",
                    "downloadLink": "https://geo.public.data.uu.nl:443/vault-sandstone-compaction/Mehranpour_et_al_2021_DEModeling[1621322230]/original/calibration/",
                    "extension": ""
                },
                {
                    "fileName": "contact model",
                    "downloadLink": "https://geo.public.data.uu.nl:443/vault-sandstone-compaction/Mehranpour_et_al_2021_DEModeling[1621322230]/original/contact model/",
                    "extension": ""
                },
                {
                    "fileName": "PFC code",
                    "downloadLink": "https://geo.public.data.uu.nl:443/vault-sandstone-compaction/Mehranpour_et_al_2021_DEModeling[1621322230]/original/PFC code/",
                    "extension": ""
                },
                {
                    "fileName": "uniaxial compaction",
                    "downloadLink": "https://geo.public.data.uu.nl:443/vault-sandstone-compaction/Mehranpour_et_al_2021_DEModeling[1621322230]/original/uniaxial compaction/",
                    "extension": ""
                },
                {
                    "fileName": "Mehranpour_et-al_2021-description.docx",
                    "downloadLink": "https://geo.public.data.uu.nl:443/vault-sandstone-compaction/Mehranpour_et_al_2021_DEModeling[1621322230]/original/Mehranpour_et-al_2021-description.docx",
                    "extension": "docx"
                },
                {
                    "fileName": "yoda-metadata.json",
                    "downloadLink": "https://geo.public.data.uu.nl:443/vault-sandstone-compaction/Mehranpour_et_al_2021_DEModeling[1621322230]/original/yoda-metadata.json",
                    "extension": "json"
                }
            ],
            "resource_type": "Research Data",
            "resource_type_general": "Dataset",
            "publication_year": "2021",
            "language": "en",
            "publisher": "Utrecht University",
            "citation": "Mehranpour, M. H. (2021). <i>Contact model and numerical modelling results: “Compaction of the Groningen Gas Reservoir Sandstone: Discrete Element Modelling Using Microphysically Based Grain-Scale Interaction Laws”</i> (Version 1.0) [Data set]. Utrecht University. https://doi.org/10.24416/UU01-575EWU",
            "geojson": {
                "type": "FeatureCollection",
                "features": [
                    {
                        "type": "Feature",
                        "geometry": {
                            "type": "Polygon",
                            "coordinates": [
                                [
                                    [
                                        6.76325824029,
                                        53.3471390085
                                    ],
                                    [
                                        6.76325824029,
                                        53.3504181107
                                    ],
                                    [
                                        6.76068433733,
                                        53.3504181107
                                    ],
                                    [
                                        6.76068433733,
                                        53.3471390085
                                    ],
                                    [
                                        6.76325824029,
                                        53.3471390085
                                    ]
                                ]
                            ]
                        },
                        "properties": {
                            "name": "Groningen gas field’s Slochteren sandstone reservoir rock"
                        }
                    }
                ]
            },
            "surface_area": 0,
            "rightsList": [
                {
                    "rights": "Open - freely retrievable",
                    "rightsUri": "info:eu-repo/semantics/openAccess",
                    "rightsIdentifier": "",
                    "rightsIdentifierScheme": "",
                    "rightsSchemeUri": ""
                },
                {
                    "rights": "Creative Commons Attribution 4.0 International Public License",
                    "rightsUri": "https://creativecommons.org/licenses/by/4.0/legalcode",
                    "rightsIdentifier": "",
                    "rightsIdentifierScheme": "",
                    "rightsSchemeUri": ""
                }
            ],
            "alternateIdentifier": [],
            "fundingReferences": [
                {
                    "funderName": "Netherlands Research Council (NWO)",
                    "funderIdentifier": "",
                    "funderIdentifierType": "",
                    "schemeUri": "",
                    "awardNumber": "",
                    "awardUri": "",
                    "awardTitle": ""
                }
            ],
            "dates": [
                {
                    "date": "2021-05-18T11:05:08",
                    "dateType": "Updated",
                    "dateInformation": ""
                },
                {
                    "date": "2019-06-01/2020-01-01",
                    "dateType": "Collected",
                    "dateInformation": ""
                }
            ],
            "sizes": [],
            "formats": [],
            "laboratories": [],
            "relatedIdentifiers": [
                {
                    "relatedIdentifier": "https://doi.org/10.1029/2019JB018702",
                    "relatedIdentifierType": "DOI",
                    "relationType": "IsSupplementedBy",
                    "relatedMetadataScheme": "",
                    "schemeUri": "",
                    "schemeType": "",
                    "resourceTypeGeneral": ""
                },
                {
                    "relatedIdentifier": "https://doi.org/10.1029/2019JB017366",
                    "relatedIdentifierType": "DOI",
                    "relationType": "IsSupplementedBy",
                    "relatedMetadataScheme": "",
                    "schemeUri": "",
                    "schemeType": "",
                    "resourceTypeGeneral": ""
                }
            ],
            "subjects": [
                {
                    "subject": "Rock and melt physical properties",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "OECD FOS 2007",
                    "classificationCode": "",
                    "EPOS_Uris": []
                },
                {
                    "subject": "Discrete Element Method (DEM)",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "Keyword",
                    "classificationCode": "",
                    "EPOS_Uris": []
                },
                {
                    "subject": "Particle Flow Code (PFC)",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "Keyword",
                    "classificationCode": "",
                    "EPOS_Uris": []
                },
                {
                    "subject": "Inelastic deformation",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "Keyword",
                    "classificationCode": "",
                    "EPOS_Uris": [
                        "https://epos-msl.uu.nl/voc/rockphysics/1.3/inferred_deformation_behavior-deformation_behaviour-inelastic_deformation"
                    ]
                },
                {
                    "subject": "Compaction",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "Keyword",
                    "classificationCode": "",
                    "EPOS_Uris": [
                        "https://epos-msl.uu.nl/voc/rockphysics/1.3/measured_property-strain",
                        "https://epos-msl.uu.nl/voc/analoguemodelling/1.3/measured_property-strain"
                    ]
                },
                {
                    "subject": "Sandstone",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "Keyword",
                    "classificationCode": "",
                    "EPOS_Uris": [
                        "https://epos-msl.uu.nl/voc/materials/1.3/sedimentary_rock-sandstone"
                    ]
                },
                {
                    "subject": "EPOS",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "Keyword",
                    "classificationCode": "",
                    "EPOS_Uris": []
                },
                {
                    "subject": "Multi-scale laboratories",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "Keyword",
                    "classificationCode": "",
                    "EPOS_Uris": []
                },
                {
                    "subject": "Clay film",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "Keyword",
                    "classificationCode": "",
                    "EPOS_Uris": []
                },
                {
                    "subject": "Quartz",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "Keyword",
                    "classificationCode": "",
                    "EPOS_Uris": [
                        "https://epos-msl.uu.nl/voc/materials/1.3/minerals-silicate_minerals-tectosilicates-quartz"
                    ]
                },
                {
                    "subject": "Gas reservoir",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "Keyword",
                    "classificationCode": "",
                    "EPOS_Uris": [
                        "https://epos-msl.uu.nl/voc/subsurface/1.3/subsurface_energy_production-hydrocarbon_energy_production-gas_field"
                    ]
                },
                {
                    "subject": "Subsidence",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "Keyword",
                    "classificationCode": "",
                    "EPOS_Uris": [
                        "https://epos-msl.uu.nl/voc/subsurface/1.3/surface_subsidence"
                    ]
                },
                {
                    "subject": "Induced seismicity",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "Keyword",
                    "classificationCode": "",
                    "EPOS_Uris": [
                        "https://epos-msl.uu.nl/voc/subsurface/1.3/induced_seismicity"
                    ]
                },
                {
                    "subject": "Contact model",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "Keyword",
                    "classificationCode": "",
                    "EPOS_Uris": []
                },
                {
                    "subject": "Nonlinear elasticity",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "Keyword",
                    "classificationCode": "",
                    "EPOS_Uris": []
                },
                {
                    "subject": "permanent time-independent deformation",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "Keyword",
                    "classificationCode": "",
                    "EPOS_Uris": []
                },
                {
                    "subject": "Intragranular failure",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "Keyword",
                    "classificationCode": "",
                    "EPOS_Uris": [
                        "https://epos-msl.uu.nl/voc/rockphysics/1.3/inferred_deformation_behavior-microphysical_deformation_mechanism-intragranular_cracking",
                        "https://epos-msl.uu.nl/voc/microscopy/1.3/analyzed_feature-deformation_microstructure-brittle_microstructure-intragranular_crack"
                    ]
                },
                {
                    "subject": "Numerical modeling",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "Keyword",
                    "classificationCode": "",
                    "EPOS_Uris": []
                },
                {
                    "subject": "Grain failure",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "Keyword",
                    "classificationCode": "",
                    "EPOS_Uris": [
                        "https://epos-msl.uu.nl/voc/rockphysics/1.3/inferred_deformation_behavior-microphysical_deformation_mechanism-intragranular_cracking",
                        "https://epos-msl.uu.nl/voc/microscopy/1.3/analyzed_feature-deformation_microstructure-brittle_microstructure-intragranular_crack"
                    ]
                },
                {
                    "subject": "sandstone",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "Material",
                    "classificationCode": "",
                    "EPOS_Uris": [
                        "https://epos-msl.uu.nl/voc/materials/1.3/sedimentary_rock-sandstone"
                    ]
                },
                {
                    "subject": "Triaxial",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "Apparatus",
                    "classificationCode": "",
                    "EPOS_Uris": []
                },
                {
                    "subject": "Strain gauge",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "Monitoring",
                    "classificationCode": "",
                    "EPOS_Uris": []
                },
                {
                    "subject": "Triaxial Compressive Strength",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "Measured_Property",
                    "classificationCode": "",
                    "EPOS_Uris": [
                        "https://epos-msl.uu.nl/voc/rockphysics/1.3/measured_property-mechanical_strength-triaxial_compressive_strength_s1-s2s3"
                    ]
                },
                {
                    "subject": "Young’s Modulus",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "Measured_Property",
                    "classificationCode": "",
                    "EPOS_Uris": []
                },
                {
                    "subject": "Bulk Modulus",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "Measured_Property",
                    "classificationCode": "",
                    "EPOS_Uris": [
                        "https://epos-msl.uu.nl/voc/rockphysics/1.3/measured_property-elasticity-bulk_modulus",
                        "https://epos-msl.uu.nl/voc/rockphysics/1.3/inferred_deformation_behavior-deformation_behaviour-poroelastic_deformation",
                        "https://epos-msl.uu.nl/voc/analoguemodelling/1.3/measured_property-elasticity-bulk_modulus"
                    ]
                }
            ],
            "subdomains": [
                "rock and melt physics",
                "analogue modelling of geologic processes",
                "microscopy and tomography"
            ]
        },
    ]
}
```

</details>

### /paleo {#dp_paleo}
This endpoint gives access to all data-publications available that are marked as belonging to the paleomagnetism (sub)domain. 

### Search all paleomagnetism data-publications [GET]
+ Parameters

    + limit (number, optional) - The number of results to return.
        + Default: `10`
    + offset (number, optional) - The number to start results from. 
        + Default: `0`
    + query (text, optional) - Words to search for. 
        + Default: ``
    + authorName (text, optional) - Author names to search for. 
        + Default: ``
    + labName (text, optional) - Lab names to search for. 
        + Default: ``
    + title (text, optional) - Title to search for. 
        + Default: ``
    + tags (text, optional) - Tags to search for. 
        + Default: ``
    + hasDownloads (boolean, optional) - Filter results to only include results with download links.
        + Default: `false`
    + boundingBox 	(minx,miny,maxx,maxy) decimals, optional - Bounding box geographically filtering the results. If provided the bounding box must be valid. bounds: (-180, -90, 180, 90). 
        + Default: ``

        
+ Response 200 (application/json)

<details>
  <summary>view response</summary>
  
```json
{
    "success": "true",
    "messages": [],
    "meta": {
        "resultCount": 10,
        "totalCount": 321,
        "limit": 10,
        "offset": 0
    },
    "links": {
        "current_url": "https://epos-msl.uu.nl/api/v2/datapublications/paleo?offset=0&limit=10"
    },
    "data": [
        {
            "title": "Rock magnetic properties, IODP Site U1518, depth interval 250-400 mbsf",
            "doi": "10.24416/uu01-xzskhp",
            "source": "https://public.yoda.uu.nl/geo/UU01/XZSKHP.html",
            "portalLink": "http://localhost/data-publication/516d47a85bc036c0b6b1c569138cccca",
            "name": "516d47a85bc036c0b6b1c569138cccca",
            "creators": [
                {
                    "name": "Annika Greve",
                    "nameType": "Personal",
                    "givenName": "Annika",
                    "familyName": "Greve",
                    "nameIdentifiers": [
                        {
                            "nameIdentifier": "https://orcid.org/0000-0001-8670-8242",
                            "nameIdentifierScheme": "ORCID",
                            "nameIdentifierUri": ""
                        }
                    ],
                    "affiliation": [
                        {
                            "name": "Utrecht University",
                            "affiliationIdentifier": "",
                            "affiliationIdentifierScheme": "",
                            "schemeUri": ""
                        }
                    ]
                }
            ],
            "descriptions": [
                {
                    "description": "Rock magnetic properties for hemipelagic sediments sampled at Site U1518 of IODP Expedition 375 (depth interval: 250-400 mbsf). \n\nData and file information:\n ‘hysteresis_parameters_curated.txt’ summarizes composition specific parameters from hysteresis and backfield curves.\n‘suceptibility_remanence_curated.txt’ summarizes curational information for each sample, susceptibility and remanence data measured on 7cc sample cubes.\n\nData Curation\nDepth: CSF-A and CSF-B refer to core depth scales of International Ocean Discovery Program (see pdf file “IODP Depth Scales Terminology” on https://www.iodp.org/policies-and-guidelines)\nCSF: core depth below sea floor, in this study, top depth CSF-B is used as sample depth in meters below sea floor (mbsf).\nSection Type: R for Rotary Core Barrel\n\nSusceptibility and Remanence data\nKm is the mean susceptibility extracted from anisotropy of magnetic susceptibility datafiles. These data are presented in Greve et al., 2020, https://doi.org/10.1016/j.epsl.2020.116322\nNRM: Natural Remanent Magnetization in emu\nARM: Anhysteretic Remanent Magnetization in emu. \nSIRM: Saturation Isothermal Remanent Magnetization imparted at 1.2 T in emu. \n\nHysteresis parameters\nAll hysteresis parameters were determined following mass normalization and automated slope correction. \nMs: mass-normalized saturation magnetization in Am²/kg \nMr: mass-normalized saturation remanent magnetization in Am²/kg \nBc: coercivity in mT\nBcr: remanent coercivity field in mT\n\nContact person: A. Greve - Researcher - a.greve@uu.nl",
                    "descriptionType": "Abstract"
                }
            ],
            "contributors": [
                {
                    "name": "Greve, Annika",
                    "contributorType": "ProjectLeader",
                    "nameType": "Personal",
                    "givenName": "",
                    "familyName": "",
                    "nameIdentifiers": [
                        {
                            "nameIdentifier": "https://orcid.org/0000-0001-8670-8242",
                            "nameIdentifierScheme": "ORCID",
                            "nameIdentifierUri": ""
                        }
                    ],
                    "affiliation": [
                        {
                            "name": "Utrecht University",
                            "affiliationIdentifier": "",
                            "affiliationIdentifierScheme": "",
                            "schemeUri": ""
                        }
                    ]
                },
                {
                    "name": "Kars, Myriam",
                    "contributorType": "DataCollector",
                    "nameType": "Personal",
                    "givenName": "",
                    "familyName": "",
                    "nameIdentifiers": [
                        {
                            "nameIdentifier": "https://orcid.org/0000-0002-4984-1412",
                            "nameIdentifierScheme": "ORCID",
                            "nameIdentifierUri": ""
                        }
                    ],
                    "affiliation": [
                        {
                            "name": "Kochi University",
                            "affiliationIdentifier": "",
                            "affiliationIdentifierScheme": "",
                            "schemeUri": ""
                        }
                    ]
                },
                {
                    "name": "Dekkers, Mark. J.",
                    "contributorType": "Supervisor",
                    "nameType": "Personal",
                    "givenName": "",
                    "familyName": "",
                    "nameIdentifiers": [
                        {
                            "nameIdentifier": "https://orcid.org/0000-0002-4156-3841",
                            "nameIdentifierScheme": "ORCID",
                            "nameIdentifierUri": ""
                        }
                    ],
                    "affiliation": [
                        {
                            "name": "Utrecht University",
                            "affiliationIdentifier": "",
                            "affiliationIdentifierScheme": "",
                            "schemeUri": ""
                        }
                    ]
                },
                {
                    "name": "Paleomagnetic Laboratory Fort Hoofddijk  (Utrecht University, The Netherlands)",
                    "contributorType": "HostingInstitution",
                    "nameType": "Personal",
                    "givenName": "",
                    "familyName": "",
                    "nameIdentifiers": [],
                    "affiliation": [
                        {
                            "name": "Utrecht University",
                            "affiliationIdentifier": "",
                            "affiliationIdentifierScheme": "",
                            "schemeUri": ""
                        }
                    ]
                }
            ],
            "materials": [],
            "researchAspects": [
                "remanent magnetisation",
                "backfield remanence coercivity (BRC)",
                "magnetic hysteresis curves",
                "hysteresis parameters",
                "magnetic susceptibility",
                "bulk magnetic susceptibility",
                "anhysteretic remanent magnetisation (ARM)",
                "isothermal remanent magnetisation (IRM)",
                "natural remanent magnetisation (NRM)",
                "anisotropy of magnetic susceptibility and remanence",
                "natural remanent magnetisation (NRM) orientation"
            ],
            "files": [
                {
                    "fileName": "raw-data",
                    "downloadLink": "https://geo.public.data.uu.nl:443/vault-papaku-rockmagnetism/Greve_et_al_2021[1611243312]/original/raw-data/",
                    "extension": ""
                }
            ],
            "resource_type": "Other Document",
            "resource_type_general": "Text",
            "publication_year": "2021",
            "language": "en",
            "publisher": "Utrecht University",
            "citation": "Greve, A. (2021). Rock magnetic properties, IODP Site U1518, depth interval 250-400 mbsf. <i>Utrecht University</i>. https://doi.org/10.24416/UU01-XZSKHP",
            "geojson": null,
            "surface_area": 0,
            "rightsList": [
                {
                    "rights": "Open - freely retrievable",
                    "rightsUri": "info:eu-repo/semantics/openAccess",
                    "rightsIdentifier": "",
                    "rightsIdentifierScheme": "",
                    "rightsSchemeUri": ""
                },
                {
                    "rights": "Creative Commons Attribution 4.0 International Public License",
                    "rightsUri": "https://creativecommons.org/licenses/by/4.0/legalcode",
                    "rightsIdentifier": "",
                    "rightsIdentifierScheme": "",
                    "rightsSchemeUri": ""
                }
            ],
            "alternateIdentifier": [],
            "fundingReferences": [
                {
                    "funderName": "NWO DeepNL",
                    "funderIdentifier": "",
                    "funderIdentifierType": "",
                    "schemeUri": "",
                    "awardNumber": "",
                    "awardUri": "",
                    "awardTitle": ""
                },
                {
                    "funderName": "Korea Institute of Energy Technology Evaluation and Planning (KETEP) and the Ministry of Trade, Industry & Energy (MOTIE) of the Republic of Korea",
                    "funderIdentifier": "",
                    "funderIdentifierType": "",
                    "schemeUri": "",
                    "awardNumber": "",
                    "awardUri": "",
                    "awardTitle": ""
                }
            ],
            "dates": [
                {
                    "date": "2024-07-12T11:08:15",
                    "dateType": "Updated",
                    "dateInformation": ""
                },
                {
                    "date": "2017-03-17/2017-03-20",
                    "dateType": "Collected",
                    "dateInformation": ""
                }
            ],
            "sizes": [],
            "formats": [],
            "laboratories": [],
            "relatedIdentifiers": [
                {
                    "relatedIdentifier": "https://doi.org/10.1016/j.epsl.2020.116322",
                    "relatedIdentifierType": "DOI",
                    "relationType": "References",
                    "relatedMetadataScheme": "",
                    "schemeUri": "",
                    "schemeType": "",
                    "resourceTypeGeneral": ""
                },
                {
                    "relatedIdentifier": "https://www.iodp.org/policies-and-guidelines",
                    "relatedIdentifierType": "URL",
                    "relationType": "References",
                    "relatedMetadataScheme": "",
                    "schemeUri": "",
                    "schemeType": "",
                    "resourceTypeGeneral": ""
                }
            ],
            "subjects": [
                {
                    "subject": "Natural Sciences - Earth and related environmental sciences (1.5)",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "OECD FOS 2007",
                    "classificationCode": "",
                    "EPOS_Uris": []
                },
                {
                    "subject": "backfield remanent coercivity",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "Keyword",
                    "classificationCode": "",
                    "EPOS_Uris": [
                        "https://epos-msl.uu.nl/voc/paleomagnetism/1.3/measured_property-remanent_magnetisation-backfield_remanence_coercivity_brc"
                    ]
                },
                {
                    "subject": "rock magnetic parameters",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "Keyword",
                    "classificationCode": "",
                    "EPOS_Uris": []
                },
                {
                    "subject": "hemipelagic sediments",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "Keyword",
                    "classificationCode": "",
                    "EPOS_Uris": []
                },
                {
                    "subject": "EPOS",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "Keyword",
                    "classificationCode": "",
                    "EPOS_Uris": []
                },
                {
                    "subject": "multi-scale laboratories",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "Keyword",
                    "classificationCode": "",
                    "EPOS_Uris": []
                },
                {
                    "subject": "Paleomagnetic and magnetic data",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "Keyword",
                    "classificationCode": "",
                    "EPOS_Uris": []
                },
                {
                    "subject": "hysteresis parameters",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "Keyword",
                    "classificationCode": "",
                    "EPOS_Uris": [
                        "https://epos-msl.uu.nl/voc/paleomagnetism/1.3/measured_property-magnetic_hysteresis_curves-hysteresis_parameters"
                    ]
                },
                {
                    "subject": "bulk magnetic susceptibility",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "Keyword",
                    "classificationCode": "",
                    "EPOS_Uris": [
                        "https://epos-msl.uu.nl/voc/paleomagnetism/1.3/measured_property-magnetic_susceptibility-bulk_magnetic_susceptibility"
                    ]
                }
            ],
            "subdomains": [
                "paleomagnetism"
            ]
        },
    ]
}
```

</details>

### /microscopy {#dp_microscopy}
This endpoint gives access to all data-publications available that are marked as belonging to the microscopy and tomography (sub)domain. 

### Search all microscopy and tomography data-publications [GET]
+ Parameters

    + limit (number, optional) - The number of results to return.
        + Default: `10`
    + offset (number, optional) - The number to start results from. 
        + Default: `0`
    + query (text, optional) - Words to search for. 
        + Default: ``
    + authorName (text, optional) - Author names to search for. 
        + Default: ``
    + labName (text, optional) - Lab names to search for. 
        + Default: ``
    + title (text, optional) - Title to search for. 
        + Default: ``
    + tags (text, optional) - Tags to search for. 
        + Default: ``
    + hasDownloads (boolean, optional) - Filter results to only include results with download links.
        + Default: `false`
    + boundingBox 	(minx,miny,maxx,maxy) decimals, optional - Bounding box geographically filtering the results. If provided the bounding box must be valid. bounds: (-180, -90, 180, 90). 
        + Default: ``

        
+ Response 200 (application/json)

<details>
  <summary>view response</summary>
  
```json
{
    "success": "true",
    "messages": [],
    "meta": {
        "resultCount": 10,
        "totalCount": 321,
        "limit": 10,
        "offset": 0
    },
    "links": {
        "current_url": "https://epos-msl.uu.nl/api/v2/datapublications/microscopy?offset=0&limit=10"
    },
    "data": [
        {
            "title": "Contact model and numerical modelling results: “Compaction of the Groningen Gas Reservoir Sandstone: Discrete Element Modelling Using Microphysically Based Grain-Scale Interaction Laws”",
            "doi": "10.24416/uu01-575ewu",
            "source": "https://public.yoda.uu.nl/geo/UU01/575EWU.html",
            "portalLink": "http://localhost/data-publication/ad60ac76a738f696d9de7acc1506d491",
            "name": "ad60ac76a738f696d9de7acc1506d491",
            "creators": [
                {
                    "name": "Mohammad Hadi Mehranpour",
                    "nameType": "Personal",
                    "givenName": "Mohammad Hadi",
                    "familyName": "Mehranpour",
                    "nameIdentifiers": [
                        {
                            "nameIdentifier": "0000-0001-7336-0435",
                            "nameIdentifierScheme": "ORCID",
                            "nameIdentifierUri": ""
                        }
                    ],
                    "affiliation": [
                        {
                            "name": "Utrecht University",
                            "affiliationIdentifier": "",
                            "affiliationIdentifierScheme": "",
                            "schemeUri": ""
                        }
                    ]
                }
            ],
            "descriptions": [
                {
                    "description": "Reservoir compaction, surface subsidence and induced seismicity are often associated with prolonged hydrocarbon production. Recent experiments conducted on the Groningen gas field’s Slochteren sandstone reservoir rock, at in-situ conditions, have shown that compaction involves both poro-elastic strain and time-independent, permanent strain caused by consolidation and shear of clay films coating the sandstone grains, with grain failure occurring at higher stresses. To model compaction of the reservoir in space and time, numerical approaches, such as the Discrete Element Method (DEM) , populated with realistic grain-scale mechanisms are needed. We developed a new particle-interaction law (contact model) for the classic DEM to explicitly account for the experimentally observed mechanisms of non-linear elasticity, intergranular clay film deformation, and grain breakage. It was calibrated against both hydrostatic and conventional triaxial compression experiments and validated against an independent set of pore pressure depletion experiments conducted under uniaxial strain conditions, using a range of sample porosities, grain size distributions and clay contents. The model obtained was used to predict compaction of the Groningen reservoir. These results were compared with field measurements of in-situ compaction and matched favorably, within field measurement uncertainties. The new model allows systematic investigation of the effects of mineralogy, microstructure, boundary conditions and loading path on compaction behavior of the reservoir. It also offers a means of generating a data bank suitable for developing generalized constitutive models and for predicting reservoir response to different scenarios of gas extraction, or of fluid injection for stabilization or storage purposes.\n\nThe data provided in this dataset include the contact model (source codes and the contact model library) developed for the Particle Flow Code (PFC) software, Fish code package for running PFC models, numerical modeling results (tabulated) obtained in the calibration procedure and uniaxial compaction prediction.\n",
                    "descriptionType": "Abstract"
                }
            ],
            "contributors": [
                {
                    "name": "Mehranpour, Mohammad Hadi",
                    "contributorType": "DataCollector",
                    "nameType": "Personal",
                    "givenName": "",
                    "familyName": "",
                    "nameIdentifiers": [
                        {
                            "nameIdentifier": "0000-0001-7336-0435",
                            "nameIdentifierScheme": "ORCID",
                            "nameIdentifierUri": ""
                        }
                    ],
                    "affiliation": [
                        {
                            "name": "Utrecht University",
                            "affiliationIdentifier": "",
                            "affiliationIdentifierScheme": "",
                            "schemeUri": ""
                        }
                    ]
                },
                {
                    "name": "Hangx, Suzanne J.T.",
                    "contributorType": "ProjectManager",
                    "nameType": "Personal",
                    "givenName": "",
                    "familyName": "",
                    "nameIdentifiers": [
                        {
                            "nameIdentifier": "0000-0003-2253-3273",
                            "nameIdentifierScheme": "ORCID",
                            "nameIdentifierUri": ""
                        }
                    ],
                    "affiliation": [
                        {
                            "name": "Utrecht University",
                            "affiliationIdentifier": "",
                            "affiliationIdentifierScheme": "",
                            "schemeUri": ""
                        }
                    ]
                },
                {
                    "name": "Spiers, Christopher James",
                    "contributorType": "ProjectLeader",
                    "nameType": "Personal",
                    "givenName": "",
                    "familyName": "",
                    "nameIdentifiers": [
                        {
                            "nameIdentifier": "0000-0002-3436-8941",
                            "nameIdentifierScheme": "ORCID",
                            "nameIdentifierUri": ""
                        }
                    ],
                    "affiliation": [
                        {
                            "name": "Utrecht University",
                            "affiliationIdentifier": "",
                            "affiliationIdentifierScheme": "",
                            "schemeUri": ""
                        }
                    ]
                }
            ],
            "materials": [
                "sedimentary rock",
                "sandstone",
                "minerals",
                "silicate minerals",
                "tectosilicates",
                "quartz",
                "wacke",
                "Slochteren sandstone",
                "phyllosilicates",
                "clay",
                "unconsolidated sediment",
                "clastic sediment",
                "clay"
            ],
            "researchAspects": [],
            "files": [
                {
                    "fileName": "calibration",
                    "downloadLink": "https://geo.public.data.uu.nl:443/vault-sandstone-compaction/Mehranpour_et_al_2021_DEModeling[1621322230]/original/calibration/",
                    "extension": ""
                },
                {
                    "fileName": "contact model",
                    "downloadLink": "https://geo.public.data.uu.nl:443/vault-sandstone-compaction/Mehranpour_et_al_2021_DEModeling[1621322230]/original/contact model/",
                    "extension": ""
                }
            ],
            "resource_type": "Research Data",
            "resource_type_general": "Dataset",
            "publication_year": "2021",
            "language": "en",
            "publisher": "Utrecht University",
            "citation": "Mehranpour, M. H. (2021). <i>Contact model and numerical modelling results: “Compaction of the Groningen Gas Reservoir Sandstone: Discrete Element Modelling Using Microphysically Based Grain-Scale Interaction Laws”</i> (Version 1.0) [Data set]. Utrecht University. https://doi.org/10.24416/UU01-575EWU",
            "geojson": {
                "type": "FeatureCollection",
                "features": [
                    {
                        "type": "Feature",
                        "geometry": {
                            "type": "Polygon",
                            "coordinates": [
                                [
                                    [
                                        6.76325824029,
                                        53.3471390085
                                    ],
                                    [
                                        6.76325824029,
                                        53.3504181107
                                    ],
                                    [
                                        6.76068433733,
                                        53.3504181107
                                    ],
                                    [
                                        6.76068433733,
                                        53.3471390085
                                    ],
                                    [
                                        6.76325824029,
                                        53.3471390085
                                    ]
                                ]
                            ]
                        },
                        "properties": {
                            "name": "Groningen gas field’s Slochteren sandstone reservoir rock"
                        }
                    }
                ]
            },
            "surface_area": 0,
            "rightsList": [
                {
                    "rights": "Open - freely retrievable",
                    "rightsUri": "info:eu-repo/semantics/openAccess",
                    "rightsIdentifier": "",
                    "rightsIdentifierScheme": "",
                    "rightsSchemeUri": ""
                },
                {
                    "rights": "Creative Commons Attribution 4.0 International Public License",
                    "rightsUri": "https://creativecommons.org/licenses/by/4.0/legalcode",
                    "rightsIdentifier": "",
                    "rightsIdentifierScheme": "",
                    "rightsSchemeUri": ""
                }
            ],
            "alternateIdentifier": [],
            "fundingReferences": [
                {
                    "funderName": "Netherlands Research Council (NWO)",
                    "funderIdentifier": "",
                    "funderIdentifierType": "",
                    "schemeUri": "",
                    "awardNumber": "",
                    "awardUri": "",
                    "awardTitle": ""
                }
            ],
            "dates": [
                {
                    "date": "2021-05-18T11:05:08",
                    "dateType": "Updated",
                    "dateInformation": ""
                },
                {
                    "date": "2019-06-01/2020-01-01",
                    "dateType": "Collected",
                    "dateInformation": ""
                }
            ],
            "sizes": [],
            "formats": [],
            "laboratories": [],
            "relatedIdentifiers": [
                {
                    "relatedIdentifier": "https://doi.org/10.1029/2019JB018702",
                    "relatedIdentifierType": "DOI",
                    "relationType": "IsSupplementedBy",
                    "relatedMetadataScheme": "",
                    "schemeUri": "",
                    "schemeType": "",
                    "resourceTypeGeneral": ""
                },
                {
                    "relatedIdentifier": "https://doi.org/10.1029/2019JB017366",
                    "relatedIdentifierType": "DOI",
                    "relationType": "IsSupplementedBy",
                    "relatedMetadataScheme": "",
                    "schemeUri": "",
                    "schemeType": "",
                    "resourceTypeGeneral": ""
                }
            ],
            "subjects": [
                {
                    "subject": "Rock and melt physical properties",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "OECD FOS 2007",
                    "classificationCode": "",
                    "EPOS_Uris": []
                },
                {
                    "subject": "Discrete Element Method (DEM)",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "Keyword",
                    "classificationCode": "",
                    "EPOS_Uris": []
                }
            ],
            "subdomains": [
                "rock and melt physics",
                "analogue modelling of geologic processes",
                "microscopy and tomography"
            ]
        },
    ]
}
```

</details>

### /geochemistry {#dp_geochemistry}
This endpoint gives access to all data-publications available that are marked as belonging to the geochemistry (sub)domain. 

### Search all geochemistry data-publications [GET]
+ Parameters

    + limit (number, optional) - The number of results to return.
        + Default: `10`
    + offset (number, optional) - The number to start results from. 
        + Default: `0`
    + query (text, optional) - Words to search for. 
        + Default: ``
    + authorName (text, optional) - Author names to search for. 
        + Default: ``
    + labName (text, optional) - Lab names to search for. 
        + Default: ``
    + title (text, optional) - Title to search for. 
        + Default: ``
    + tags (text, optional) - Tags to search for. 
        + Default: ``
    + hasDownloads (boolean, optional) - Filter results to only include results with download links.
        + Default: `false`
    + boundingBox 	(minx,miny,maxx,maxy) decimals, optional - Bounding box geographically filtering the results. If provided the bounding box must be valid. bounds: (-180, -90, 180, 90). 
        + Default: ``

        
+ Response 200 (application/json)

<details>
  <summary>view response</summary>
  

```json
{
    "success": "true",
    "messages": [],
    "meta": {
        "resultCount": 10,
        "totalCount": 321,
        "limit": 10,
        "offset": 0
    },
    "links": {
        "current_url": "https://epos-msl.uu.nl/api/v2/datapublications/geochemistry?offset=0&limit=10"
    },
    "data": [
        {
            "title": "Frictional slip weakening and shear-enhanced crystallinity in simulated coal fault gouges at subseismic slip rates",
            "doi": "10.24416/uu01-48i5da",
            "source": "https://public.yoda.uu.nl/geo/UU01/48I5DA.html",
            "portalLink": "http://localhost/data-publication/f6b4a6bf74c39951c13f5299931b494e",
            "name": "f6b4a6bf74c39951c13f5299931b494e",
            "creators": [
                {
                    "name": "Jinfeng Liu",
                    "nameType": "Personal",
                    "givenName": "Jinfeng",
                    "familyName": "Liu",
                    "nameIdentifiers": [
                        {
                            "nameIdentifier": "https://orcid.org/0000-0002-6444-9427",
                            "nameIdentifierScheme": "ORCID",
                            "nameIdentifierUri": ""
                        },
                        {
                            "nameIdentifier": "56974027400",
                            "nameIdentifierScheme": "Author identifier (Scopus)",
                            "nameIdentifierUri": ""
                        }
                    ],
                    "affiliation": [
                        {
                            "name": "School of Earth Sciences and Engineering, Sun Yat-Sen University",
                            "affiliationIdentifier": "",
                            "affiliationIdentifierScheme": "",
                            "schemeUri": ""
                        },
                        {
                            "name": "Guangdong Provincial Key Lab of Geodynamics and Geohazards, Sun Yat-Sen University, Zhuhai, China",
                            "affiliationIdentifier": "",
                            "affiliationIdentifierScheme": "",
                            "schemeUri": ""
                        },
                        {
                            "name": "Southern Marine Science and Engineering Guangdong Laboratory, Zhuhai, China",
                            "affiliationIdentifier": "",
                            "affiliationIdentifierScheme": "",
                            "schemeUri": ""
                        }
                    ]
                },
                {
                    "name": "Luuk Bernd Hunfeld",
                    "nameType": "Personal",
                    "givenName": "Luuk Bernd",
                    "familyName": "Hunfeld",
                    "nameIdentifiers": [
                        {
                            "nameIdentifier": "https://orcid.org/0000-0001-9250-414X",
                            "nameIdentifierScheme": "ORCID",
                            "nameIdentifierUri": ""
                        },
                        {
                            "nameIdentifier": "57197828301",
                            "nameIdentifierScheme": "Author identifier (Scopus)",
                            "nameIdentifierUri": ""
                        }
                    ],
                    "affiliation": [
                        {
                            "name": "Utrecht University",
                            "affiliationIdentifier": "",
                            "affiliationIdentifierScheme": "",
                            "schemeUri": ""
                        }
                    ]
                }
            ],
            "descriptions": [
                {
                    "description": "We report seven velocity stepping (VS) and one slide-hold-slide (SHS) friction experiments performed on simulated fault gouges prepared from bituminous coal, collected from the upper Silesian Basin of Poland. These experiments were performed at 25-45 MPa effective normal stress and 100 ℃, employing sliding velocities of 0.1-100 μm/s, using a conventional triaxial apparatus plus direct shear assembly. All samples showed marked slip weakening behaviour at shear displacements beyond ~1-2 mm, from a peak friction coefficient approaching ~0.5 to (near) steady state values of ~0.3, regardless of effective normal stress or whether vacuum dry flooded with distilled (DI) water at 15 MPa pore fluid pressure. Analysis of both unsheared and sheared samples by means of microstructural observation, micro-area X-ray diffraction (XRD) and Raman spectroscopy suggests that the marked slip weakening behaviour can be attributed to the development of R-, B- and Y- shear bands, with internal shear-enhanced coal crystallinity development. The SHS experiment performed showed a transient peak healing (restrengthening) effect that increased with the logarithm of hold time at a linearized rate of ~0.006. We also determined the rate-dependence of steady state friction for all VS samples using a full rate and state friction approach. This showed a transition from velocity strengthening to velocity weakening at slip velocities >1 μm/s in the coal sample under vacuum dry conditions, but at >10 μm/s in coal samples exposed to DI water at 15 MPa pore pressure. This may be controlled by competition between dilatant granular flow and compaction enhanced by presence of water. Together with our previous work on frictional properties of coal-shale mixtures, our results imply that the presence of a weak, coal-dominated patch on faults that cut or smear-out coal seams may promote unstable, seismogenic slip behaviour, though the importance of this in enhancing either induced or natural seismicity depends on local conditions. The data is provided in a folder with 10 subfolders for 10 experiments/samples, including friction, XRD and Raman data. Detailed information about the files in these subfolders as well as information on how the data is processed is given in the explanatory file Fan-et-al-2020-Data-Description.pdf. Contact person is Dr. Jinfeng Liu - Sun Yat-Sen University- liujinf5@mail.sysu.edu.cn",
                    "descriptionType": "Abstract"
                }
            ],
            "contributors": [
                {
                    "name": "Fan, Caiyuan",
                    "contributorType": "DataCollector",
                    "nameType": "Personal",
                    "givenName": "",
                    "familyName": "",
                    "nameIdentifiers": [
                        {
                            "nameIdentifier": "https://orcid.org/0000-0002-0413-8467",
                            "nameIdentifierScheme": "ORCID",
                            "nameIdentifierUri": ""
                        }
                    ],
                    "affiliation": [
                        {
                            "name": "School of Earth Sciences and Engineering, Sun Yat-Sen University",
                            "affiliationIdentifier": "",
                            "affiliationIdentifierScheme": "",
                            "schemeUri": ""
                        }
                    ]
                },
                {
                    "name": "Fan, Caiyuan",
                    "contributorType": "Researcher",
                    "nameType": "Personal",
                    "givenName": "",
                    "familyName": "",
                    "nameIdentifiers": [
                        {
                            "nameIdentifier": "https://orcid.org/0000-0002-0413-8467",
                            "nameIdentifierScheme": "ORCID",
                            "nameIdentifierUri": ""
                        }
                    ],
                    "affiliation": [
                        {
                            "name": "School of Earth Sciences and Engineering, Sun Yat-Sen University",
                            "affiliationIdentifier": "",
                            "affiliationIdentifierScheme": "",
                            "schemeUri": ""
                        }
                    ]
                }
            ],
            "materials": [
                "sedimentary rock",
                "coal",
                "fault rock",
                "fault gouge",
                "mudstone",
                "shale",
                "bituminous coal",
                "simulated fault gouge"
            ],
            "researchAspects": [
                "equipment",
                "x-ray diffractometer",
                "tectonic deformation structure",
                "tectonic fault"
            ],
            "files": [
                {
                    "fileName": "S0",
                    "downloadLink": "https://geo.public.data.uu.nl:443/vault-coal-friction-data/Liu_et_al_2020_Solid_Earth[1585573908]/original/S0/",
                    "extension": ""
                },
                {
                    "fileName": "S1",
                    "downloadLink": "https://geo.public.data.uu.nl:443/vault-coal-friction-data/Liu_et_al_2020_Solid_Earth[1585573908]/original/S1/",
                    "extension": ""
                },
                {
                    "fileName": "S2",
                    "downloadLink": "https://geo.public.data.uu.nl:443/vault-coal-friction-data/Liu_et_al_2020_Solid_Earth[1585573908]/original/S2/",
                    "extension": ""
                }
            ],
            "resource_type": "Other Document",
            "resource_type_general": "Text",
            "publication_year": "2020",
            "language": "en",
            "publisher": "Utrecht University",
            "citation": "Liu, J., &amp; Hunfeld, L. B. (2020). Frictional slip weakening and shear-enhanced crystallinity in simulated coal fault gouges at subseismic slip rates. <i>Utrecht University</i>. https://doi.org/10.24416/UU01-48I5DA",
            "geojson": null,
            "surface_area": 0,
            "rightsList": [
                {
                    "rights": "Open - freely retrievable",
                    "rightsUri": "info:eu-repo/semantics/openAccess",
                    "rightsIdentifier": "",
                    "rightsIdentifierScheme": "",
                    "rightsSchemeUri": ""
                },
                {
                    "rights": "Creative Commons Attribution 4.0 International Public License",
                    "rightsUri": "https://creativecommons.org/licenses/by/4.0/legalcode",
                    "rightsIdentifier": "",
                    "rightsIdentifierScheme": "",
                    "rightsSchemeUri": ""
                }
            ],
            "alternateIdentifier": [],
            "fundingReferences": [
                {
                    "funderName": "National Natural Science Foundation of China",
                    "funderIdentifier": "",
                    "funderIdentifierType": "",
                    "schemeUri": "",
                    "awardNumber": "",
                    "awardUri": "",
                    "awardTitle": ""
                }
            ],
            "dates": [
                {
                    "date": "2024-07-12T11:07:34",
                    "dateType": "Updated",
                    "dateInformation": ""
                },
                {
                    "date": "2016-10-01/2020-01-31",
                    "dateType": "Collected",
                    "dateInformation": ""
                }
            ],
            "sizes": [],
            "formats": [],
            "laboratories": [],
            "relatedIdentifiers": [
                {
                    "relatedIdentifier": "https://doi.org/10.1002/2017JB014876",
                    "relatedIdentifierType": "DOI",
                    "relationType": "References",
                    "relatedMetadataScheme": "",
                    "schemeUri": "",
                    "schemeType": "",
                    "resourceTypeGeneral": ""
                }
            ],
            "subjects": [
                {
                    "subject": "Natural Sciences - Earth and related environmental sciences (1.5)",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "OECD FOS 2007",
                    "classificationCode": "",
                    "EPOS_Uris": []
                },
                {
                    "subject": "rate-dependent friction",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "Keyword",
                    "classificationCode": "",
                    "EPOS_Uris": []
                },
                {
                    "subject": "frictional healing",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "Keyword",
                    "classificationCode": "",
                    "EPOS_Uris": [
                        "https://epos-msl.uu.nl/voc/rockphysics/1.3/measured_property-friction_-_controlled_slip_rate-friction_coefficient-frictional_strength_recovery",
                        "https://epos-msl.uu.nl/voc/analoguemodelling/1.3/measured_property-friction_-_controlled_slip_rate-friction_coefficient-frictional_strength_recovery"
                    ]
                },
                {
                    "subject": "slip-weakening",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "Keyword",
                    "classificationCode": "",
                    "EPOS_Uris": []
                }
            ],
            "subdomains": [
                "rock and melt physics",
                "analogue modelling of geologic processes",
                "microscopy and tomography",
                "geochemistry"
            ]
        }
    ]
}
```
</details>


### /geoenergy {#dp_geoenergy}
This endpoint gives access to all data-publications available that are marked as belonging to the Geo-energy test beds (sub)domain. 

### Search all Geo-energy test beds data-publications [GET]
+ Parameters

    + limit (number, optional) - The number of results to return.
        + Default: `10`
    + offset (number, optional) - The number to start results from. 
        + Default: `0`
    + query (text, optional) - Words to search for. 
        + Default: ``
    + authorName (text, optional) - Author names to search for. 
        + Default: ``
    + labName (text, optional) - Lab names to search for. 
        + Default: ``
    + title (text, optional) - Title to search for. 
        + Default: ``
    + tags (text, optional) - Tags to search for. 
        + Default: ``
    + hasDownloads (boolean, optional) - Filter results to only include results with download links.
        + Default: `false`
    + boundingBox 	(minx,miny,maxx,maxy) decimals, optional - Bounding box geographically filtering the results. If provided the bounding box must be valid. bounds: (-180, -90, 180, 90). 
        + Default: ``

        
+ Response 200 (application/json)

<details>
  <summary>view response</summary>
  
```json
{
    "success": "true",
    "messages": [],
    "meta": {
        "resultCount": 10,
        "totalCount": 321,
        "limit": 10,
        "offset": 0
    },
    "links": {
        "current_url": "https://epos-msl.uu.nl/api/v2/datapublications/geoenergy?offset=0&limit=10"
    },
    "data": [
        {
            "title": "Microstructural data used in “Drill core from seismically active sandstone gas reservoir yields clues to internal deformation mechanisms”",
            "doi": "10.24416/uu01-kew4xn",
            "source": "https://public.yoda.uu.nl/geo/UU01/KEW4XN.html",
            "portalLink": "http://localhost/data-publication/859e6299397bc6dea8d9ce86648c8ce2",
            "name": "859e6299397bc6dea8d9ce86648c8ce2",
            "creators": [
                {
                    "name": "Berend Antonie Verberne",
                    "nameType": "Personal",
                    "givenName": "Berend Antonie",
                    "familyName": "Verberne",
                    "nameIdentifiers": [
                        {
                            "nameIdentifier": "https://orcid.org/0000-0002-1208-6193",
                            "nameIdentifierScheme": "ORCID",
                            "nameIdentifierUri": ""
                        }
                    ],
                    "affiliation": [
                        {
                            "name": "Shell Global Solutions",
                            "affiliationIdentifier": "",
                            "affiliationIdentifierScheme": "",
                            "schemeUri": ""
                        }
                    ]
                }
            ],
            "descriptions": [
                {
                    "description": "Europe’s largest gas field, the Groningen field (the Netherlands), is widely known for induced subsidence and seismicity caused by gas pressure depletion and associated compaction of the sandstone reservoir. Whether compaction is elastic or partly inelastic, as implied by recent experiments, is a key factor in forecasting system behavior and seismic hazard. We sought evidence for inelastic deformation through comparative microstructural analysis of unique drill core recovered from the seismogenic center of the field in 2015, 50 yr after gas production started, versus core recovered before production (1965). \nMicrostructural data were collected from: i) a suite of 1965 and 2015 core samples, and ii) a lab-deformed sample from the 1965 core and of its virgin counterpart sample. The data include section-scale, back-scattered electron (BSE) image mosaics, phase maps prepared using electron dispersive X-ray (EDX) mapping, electron backscatter diffraction (EBSD) maps, and cathodoluminescence (CL) micrographs. Further, we include maps of intragranular crack distributions in the form of manually drawn overlays to BSE mosaics. Crack map data are stored either as shapefiles (.shp) (used in ESRI ArcGIS) or as zip-files (.zip) comprising crack polygon (.roi) overlays (used in ImageJ – Schindelin et al., 2012). The data is provided in 1 zip-file (Data_Verberne_et_al_2020.zip). Detailed information about the files in these zip-file and how the data are processed is described in the research paper (https://doi.org/10.1130/G48243.1), plus accompanying supplementary material, and an additional explanation file, which are also all included in this data publication. Contact person is Suzanne Hangx – s.j.t.hangx@uu.nl. The work was funded by the Dutch Oil Company (Nederlandse Aardolie Maatschappij BV, NAM).\n",
                    "descriptionType": "Abstract"
                }
            ],
            "contributors": [],
            "materials": [
                "sedimentary rock",
                "sandstone",
                "wacke",
                "Slochteren sandstone",
                "minerals",
                "silicate minerals",
                "tectosilicates",
                "quartz",
                "phyllosilicates",
                "clay",
                "unconsolidated sediment",
                "clastic sediment",
                "clay",
                "clay - kaolinite",
                "kaolinite",
                "illite",
                "clay - smectite"
            ],
            "researchAspects": [
                "borehole drilling",
                "drill core"
            ],
            "files": [
                {
                    "fileName": "Additional_explanation_to_the_data.pdf",
                    "downloadLink": "https://geo.public.data.uu.nl:443/vault-groningen-zandsteencompactie/research-groningen-zandsteencompactie[1667459035]/original/Additional_explanation_to_the_data.pdf",
                    "extension": "pdf"
                },
                {
                    "fileName": "Data_Verberne_et_al_2020.zip",
                    "downloadLink": "https://geo.public.data.uu.nl:443/vault-groningen-zandsteencompactie/research-groningen-zandsteencompactie[1667459035]/original/Data_Verberne_et_al_2020.zip",
                    "extension": "zip"
                },
                {
                    "fileName": "Supplementary material to Verberne et al., 2020.pdf",
                    "downloadLink": "https://geo.public.data.uu.nl:443/vault-groningen-zandsteencompactie/research-groningen-zandsteencompactie[1667459035]/original/Supplementary material to Verberne et al., 2020.pdf",
                    "extension": "pdf"
                }
            ],
            "resource_type": "Research Data",
            "resource_type_general": "Dataset",
            "publication_year": "2022",
            "language": "en",
            "publisher": "Utrecht University",
            "citation": "Verberne, B. A., Hangx, S. J. T., Pijnenburg, R. P. J., Hamers, M. F., Drury, M. R., &amp; Spiers, C. J. (2022). <i>Microstructural data used in “Drill core from seismically active sandstone gas reservoir yields clues to internal deformation mechanisms” </i>(Version 1.0) [Data set]. Utrecht University. https://doi.org/10.24416/UU01-KEW4XN",
            "geojson": null,
            "surface_area": 0,
            "rightsList": [
                {
                    "rights": "Open - freely retrievable",
                    "rightsUri": "info:eu-repo/semantics/openAccess",
                    "rightsIdentifier": "",
                    "rightsIdentifierScheme": "",
                    "rightsSchemeUri": ""
                },
                {
                    "rights": "Creative Commons Attribution 4.0 International Public License",
                    "rightsUri": "https://creativecommons.org/licenses/by/4.0/legalcode",
                    "rightsIdentifier": "",
                    "rightsIdentifierScheme": "",
                    "rightsSchemeUri": ""
                }
            ],
            "alternateIdentifier": [],
            "fundingReferences": [
                {
                    "funderName": "Nederlandse Aardolie Maatschappij",
                    "funderIdentifier": "",
                    "funderIdentifierType": "",
                    "schemeUri": "",
                    "awardNumber": "",
                    "awardUri": "",
                    "awardTitle": ""
                }
            ],
            "dates": [
                {
                    "date": "2024-07-12T11:10:17",
                    "dateType": "Updated",
                    "dateInformation": ""
                },
                {
                    "date": "2015-07-01/2018-12-31",
                    "dateType": "Collected",
                    "dateInformation": ""
                }
            ],
            "sizes": [],
            "formats": [],
            "laboratories": [],
            "relatedIdentifiers": [
                {
                    "relatedIdentifier": "10.1130/G48243.1",
                    "relatedIdentifierType": "DOI",
                    "relationType": "IsSupplementTo",
                    "relatedMetadataScheme": "",
                    "schemeUri": "",
                    "schemeType": "",
                    "resourceTypeGeneral": ""
                },
                {
                    "relatedIdentifier": "10.1109/TSMC.1979.4310076",
                    "relatedIdentifierType": "DOI",
                    "relationType": "References",
                    "relatedMetadataScheme": "",
                    "schemeUri": "",
                    "schemeType": "",
                    "resourceTypeGeneral": ""
                },
                {
                    "relatedIdentifier": "10.1007/s00603-020-02215-y",
                    "relatedIdentifierType": "DOI",
                    "relationType": "References",
                    "relatedMetadataScheme": "",
                    "schemeUri": "",
                    "schemeType": "",
                    "resourceTypeGeneral": ""
                },
                {
                    "relatedIdentifier": "10.1029/2019JB017366",
                    "relatedIdentifierType": "DOI",
                    "relationType": "References",
                    "relatedMetadataScheme": "",
                    "schemeUri": "",
                    "schemeType": "",
                    "resourceTypeGeneral": ""
                },
                {
                    "relatedIdentifier": "10.1093/bioinformatics/btp184",
                    "relatedIdentifierType": "DOI",
                    "relationType": "References",
                    "relatedMetadataScheme": "",
                    "schemeUri": "",
                    "schemeType": "",
                    "resourceTypeGeneral": ""
                },
                {
                    "relatedIdentifier": "10.1038/nmeth.2019",
                    "relatedIdentifierType": "DOI",
                    "relationType": "References",
                    "relatedMetadataScheme": "",
                    "schemeUri": "",
                    "schemeType": "",
                    "resourceTypeGeneral": ""
                }
            ],
            "subjects": [
                {
                    "subject": "deformation mechanism",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "Keyword",
                    "classificationCode": "",
                    "EPOS_Uris": [
                        "https://epos-msl.uu.nl/voc/rockphysics/1.3/inferred_deformation_behavior-microphysical_deformation_mechanism"
                    ]
                },
                {
                    "subject": "subsidence",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "Keyword",
                    "classificationCode": "",
                    "EPOS_Uris": [
                        "https://epos-msl.uu.nl/voc/subsurface/1.3/surface_subsidence"
                    ]
                },
                {
                    "subject": "compaction",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "Keyword",
                    "classificationCode": "",
                    "EPOS_Uris": [
                        "https://epos-msl.uu.nl/voc/rockphysics/1.3/measured_property-strain",
                        "https://epos-msl.uu.nl/voc/analoguemodelling/1.3/measured_property-strain"
                    ]
                },
                {
                    "subject": "electron microscopy",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "Keyword",
                    "classificationCode": "",
                    "EPOS_Uris": [
                        "https://epos-msl.uu.nl/voc/microscopy/1.3/apparatus-electron_microscopy"
                    ]
                },
                {
                    "subject": "scanning electron microscope (SEM)",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "Keyword",
                    "classificationCode": "",
                    "EPOS_Uris": [
                        "https://epos-msl.uu.nl/voc/microscopy/1.3/apparatus-electron_microscopy-scanning_electron_microscope_sem"
                    ]
                }
            ],
            "subdomains": [
                "rock and melt physics",
                "analogue modelling of geologic processes",
                "microscopy and tomography",
                "geo-energy test beds",
                "geochemistry"
            ]
        }
    ]
}
```

</details>


### /all {#dp_all}
This endpoint gives access to all data-publications available that are marked as belonging to the rock physics (sub)domain. 

### Search all data-publications [GET]
+ Parameters

    + limit (number, optional) - The number of results to return.
        + Default: `10`
    + offset (number, optional) - The number to start results from. 
        + Default: `0`
    + query (text, optional) - Words to search for. 
        + Default: ``
    + subDomain (text, optional) - subDomain to filter on. 
        + Default: ``
    + authorName (text, optional) - Author names to search for. 
        + Default: ``
    + labName (text, optional) - Lab names to search for. 
        + Default: ``
    + title (text, optional) - Title to search for. 
        + Default: ``
    + tags (text, optional) - Tags to search for. 
        + Default: ``
    + hasDownloads (boolean, optional) - Filter results to only include results with download links.
        + Default: `false`
    + boundingBox 	(minx,miny,maxx,maxy) decimals, optional - Bounding box geographically filtering the results. If provided the bounding box must be valid. bounds: (-180, -90, 180, 90). 
        + Default: ``


        
+ Response 200 (application/json)

<details>
  <summary>view response</summary>
  
```json
{
    "success": "true",
    "messages": [],
    "meta": {
        "resultCount": 10,
        "totalCount": 321,
        "limit": 10,
        "offset": 0
    },
    "links": {
        "current_url": "http://localhost:8000/api/v2/datapublications/all?offset=0&limit=10"
    },
    "data": [
        {
            "title": "Contact model and numerical modelling results: “Compaction of the Groningen Gas Reservoir Sandstone: Discrete Element Modelling Using Microphysically Based Grain-Scale Interaction Laws”",
            "doi": "10.24416/uu01-575ewu",
            "source": "https://public.yoda.uu.nl/geo/UU01/575EWU.html",
            "portalLink": "http://localhost/data-publication/ad60ac76a738f696d9de7acc1506d491",
            "name": "ad60ac76a738f696d9de7acc1506d491",
            "creators": [
                {
                    "name": "Mohammad Hadi Mehranpour",
                    "nameType": "Personal",
                    "givenName": "Mohammad Hadi",
                    "familyName": "Mehranpour",
                    "nameIdentifiers": [
                        {
                            "nameIdentifier": "0000-0001-7336-0435",
                            "nameIdentifierScheme": "ORCID",
                            "nameIdentifierUri": ""
                        }
                    ],
                    "affiliation": [
                        {
                            "name": "Utrecht University",
                            "affiliationIdentifier": "",
                            "affiliationIdentifierScheme": "",
                            "schemeUri": ""
                        }
                    ]
                }
            ],
            "descriptions": [
                {
                    "description": "Reservoir compaction, surface subsidence and induced seismicity are often associated with prolonged hydrocarbon production. Recent experiments conducted on the Groningen gas field’s Slochteren sandstone reservoir rock, at in-situ conditions, have shown that compaction involves both poro-elastic strain and time-independent, permanent strain caused by consolidation and shear of clay films coating the sandstone grains, with grain failure occurring at higher stresses. To model compaction of the reservoir in space and time, numerical approaches, such as the Discrete Element Method (DEM) , populated with realistic grain-scale mechanisms are needed. We developed a new particle-interaction law (contact model) for the classic DEM to explicitly account for the experimentally observed mechanisms of non-linear elasticity, intergranular clay film deformation, and grain breakage. It was calibrated against both hydrostatic and conventional triaxial compression experiments and validated against an independent set of pore pressure depletion experiments conducted under uniaxial strain conditions, using a range of sample porosities, grain size distributions and clay contents. The model obtained was used to predict compaction of the Groningen reservoir. These results were compared with field measurements of in-situ compaction and matched favorably, within field measurement uncertainties. The new model allows systematic investigation of the effects of mineralogy, microstructure, boundary conditions and loading path on compaction behavior of the reservoir. It also offers a means of generating a data bank suitable for developing generalized constitutive models and for predicting reservoir response to different scenarios of gas extraction, or of fluid injection for stabilization or storage purposes.\n\nThe data provided in this dataset include the contact model (source codes and the contact model library) developed for the Particle Flow Code (PFC) software, Fish code package for running PFC models, numerical modeling results (tabulated) obtained in the calibration procedure and uniaxial compaction prediction.\n",
                    "descriptionType": "Abstract"
                }
            ],
            "contributors": [
                {
                    "name": "Mehranpour, Mohammad Hadi",
                    "contributorType": "DataCollector",
                    "nameType": "Personal",
                    "givenName": "",
                    "familyName": "",
                    "nameIdentifiers": [
                        {
                            "nameIdentifier": "0000-0001-7336-0435",
                            "nameIdentifierScheme": "ORCID",
                            "nameIdentifierUri": ""
                        }
                    ],
                    "affiliation": [
                        {
                            "name": "Utrecht University",
                            "affiliationIdentifier": "",
                            "affiliationIdentifierScheme": "",
                            "schemeUri": ""
                        }
                    ]
                },
                {
                    "name": "Hangx, Suzanne J.T.",
                    "contributorType": "ProjectManager",
                    "nameType": "Personal",
                    "givenName": "",
                    "familyName": "",
                    "nameIdentifiers": [
                        {
                            "nameIdentifier": "0000-0003-2253-3273",
                            "nameIdentifierScheme": "ORCID",
                            "nameIdentifierUri": ""
                        }
                    ],
                    "affiliation": [
                        {
                            "name": "Utrecht University",
                            "affiliationIdentifier": "",
                            "affiliationIdentifierScheme": "",
                            "schemeUri": ""
                        }
                    ]
                },
                {
                    "name": "Spiers, Christopher James",
                    "contributorType": "ProjectLeader",
                    "nameType": "Personal",
                    "givenName": "",
                    "familyName": "",
                    "nameIdentifiers": [
                        {
                            "nameIdentifier": "0000-0002-3436-8941",
                            "nameIdentifierScheme": "ORCID",
                            "nameIdentifierUri": ""
                        }
                    ],
                    "affiliation": [
                        {
                            "name": "Utrecht University",
                            "affiliationIdentifier": "",
                            "affiliationIdentifierScheme": "",
                            "schemeUri": ""
                        }
                    ]
                }
            ],
            "materials": [
                "sedimentary rock",
                "sandstone",
                "minerals",
                "silicate minerals",
                "tectosilicates",
                "quartz",
                "wacke",
                "Slochteren sandstone",
                "phyllosilicates",
                "clay",
                "unconsolidated sediment",
                "clastic sediment",
                "clay"
            ],
            "researchAspects": [
                "deformation behaviour",
                "inelastic deformation",
                "strain",
                "microphysical deformation mechanism",
                "intragranular cracking",
                "mechanical strength",
                "triaxial compressive strength (s1>s2=s3)",
                "elasticity",
                "bulk modulus",
                "poroelastic deformation",
                "elastic strain",
                "inelastic strain",
                "pore fluid pressure"
            ],
            "files": [
                {
                    "fileName": "calibration",
                    "downloadLink": "https://geo.public.data.uu.nl:443/vault-sandstone-compaction/Mehranpour_et_al_2021_DEModeling[1621322230]/original/calibration/",
                    "extension": ""
                },
                {
                    "fileName": "contact model",
                    "downloadLink": "https://geo.public.data.uu.nl:443/vault-sandstone-compaction/Mehranpour_et_al_2021_DEModeling[1621322230]/original/contact model/",
                    "extension": ""
                },
                {
                    "fileName": "PFC code",
                    "downloadLink": "https://geo.public.data.uu.nl:443/vault-sandstone-compaction/Mehranpour_et_al_2021_DEModeling[1621322230]/original/PFC code/",
                    "extension": ""
                },
                {
                    "fileName": "uniaxial compaction",
                    "downloadLink": "https://geo.public.data.uu.nl:443/vault-sandstone-compaction/Mehranpour_et_al_2021_DEModeling[1621322230]/original/uniaxial compaction/",
                    "extension": ""
                },
                {
                    "fileName": "Mehranpour_et-al_2021-description.docx",
                    "downloadLink": "https://geo.public.data.uu.nl:443/vault-sandstone-compaction/Mehranpour_et_al_2021_DEModeling[1621322230]/original/Mehranpour_et-al_2021-description.docx",
                    "extension": "docx"
                },
                {
                    "fileName": "yoda-metadata.json",
                    "downloadLink": "https://geo.public.data.uu.nl:443/vault-sandstone-compaction/Mehranpour_et_al_2021_DEModeling[1621322230]/original/yoda-metadata.json",
                    "extension": "json"
                }
            ],
            "resource_type": "Research Data",
            "resource_type_general": "Dataset",
            "publication_year": "2021",
            "language": "en",
            "publisher": "Utrecht University",
            "citation": "Mehranpour, M. H. (2021). <i>Contact model and numerical modelling results: “Compaction of the Groningen Gas Reservoir Sandstone: Discrete Element Modelling Using Microphysically Based Grain-Scale Interaction Laws”</i> (Version 1.0) [Data set]. Utrecht University. https://doi.org/10.24416/UU01-575EWU",
            "geojson": {
                "type": "FeatureCollection",
                "features": [
                    {
                        "type": "Feature",
                        "geometry": {
                            "type": "Polygon",
                            "coordinates": [
                                [
                                    [
                                        6.76325824029,
                                        53.3471390085
                                    ],
                                    [
                                        6.76325824029,
                                        53.3504181107
                                    ],
                                    [
                                        6.76068433733,
                                        53.3504181107
                                    ],
                                    [
                                        6.76068433733,
                                        53.3471390085
                                    ],
                                    [
                                        6.76325824029,
                                        53.3471390085
                                    ]
                                ]
                            ]
                        },
                        "properties": {
                            "name": "Groningen gas field’s Slochteren sandstone reservoir rock"
                        }
                    }
                ]
            },
            "surface_area": 0,
            "rightsList": [
                {
                    "rights": "Open - freely retrievable",
                    "rightsUri": "info:eu-repo/semantics/openAccess",
                    "rightsIdentifier": "",
                    "rightsIdentifierScheme": "",
                    "rightsSchemeUri": ""
                },
                {
                    "rights": "Creative Commons Attribution 4.0 International Public License",
                    "rightsUri": "https://creativecommons.org/licenses/by/4.0/legalcode",
                    "rightsIdentifier": "",
                    "rightsIdentifierScheme": "",
                    "rightsSchemeUri": ""
                }
            ],
            "alternateIdentifier": [],
            "fundingReferences": [
                {
                    "funderName": "Netherlands Research Council (NWO)",
                    "funderIdentifier": "",
                    "funderIdentifierType": "",
                    "schemeUri": "",
                    "awardNumber": "",
                    "awardUri": "",
                    "awardTitle": ""
                }
            ],
            "dates": [
                {
                    "date": "2021-05-18T11:05:08",
                    "dateType": "Updated",
                    "dateInformation": ""
                },
                {
                    "date": "2019-06-01/2020-01-01",
                    "dateType": "Collected",
                    "dateInformation": ""
                }
            ],
            "sizes": [],
            "formats": [],
            "laboratories": [],
            "relatedIdentifiers": [
                {
                    "relatedIdentifier": "https://doi.org/10.1029/2019JB018702",
                    "relatedIdentifierType": "DOI",
                    "relationType": "IsSupplementedBy",
                    "relatedMetadataScheme": "",
                    "schemeUri": "",
                    "schemeType": "",
                    "resourceTypeGeneral": ""
                },
                {
                    "relatedIdentifier": "https://doi.org/10.1029/2019JB017366",
                    "relatedIdentifierType": "DOI",
                    "relationType": "IsSupplementedBy",
                    "relatedMetadataScheme": "",
                    "schemeUri": "",
                    "schemeType": "",
                    "resourceTypeGeneral": ""
                }
            ],
            "subjects": [
                {
                    "subject": "Rock and melt physical properties",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "OECD FOS 2007",
                    "classificationCode": "",
                    "EPOS_Uris": []
                },
                {
                    "subject": "Discrete Element Method (DEM)",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "Keyword",
                    "classificationCode": "",
                    "EPOS_Uris": []
                },
                {
                    "subject": "Particle Flow Code (PFC)",
                    "schemeUri": "",
                    "valueUri": "",
                    "subjectScheme": "Keyword",
                    "classificationCode": "",
                    "EPOS_Uris": []
                }
            ],
            "subdomains": [
                "rock and melt physics",
                "analogue modelling of geologic processes",
                "microscopy and tomography"
            ]
        }
    ]
}
```
</details>

- ## Facilities {#facilities}


### /rock_physics {#f_rock_physics}
This endpoint gives access to all facilities available that are marked as belonging to the rock physics (sub)domain. 

### Search all rock physics facilities [GET]
+ Parameters

    + limit (number, optional) - The number of results to return.
        + Default: `10`
    + offset (number, optional) - The number to start results from. 
        + Default: `0`
    + tags (text, optional) - Tags to search for. 
        + Default: ``
    + query (text, optional) - Words to search for. 
        + Default: ``
    + title (text, optional) - Title to search for. 
        + Default: ``
    + country (text, optional) - Country to search for. 
        + Default: ``
    + city (text, optional) - City to search for. 
        + Default: ``
   + boundingBox 	(minx,miny,maxx,maxy) decimals, optional - Bounding box geographically filtering the results. If provided the bounding box must be valid. bounds: (-180, -90, 180, 90). 
        + Default: ``

        
+ Response 200 (application/json)

<details>
  <summary>view response</summary>
  
```json
 {
    "success": "true",
    "messages": [],
    "meta": {
        "resultCount": 1,
        "totalCount": 321,
        "limit": 1,
        "offset": 0
    },
    "links": {
        "current_url": "https://epos-msl.uu.nl/api/v2/facilities/rock_physics?offset=0&limit=1"
    },
    "data": [
        {
            "title": "Core Logging Lab",
            "portalLink": "http://localhost:8000/lab/66f041e16a60928b05a7e228a89c3799",
            "organisation": "National Institute of Oceanography and Applied Geophysics (OGS)",
            "domain": "Rock and melt physics",
            "descriptions": [
                {
                    "description": "Continuous centimetric scale measurements of different physical properties of marine sediment and rock samples are at the base of the most of geological and paleoenvironmental studies.",
                    "descriptionType": "Description"
                },
                {
                    "description": "<p>Continuous centimetric scale measurements of different physical properties of marine sediment and rock samples are at the base of the most of geological and paleoenvironmental studies.",
                    "descriptionType": "Description in HTML"
                }
            ],
            "equipment": [
                {
                    "name": "Multi Sensor Core Logger",
                    "descriptions": [
                        {
                            "description": "Gamma Density\r\nA gamma ray source and detector are mounted across the core on a sensor stand that aligns them with the centre of the core. ",
                            "descriptionType": "Description"
                        },
                        {
                            "description": "<p>Gamma Density\nA gamma ray source and detector are mounted across the core on a sensor stand that aligns them with the centre of the core. ",
                            "descriptionType": "Description in HTML"
                        }
                    ],
                    "domain": "Rock and melt physics",
                    "category": "Permanent",
                    "type": "Geotechnical equipment",
                    "group": "Physical Properties",
                    "brand": "Geotek®",
                    "addOns": [
                        {
                            "description": [
                                {
                                    "description": "Gamma Density (Bulk Density): 137Cs gamma source with 2.5mm or 5mm collimators; density resolution up to 1% (depending upon counting time",
                                    "descriptionType": "Description"
                                }
                            ],
                            "type": "Acoustic equipment",
                            "group": "Acoustic emission monitoring equipment"
                        },
                        {
                            "description": [
                                {
                                    "description": "P-wave velocity: 250-500 kHz piezo-electric ceramic transducers; accurate to about 0.2% (depending on core condition)",
                                    "descriptionType": "Description"
                                }
                            ],
                            "type": "Acoustic equipment",
                            "group": "Acoustic emission monitoring equipment"
                        },
                        {
                            "description": [
                                {
                                    "description": "Magnetic Susceptibility: Bartington loop sensor 60-120mm diameter, or point sensor (on split cores) giving 5% calibration accuracy",
                                    "descriptionType": "Description"
                                }
                            ],
                            "type": "Acoustic equipment",
                            "group": "Acoustic emission monitoring equipment"
                        },
                        {
                            "description": [
                                {
                                    "description": "Geoscan V linescan Imaging system: Images can be collected over the full core width between 100 and 1000 lines per centimetre, corresponding to 100 and 10 micron pixel sizes, respectively. Pixel sensor: 1x5K CCD ADC resolution: 14 bits per colour channel MAX pixel rate: 3.2M pixel/sec MAX scan rate: 200 lines/sec Down core res: up to 10 microns Output: 48-bit RGB TIFF",
                                    "descriptionType": "Description"
                                }
                            ],
                            "type": "Syn-deformation imaging equipment",
                            "group": "Camera imaging during testing"
                        },
                        {
                            "description": [
                                {
                                    "description": "Available July 2025 - The CM-700d spectrophotometer uses a diffused illumination, 8 degree viewing angle with a pulsed xenon lamp providing the illumination. The instrument detector collects light in 10 nm increments between 400 nm and 700 nm wavelength ranges.",
                                    "descriptionType": "Description"
                                }
                            ],
                            "type": "Syn-deformation imaging equipment",
                            "group": "Colour Spectrophotometry"
                        }
                    ]
                },
                {
                    "name": "Custom-built - X-radiography (μ-CT)",
                    "descriptions": [
                        {
                            "description": "Visually flat images can be created from scanned whole or split cores using software corrections.",
                            "descriptionType": "Description"
                        },
                        {
                            "description": "<p>Visually flat images can be created from scanned whole or split cores using software corrections. ",
                            "descriptionType": "Description in HTML"
                        }
                    ],
                    "domain": "Microscopy and tomography",
                    "category": "Permanent",
                    "type": "X-Ray Tomography",
                    "group": "X-ray CT (Computed Tomography)",
                    "brand": "Geotek®",
                    "addOns": []
                }
            ],
            "geojson": {
                "type": "Feature",
                "geometry": {
                    "type": "Point",
                    "coordinates": [
                        13.802235,
                        45.660068
                    ]
                },
                "properties": {
                    "city": "Trieste",
                    "country": "Italy"
                }
            },
            "contact": "http://localhost:8000/laboratory-contact-person/66f041e16a60928b05a7e228a89c3799"
        }
    ]
}
```

</details>

### /analogue {#f_analogue}
This endpoint gives access to all facilities available that are marked as belonging to the analogue modelling (sub)domain. 

### Search all analogue modelling facilities [GET]
+ Parameters

    + limit (number, optional) - The number of results to return.
        + Default: `10`
    + offset (number, optional) - The number to start results from. 
        + Default: `0`
    + tags (text, optional) - Tags to search for. 
        + Default: ``
    + query (text, optional) - Words to search for. 
        + Default: ``
    + title (text, optional) - Title to search for. 
        + Default: ``
    + country (text, optional) - Country to search for. 
        + Default: ``
    + city (text, optional) - City to search for. 
        + Default: ``
   + boundingBox 	(minx,miny,maxx,maxy) decimals, optional - Bounding box geographically filtering the results. If provided the bounding box must be valid. bounds: (-180, -90, 180, 90). 
        + Default: ``

        
+ Response 200 (application/json)

<details>
  <summary>view response</summary>
  
```json
{
    "success": "true",
    "messages": [],
    "meta": {
        "resultCount": 1,
        "totalCount": 321,
        "limit": 1,
        "offset": 0
    },
    "links": {
        "current_url": "https://epos-msl.uu.nl/api/v2/facilities/analogue?offset=0&limit=1"
    },
    "data": [
        {
            "title": "Core Logging Lab",
            "portalLink": "http://localhost:8000/lab/66f041e16a60928b05a7e228a89c3799",
            "organisation": "National Institute of Oceanography and Applied Geophysics (OGS)",
            "domain": "Analogue modelling of geologic processes",
            "descriptions": [
                {
                    "description": "Continuous centimetric scale measurements of different physical properties of marine sediment and rock samples are at the base of the most of geological and paleoenvironmental studies.",
                    "descriptionType": "Description"
                },
                {
                    "description": "<p>Continuous centimetric scale measurements of different physical properties of marine sediment and rock samples are at the base of the most of geological and paleoenvironmental studies.",
                    "descriptionType": "Description in HTML"
                }
            ],
            "equipment": [
                {
                    "name": "Multi Sensor Core Logger",
                    "descriptions": [
                        {
                            "description": "Gamma Density\r\nA gamma ray source and detector are mounted across the core on a sensor stand that aligns them with the centre of the core. ",
                            "descriptionType": "Description"
                        },
                        {
                            "description": "<p>Gamma Density\nA gamma ray source and detector are mounted across the core on a sensor stand that aligns them with the centre of the core. ",
                            "descriptionType": "Description in HTML"
                        }
                    ],
                    "domain": "Rock and melt physics",
                    "category": "Permanent",
                    "type": "Geotechnical equipment",
                    "group": "Physical Properties",
                    "brand": "Geotek®",
                    "addOns": [
                        {
                            "description": [
                                {
                                    "description": "Gamma Density (Bulk Density): 137Cs gamma source with 2.5mm or 5mm collimators; density resolution up to 1% (depending upon counting time",
                                    "descriptionType": "Description"
                                }
                            ],
                            "type": "Acoustic equipment",
                            "group": "Acoustic emission monitoring equipment"
                        },
                        {
                            "description": [
                                {
                                    "description": "P-wave velocity: 250-500 kHz piezo-electric ceramic transducers; accurate to about 0.2% (depending on core condition)",
                                    "descriptionType": "Description"
                                }
                            ],
                            "type": "Acoustic equipment",
                            "group": "Acoustic emission monitoring equipment"
                        },
                        {
                            "description": [
                                {
                                    "description": "Magnetic Susceptibility: Bartington loop sensor 60-120mm diameter, or point sensor (on split cores) giving 5% calibration accuracy",
                                    "descriptionType": "Description"
                                }
                            ],
                            "type": "Acoustic equipment",
                            "group": "Acoustic emission monitoring equipment"
                        },
                        {
                            "description": [
                                {
                                    "description": "Geoscan V linescan Imaging system: Images can be collected over the full core width between 100 and 1000 lines per centimetre, corresponding to 100 and 10 micron pixel sizes, respectively. Pixel sensor: 1x5K CCD ADC resolution: 14 bits per colour channel MAX pixel rate: 3.2M pixel/sec MAX scan rate: 200 lines/sec Down core res: up to 10 microns Output: 48-bit RGB TIFF",
                                    "descriptionType": "Description"
                                }
                            ],
                            "type": "Syn-deformation imaging equipment",
                            "group": "Camera imaging during testing"
                        },
                        {
                            "description": [
                                {
                                    "description": "Available July 2025 - The CM-700d spectrophotometer uses a diffused illumination, 8 degree viewing angle with a pulsed xenon lamp providing the illumination. The instrument detector collects light in 10 nm increments between 400 nm and 700 nm wavelength ranges.",
                                    "descriptionType": "Description"
                                }
                            ],
                            "type": "Syn-deformation imaging equipment",
                            "group": "Colour Spectrophotometry"
                        }
                    ]
                },
                {
                    "name": "Custom-built - X-radiography (μ-CT)",
                    "descriptions": [
                        {
                            "description": "Visually flat images can be created from scanned whole or split cores using software corrections.",
                            "descriptionType": "Description"
                        },
                        {
                            "description": "<p>Visually flat images can be created from scanned whole or split cores using software corrections. ",
                            "descriptionType": "Description in HTML"
                        }
                    ],
                    "domain": "Microscopy and tomography",
                    "category": "Permanent",
                    "type": "X-Ray Tomography",
                    "group": "X-ray CT (Computed Tomography)",
                    "brand": "Geotek®",
                    "addOns": []
                }
            ],
            "geojson": {
                "type": "Feature",
                "geometry": {
                    "type": "Point",
                    "coordinates": [
                        13.802235,
                        45.660068
                    ]
                },
                "properties": {
                    "city": "Trieste",
                    "country": "Italy"
                }
            },
            "contact": "http://localhost:8000/laboratory-contact-person/66f041e16a60928b05a7e228a89c3799"
        }
    ]
}
```

</details>

### /paleo {#f_paleo}
This endpoint gives access to all facilities available that are marked as belonging to the paleomagnetism (sub)domain. 

### Search all paleomagnetism facilities [GET]
+ Parameters

    + limit (number, optional) - The number of results to return.
        + Default: `10`
    + offset (number, optional) - The number to start results from. 
        + Default: `0`
    + tags (text, optional) - Tags to search for. 
        + Default: ``
    + query (text, optional) - Words to search for. 
        + Default: ``
    + title (text, optional) - Title to search for. 
        + Default: ``
    + country (text, optional) - Country to search for. 
        + Default: ``
    + city (text, optional) - City to search for. 
        + Default: ``
   + boundingBox 	(minx,miny,maxx,maxy) decimals, optional - Bounding box geographically filtering the results. If provided the bounding box must be valid. bounds: (-180, -90, 180, 90). 
        + Default: ``

        
+ Response 200 (application/json)

<details>
  <summary>view response</summary>
  
```json
{
    "success": "true",
    "messages": [],
    "meta": {
        "resultCount": 1,
        "totalCount": 321,
        "limit": 1,
        "offset": 0
    },
    "links": {
        "current_url": "https://epos-msl.uu.nl/api/v2/facilities/paleo?offset=0&limit=1"
    },
    "data": [
        {
            "title": "Laboratory for Environmental Magnetism",
            "portalLink": "http://localhost:8000/lab/19ca14e7ea6328a42e0eb13d585e4c22",
            "organisation": "Royal Meteorological Institute (KMI)",
            "domain": "Paleomagnetism",
            "descriptions": [
                {
                    "description": "Interpreting magnetic properties of natural samples is a complex challenge.",
                    "descriptionType": "Description"
                },
                {
                    "description": "<p>Interpreting magnetic properties of natural samples is a complex challenge.",
                    "descriptionType": "Description in HTML"
                }
            ],
            "equipment": [
                {
                    "name": "2G cryogenic magnetometer",
                    "descriptions": [
                        {
                            "description": "Large sample bore. Measurement of cubes up to 4 cm edge length possible",
                            "descriptionType": "Description"
                        },
                        {
                            "description": "<p>Large sample bore. Measurement of cubes up to 4 cm edge length possible</p>\n",
                            "descriptionType": "Description in HTML"
                        }
                    ],
                    "domain": "Paleomagnetism",
                    "category": "Permanent",
                    "type": "Magnetometer",
                    "group": "Cryogenic Magnetometer",
                    "brand": "",
                    "addOns": []
                },
                {
                    "name": "MPMS3",
                    "descriptions": [],
                    "domain": "Paleomagnetism",
                    "category": "Permanent",
                    "type": "Magnetometer",
                    "group": "Cryogenic Magnetometer",
                    "brand": "Quantum Design",
                    "addOns": []
                },
                {
                    "name": "Kappabridge KLY-4",
                    "descriptions": [
                        {
                            "description": "includes: heating unit, cooling unit, field dependence of magnetic low-field susceptibility",
                            "descriptionType": "Description"
                        },
                        {
                            "description": "<p>includes: heating unit, cooling unit, field dependence of magnetic low-field susceptibility</p>\n",
                            "descriptionType": "Description in HTML"
                        }
                    ],
                    "domain": "Paleomagnetism",
                    "category": "Permanent",
                    "type": "Susceptometer/Susceptibility Bridge",
                    "group": "Susceptibility Meter",
                    "brand": "Agico",
                    "addOns": []
                },
                {
                    "name": "MS3",
                    "descriptions": [],
                    "domain": "Paleomagnetism",
                    "category": "Permanent",
                    "type": "Susceptometer/Susceptibility Bridge",
                    "group": "Susceptibility Meter",
                    "brand": "Bartington",
                    "addOns": []
                },
                {
                    "name": "MS3",
                    "descriptions": [],
                    "domain": "Paleomagnetism",
                    "category": "Permanent",
                    "type": "Field Equipment",
                    "group": "Portable Susceptibility Meter",
                    "brand": "Bartington",
                    "addOns": []
                }
            ],
            "geojson": {
                "type": "Feature",
                "geometry": {
                    "type": "Point",
                    "coordinates": [
                        4.58774,
                        50.09665,
                        230
                    ]
                },
                "properties": {
                    "city": "Dourbes (Viroinval)",
                    "country": "Belgium"
                }
            },
            "contact": "http://localhost:8000/laboratory-contact-person/19ca14e7ea6328a42e0eb13d585e4c22"
        }
    ]
}
```

</details>

### /microscopy {#f_microscopy}
This endpoint gives access to all facilities available that are marked as belonging to the microscopy and tomography (sub)domain. 

### Search all microscopy and tomography facilities [GET]
+ Parameters

    + limit (number, optional) - The number of results to return.
        + Default: `10`
    + offset (number, optional) - The number to start results from. 
        + Default: `0`
    + tags (text, optional) - Tags to search for. 
        + Default: ``
    + query (text, optional) - Words to search for. 
        + Default: ``
    + title (text, optional) - Title to search for. 
        + Default: ``
    + country (text, optional) - Country to search for. 
        + Default: ``
    + city (text, optional) - City to search for. 
        + Default: ``
   + boundingBox 	(minx,miny,maxx,maxy) decimals, optional - Bounding box geographically filtering the results. If provided the bounding box must be valid. bounds: (-180, -90, 180, 90). 
        + Default: ``

        
+ Response 200 (application/json)

<details>
  <summary>view response</summary>
  
```json
{
    "success": "true",
    "messages": [],
    "meta": {
        "resultCount": 1,
        "totalCount": 321,
        "limit": 1,
        "offset": 0
    },
    "links": {
        "current_url": "https://epos-msl.uu.nl/api/v2/facilities/microscopy?offset=0&limit=1"
    },
    "data": [
        {
            "title": "Delft Petrophysics Lab",
            "portalLink": "http://localhost:8000/lab/34173cb38f07f89ddbebc2ac9128303f",
            "organisation": "Technische Universiteit Delft (TU Delft)",
            "domain": "Microscopy and tomography",
            "descriptions": [
                {
                    "description": "The X-ray Imaging Facility at Delft University of Technology (TUD) has been instrumental over the past ten years for applied earth science and civil engineering research in the Netherlands using X-ray computed tomography (CT).",
                    "descriptionType": "Description"
                },
                {
                    "description": "<p>The X-ray Imaging Facility at Delft University of Technology (TUD) has been instrumental over the past ten years for applied earth science and civil engineering research in the Netherlands using X-ray computed tomography (CT).",
                    "descriptionType": "Description in HTML"
                }
            ],
            "equipment": [
                {
                    "name": "Ultrapyc 5000",
                    "descriptions": [],
                    "domain": "Rock and melt physics",
                    "category": "Permanent",
                    "type": "Porosity and density measurement equipment",
                    "group": "Pycnometer",
                    "brand": "",
                    "addOns": []
                },
                {
                    "name": "Thermal conductivity test setup",
                    "descriptions": [],
                    "domain": "Rock and melt physics",
                    "category": "Permanent",
                    "type": "Thermal property measurement equipment",
                    "group": "Thermal conductivity test setup",
                    "brand": "",
                    "addOns": []
                },
                {
                    "name": "CoreTOM (μ-CT)",
                    "descriptions": [
                        {
                            "description": "Lab-scale micro computed tomography scanner to characterize microstructures of a wide variation of materials and sample diemensions. Samples with diameters of 1 mm to up to 12 cm can be scanned at with a maximum of 3 micrometer resolution. Large samples (>30 cm in length) can be scanned and the equipment has the capability to perform dynamic imaging.",
                            "descriptionType": "Description"
                        },
                        {
                            "description": "<p>Lab-scale micro computed tomography scanner to characterize microstructures of a wide variation of materials and sample diemensions. Samples with diameters of 1 mm to up to 12 cm can be scanned at with a maximum of 3 micrometer resolution. Large samples (&gt;30 cm in length) can be scanned and the equipment has the capability to perform dynamic imaging.</p>\n",
                            "descriptionType": "Description in HTML"
                        }
                    ],
                    "domain": "Microscopy and tomography",
                    "category": "Permanent",
                    "type": "X-Ray Tomography",
                    "group": "X-ray CT (Computed Tomography)",
                    "brand": "Tescan",
                    "addOns": []
                }
            ],
            "geojson": {
                "type": "Feature",
                "geometry": {
                    "type": "Point",
                    "coordinates": [
                        4.375744020366476,
                        51.99867212334709
                    ]
                },
                "properties": {
                    "city": "Delft",
                    "country": "Netherlands"
                }
            },
            "contact": "http://localhost:8000/laboratory-contact-person/34173cb38f07f89ddbebc2ac9128303f"
        }
    ]
}
```

</details>

### /geochemistry {#f_geochemistry}
This endpoint gives access to all facilities available that are marked as belonging to the geochemistry (sub)domain. 

### Search all geochemistry facilities [GET]
+ Parameters

    + limit (number, optional) - The number of results to return.
        + Default: `10`
    + offset (number, optional) - The number to start results from. 
        + Default: `0`
    + tags (text, optional) - Tags to search for. 
        + Default: ``
    + query (text, optional) - Words to search for. 
        + Default: ``
    + title (text, optional) - Title to search for. 
        + Default: ``
    + country (text, optional) - Country to search for. 
        + Default: ``
    + city (text, optional) - City to search for. 
        + Default: ``
   + boundingBox 	(minx,miny,maxx,maxy) decimals, optional - Bounding box geographically filtering the results. If provided the bounding box must be valid. bounds: (-180, -90, 180, 90). 
        + Default: ``

        
+ Response 200 (application/json)

<details>
  <summary>view response</summary>
  

```json
{
    "success": "true",
    "messages": [],
    "meta": {
        "resultCount": 1,
        "totalCount": 321,
        "limit": 1,
        "offset": 0
    },
    "links": {
        "current_url": "https://epos-msl.uu.nl/api/v2/facilities/geochemistry?offset=0&limit=1"
    },
    "data": [
        {
            "title": "The Netherlands Isotope Geochemistry Laboratory (NIGeL)",
            "portalLink": "http://localhost:8000/lab/43ec517d68b6edd3015b3edc9a11367b",
            "organisation": "Vrije Universiteit Amsterdam",
            "domain": "Geochemistry",
            "descriptions": [
                {
                    "description": "The Netherlands Isotope Geochemistry Laboratory (NIGeL) is a NWO-Large Infrastructure funded national research facility for geochemical and isotopic analysis serving the Earth & Planetary Science, Archaeology, Forensic and Cultural Heritage research communities within and beyond the Netherlands.",
                    "descriptionType": "Description"
                },
                {
                    "description": "<p>The Netherlands Isotope Geochemistry Laboratory (NIGeL) is a NWO-Large Infrastructure funded national research facility for geochemical and isotopic analysis serving the Earth &amp; Planetary Science, Archaeology, Forensic and Cultural Heritage research communities within and beyond the Netherlands.",
                    "descriptionType": "Description in HTML"
                }
            ],
            "equipment": [
                {
                    "name": "TritonXT",
                    "descriptions": [
                        {
                            "description": "Multi-collector TIMS that includes 10>13 Ohm capability for the analysis small sample size",
                            "descriptionType": "Description"
                        },
                        {
                            "description": "<p>Multi-collector TIMS that includes 10&gt;13 Ohm capability for the analysis small sample size</p>\n",
                            "descriptionType": "Description in HTML"
                        }
                    ],
                    "domain": "Geochemistry",
                    "category": "Permanent",
                    "type": "Mass Spectrometer",
                    "group": "Thermal ionization-mass spectrometer",
                    "brand": "ThermoFisher",
                    "addOns": []
                },
                {
                    "name": "iCAP TQ",
                    "descriptions": [
                        {
                            "description": "for the analysis of dissolved sample solutions or the analysis of solid samples when linked to either nano- or femto-second laser",
                            "descriptionType": "Description"
                        },
                        {
                            "description": "<p>for the analysis of dissolved sample solutions or the analysis of solid samples when linked to either nano- or femto-second laser</p>\n",
                            "descriptionType": "Description in HTML"
                        }
                    ],
                    "domain": "Geochemistry",
                    "category": "Permanent",
                    "type": "Mass Spectrometer",
                    "group": "Inductively coupled plasma mass spectrometer",
                    "brand": "ThermoFisher",
                    "addOns": []
                }
            ],
            "geojson": {
                "type": "Feature",
                "geometry": {
                    "type": "Point",
                    "coordinates": [
                        4.8627673299627086,
                        52.33556937356889
                    ]
                },
                "properties": {
                    "city": "Amsterdam",
                    "country": "Netherlands"
                }
            },
            "contact": "http://localhost:8000/laboratory-contact-person/43ec517d68b6edd3015b3edc9a11367b"
        }
    ]
}
```
</details>


### /geoenergy {#f_geoenergy}
This endpoint gives access to all facilities available that are marked as belonging to the Geo-energy test beds (sub)domain. 

### Search all Geo-energy test beds facilities [GET]
+ Parameters

    + limit (number, optional) - The number of results to return.
        + Default: `10`
    + offset (number, optional) - The number to start results from. 
        + Default: `0`
    + tags (text, optional) - Tags to search for. 
        + Default: ``
    + query (text, optional) - Words to search for. 
        + Default: ``
    + title (text, optional) - Title to search for. 
        + Default: ``
    + country (text, optional) - Country to search for. 
        + Default: ``
    + city (text, optional) - City to search for. 
        + Default: ``
   + boundingBox 	(minx,miny,maxx,maxy) decimals, optional - Bounding box geographically filtering the results. If provided the bounding box must be valid. bounds: (-180, -90, 180, 90). 
        + Default: ``

        
+ Response 200 (application/json)

<details>
  <summary>view response</summary>
  
```json
{
    "success": "true",
    "messages": [],
    "meta": {
        "resultCount": 1,
        "totalCount": 321,
        "limit": 1,
        "offset": 0
    },
    "links": {
        "current_url": "https://epos-msl.uu.nl/api/v2/facilities/geoenergy?offset=0&limit=1"
    },
    "data": [
        {
            "title": "Applied Geoscience Hydrogen and CO2 Laboratory",
            "portalLink": "http://localhost:8000/lab/6974ce5ac660610b44d9b9fed0ff9548",
            "organisation": "University of Edinburgh (UEDIN)",
            "domain": "Geo-energy test beds",
            "descriptions": [
                {
                    "description": "The Applied Geoscience Laboratory for Sustainable Energy has a suite of coupled thermal-hydro-mechanical-chemical experimental equipment for research into the secure and sustainable utilisation of the subsurface for low-carbon energy applications.",
                    "descriptionType": "Description"
                },
                {
                    "description": "<p>The Applied Geoscience Laboratory for Sustainable Energy has a suite of coupled thermal-hydro-mechanical-chemical experimental equipment for research into the secure and sustainable utilisation of the subsurface for low-carbon energy applications.</p>\n",
                    "descriptionType": "Description in HTML"
                }
            ],
            "equipment": [
                {
                    "name": "high PT Geochemistry reaction vessels",
                    "descriptions": [
                        {
                            "description": "Multi-phase geochemical reactions for hydrogen and CO2 at in-situ elevated temperatures, fluid types, and pressures.\r\nConstant pressure monitoring, with fluid and gas sampling supported by full rock, gas and fluid analysis.",
                            "descriptionType": "Description"
                        },
                        {
                            "description": "<p>Multi-phase geochemical reactions for hydrogen and CO2 at in-situ elevated temperatures, fluid types, and pressures.\nConstant pressure monitoring, with fluid and gas sampling supported by full rock, gas and fluid analysis.</p>\n",
                            "descriptionType": "Description in HTML"
                        }
                    ],
                    "domain": "Rock and melt physics",
                    "category": "Permanent",
                    "type": "Fluid transport apparatus",
                    "group": "high PT Geochemistry reaction vessels",
                    "brand": "",
                    "addOns": []
                },
                {
                    "name": "Multiphase Flow Micromodel",
                    "descriptions": [
                        {
                            "description": "High-Pressure Micromodel Experiments\r\n\r\nGlass micromodels with different pore networks and wettability.",
                            "descriptionType": "Description"
                        },
                        {
                            "description": "<p>High-Pressure Micromodel Experiments</p>\n<p>Glass micromodels with different pore networks and wettability.",
                            "descriptionType": "Description in HTML"
                        }
                    ],
                    "domain": "Rock and melt physics",
                    "category": "Permanent",
                    "type": "Fluid transport apparatus",
                    "group": "Multiphase Flow Micromodel",
                    "brand": "",
                    "addOns": []
                }
            ],
            "geojson": {
                "type": "Feature",
                "geometry": {
                    "type": "Point",
                    "coordinates": [
                        -3.175,
                        55.9228
                    ]
                },
                "properties": {
                    "city": "Edinburgh",
                    "country": "United Kingdom"
                }
            },
            "contact": "http://localhost:8000/laboratory-contact-person/6974ce5ac660610b44d9b9fed0ff9548"
        }
    ]
}
```

</details>


### /all {#f_all}
This endpoint gives access to all facilities available that are marked as belonging to the rock physics (sub)domain. 

### Search all facilities [GET]
+ Parameters

    + limit (number, optional) - The number of results to return.
        + Default: `10`
    + offset (number, optional) - The number to start results from. 
        + Default: `0`
    + query (text, optional) - Words to search for. 
        + Default: ``
    + subDomain (text, optional) - subDomain to filter on. 
        + Default: ``
    + authorName (text, optional) - Author names to search for. 
        + Default: ``
    + labName (text, optional) - Lab names to search for. 
        + Default: ``
    + title (text, optional) - Title to search for. 
        + Default: ``
    + tags (text, optional) - Tags to search for. 
        + Default: ``
    + hasDownloads (boolean, optional) - Filter results to only include results with download links.
        + Default: `false`
    + boundingBox 	(minx,miny,maxx,maxy) decimals, optional - Bounding box geographically filtering the results. If provided the bounding box must be valid. bounds: (-180, -90, 180, 90). 
        + Default: ``


        
+ Response 200 (application/json)

<details>
  <summary>view response</summary>
  
```json
{
    "success": "true",
    "messages": [],
    "meta": {
        "resultCount": 1,
        "totalCount": 321,
        "limit": 1,
        "offset": 0
    },
    "links": {
        "current_url": "https://epos-msl.uu.nl/api/v2/facilities/all?offset=0&limit=1"
    },
    "data": [
        {
            "title": "Core Logging Lab",
            "portalLink": "http://localhost:8000/lab/66f041e16a60928b05a7e228a89c3799",
            "organisation": "National Institute of Oceanography and Applied Geophysics (OGS)",
            "domain": "Rock and melt physics",
            "descriptions": [
                {
                    "description": "Continuous centimetric scale measurements of different physical properties of marine sediment and rock samples are at the base of the most of geological and paleoenvironmental studies.",
                    "descriptionType": "Description"
                },
                {
                    "description": "<p>Continuous centimetric scale measurements of different physical properties of marine sediment and rock samples are at the base of the most of geological and paleoenvironmental studies.",
                    "descriptionType": "Description in HTML"
                }
            ],
            "equipment": [
                {
                    "name": "Multi Sensor Core Logger",
                    "descriptions": [
                        {
                            "description": "Gamma Density\r\nA gamma ray source and detector are mounted across the core on a sensor stand that aligns them with the centre of the core. ",
                            "descriptionType": "Description"
                        },
                        {
                            "description": "<p>Gamma Density\nA gamma ray source and detector are mounted across the core on a sensor stand that aligns them with the centre of the core. ",
                            "descriptionType": "Description in HTML"
                        }
                    ],
                    "domain": "Rock and melt physics",
                    "category": "Permanent",
                    "type": "Geotechnical equipment",
                    "group": "Physical Properties",
                    "brand": "Geotek®",
                    "addOns": [
                        {
                            "description": [
                                {
                                    "description": "Gamma Density (Bulk Density): 137Cs gamma source with 2.5mm or 5mm collimators; density resolution up to 1% (depending upon counting time",
                                    "descriptionType": "Description"
                                }
                            ],
                            "type": "Acoustic equipment",
                            "group": "Acoustic emission monitoring equipment"
                        },
                        {
                            "description": [
                                {
                                    "description": "P-wave velocity: 250-500 kHz piezo-electric ceramic transducers; accurate to about 0.2% (depending on core condition)",
                                    "descriptionType": "Description"
                                }
                            ],
                            "type": "Acoustic equipment",
                            "group": "Acoustic emission monitoring equipment"
                        },
                        {
                            "description": [
                                {
                                    "description": "Magnetic Susceptibility: Bartington loop sensor 60-120mm diameter, or point sensor (on split cores) giving 5% calibration accuracy",
                                    "descriptionType": "Description"
                                }
                            ],
                            "type": "Acoustic equipment",
                            "group": "Acoustic emission monitoring equipment"
                        },
                        {
                            "description": [
                                {
                                    "description": "Geoscan V linescan Imaging system: Images can be collected over the full core width between 100 and 1000 lines per centimetre, corresponding to 100 and 10 micron pixel sizes, respectively. Pixel sensor: 1x5K CCD ADC resolution: 14 bits per colour channel MAX pixel rate: 3.2M pixel/sec MAX scan rate: 200 lines/sec Down core res: up to 10 microns Output: 48-bit RGB TIFF",
                                    "descriptionType": "Description"
                                }
                            ],
                            "type": "Syn-deformation imaging equipment",
                            "group": "Camera imaging during testing"
                        },
                        {
                            "description": [
                                {
                                    "description": "Available July 2025 - The CM-700d spectrophotometer uses a diffused illumination, 8 degree viewing angle with a pulsed xenon lamp providing the illumination. The instrument detector collects light in 10 nm increments between 400 nm and 700 nm wavelength ranges.",
                                    "descriptionType": "Description"
                                }
                            ],
                            "type": "Syn-deformation imaging equipment",
                            "group": "Colour Spectrophotometry"
                        }
                    ]
                },
                {
                    "name": "Custom-built - X-radiography (μ-CT)",
                    "descriptions": [
                        {
                            "description": "Visually flat images can be created from scanned whole or split cores using software corrections.",
                            "descriptionType": "Description"
                        },
                        {
                            "description": "<p>Visually flat images can be created from scanned whole or split cores using software corrections. ",
                            "descriptionType": "Description in HTML"
                        }
                    ],
                    "domain": "Microscopy and tomography",
                    "category": "Permanent",
                    "type": "X-Ray Tomography",
                    "group": "X-ray CT (Computed Tomography)",
                    "brand": "Geotek®",
                    "addOns": []
                }
            ],
            "geojson": {
                "type": "Feature",
                "geometry": {
                    "type": "Point",
                    "coordinates": [
                        13.802235,
                        45.660068
                    ]
                },
                "properties": {
                    "city": "Trieste",
                    "country": "Italy"
                }
            },
            "contact": "http://localhost:8000/laboratory-contact-person/66f041e16a60928b05a7e228a89c3799"
        }
    ]
}
```
</details>






















----------------------------------------------------------------------
### Deprecated API endpoints

There is another API available offering access to data through our CKAN portal. Nevertheless, this API contain less data and it is deprecated. Please make sure you use the above mentioned v2 API for your application or switch as soon as possible.

#### Base url

The base url for the API:

```
https://epos-msl.uu.nl/webservice/api
```

#### /rock_physics
This endpoint gives access to all data-publications available that are marked as belonging to the rock physics (sub)domain. 

##### Search all rock physics data-publications [GET]
+ Parameters

    + rows (number, optional) - The number of results to return.
        + Default: `10`
    + start (number, optional) - The number to start results from. 
        + Default: `0`
    + query (text, optional) - Words to search for. 
        + Default: ``
    + authorName (text, optional) - Author names to search for. 
        + Default: ``
    + labName (text, optional) - Lab names to search for. 
        + Default: ``
    + title (text, optional) - Title to search for. 
        + Default: ``
    + tags (text, optional) - Tags to search for. 
        + Default: ``
    + hasDownloads (boolean, optional) - Filter results to only include results with download links.
        + Default: `false`

        
+ Response 200 (application/json)

<details>
  <summary>view response</summary>
  
```json
{
    "success": true,
    "message": "",
    "result": {
        "count": 84,
        "resultCount": 2,
        "results": [
            {
                "title": "Stress-cycling data uniaxial compaction of quartz sand in various chemical environments",
                "name": "25c6bbb8590ad766b48a08c83c028899",
                "portalLink": "http://localhost:5000/data-publication/25c6bbb8590ad766b48a08c83c028899",
                "doi": "10.24416/UU01-VM3Z6I",
                "handle": "",
                "license": "",
                "version": "",
                "source": "http://dx.doi.org/10.24416/UU01-VM3Z6I",
                "publisher": "e2eaa4d7-e873-42c2-b449-7c1e1005bba3",
                "subdomain": [
                    "rock and melt physics",
                    "analogue modelling of geologic processes"
                ],
                "description": "Decarbonisation of the energy system requires new uses of porous subsurface reservoirs, where hot porous reservoirs can be utilised as sustainable sources of heat and electricity, while depleted ones can be employed to temporary store energy or permanently store waste. However, fluid injection induces a poro-elastic response of the reservoir rock, as well as a chemical response that is not well understood. We conducted uniaxial stress-cycling experiments on quartz sand aggregates to investigate the effect of pore fluid chemistry on short-term compaction. Two of the tested environments, low-vacuum (dry) and n-decane, were devoid of water, and the other environments included distilled water and five aqueous solutions with dissolved HCl and NaOH in various concentrations, covering pH values in the range 1 to 14. In addition, we collected acoustic emission data and performed microstructural analyses to gain insight into the deformation mechanisms.\n\nThe data is provided in one folder for 26 experiments/samples. Detailed information about the files in these subfolders as well as information on how the data is processed is given in the explanatory file Schimmel-et-al_2020_data-description.docx. Contact person is Mariska Schimmel - PhD - m.t.w.schimmel@uu.nl / marischimmel@gmail.com",
                "publicationDate": "2020-01-01",
                "citation": "Schimmel, M. T. W. (2020). Stress-cycling data uniaxial compaction of quartz sand in various chemical environments. <i>Utrecht University</i>. https://doi.org/10.24416/UU01-VM3Z6I",
                "creators": [
                    {
                        "authorName": "Schimmel, Mariska T.W.",
                        "authorOrcid": "0000-0002-9854-0552",
                        "authorScopus": "",
                        "authorAffiliation": "Utrecht University;"
                    }
                ],
                "contributors": [
                    {
                        "contributorName": "Schimmel, Mariska T.W.",
                        "contributorOrcid": "0000-0002-9854-0552",
                        "contributorScopus": "",
                        "contributorAffiliation": "Utrecht University;",
                        "contributorRole": "DataCollector"
                    },
                    {
                        "contributorName": "Schimmel, Mariska T.W.",
                        "contributorOrcid": "0000-0002-9854-0552",
                        "contributorScopus": "",
                        "contributorAffiliation": "Utrecht University;",
                        "contributorRole": "ContactPerson"
                    },
                    {
                        "contributorName": "Hangx, Suzanne J.T.",
                        "contributorOrcid": "0000-0003-2253-3273",
                        "contributorScopus": "",
                        "contributorAffiliation": "Utrecht University;",
                        "contributorRole": "Supervisor"
                    },
                    {
                        "contributorName": "Spiers, Christopher James",
                        "contributorOrcid": "0000-0002-3436-8941",
                        "contributorScopus": "",
                        "contributorAffiliation": "Utrecht University;",
                        "contributorRole": "Supervisor"
                    },
                    {
                        "contributorName": "Experimental rock deformation/HPT-Lab (Utrecht University, The Netherlands)",
                        "contributorOrcid": "",
                        "contributorScopus": "",
                        "contributorAffiliation": "Utrecht University;",
                        "contributorRole": "HostingInstitution"
                    }
                ],
                "references": [
                    {
                        "referenceDoi": "10.1007/s00603-020-02267-0",
                        "referenceHandle": "",
                        "referenceTitle": "Schimmel, M. T. W., Hangx, S. J. T., & Spiers, C. J. (2020). Impact of Chemical Environment on Compaction Behaviour of Quartz Sands during Stress-Cycling. Rock Mechanics and Rock Engineering, 54(3), 981–1003. https://doi.org/10.1007/s00603-020-02267-0\n",
                        "referenceType": "IsSupplementTo"
                    },
                    {
                        "referenceDoi": "10.5880/fidgeo.2019.005",
                        "referenceHandle": "",
                        "referenceTitle": "Schimmel, M., Hangx, S., &amp; Spiers, C. (2019). <i>Compaction creep data uniaxial compaction of quartz sand in various chemical environments</i> [Data set]. GFZ Data Services. https://doi.org/10.5880/FIDGEO.2019.005",
                        "referenceType": "Continues"
                    }
                ],
                "laboratories": [
                    "Experimental rock deformation/HPT-Lab (Utrecht University,  The Netherlands)"
                ],
                "materials": [
                    "sand",
                    "quartz"
                ],
                "spatial": [],
                "locations": [
                    "Heksenberg Formation at the Beaujean Quarry in Heerlen, the Netherlands"
                ],
                "coveredPeriods": [],
                "collectionPeriods": [],
                "maintainer": "",
                "downloads": [
                    {
                        "fileName": "Schimmel_et_al_2020_Data_description",
                        "downloadLink": "https://geo.public.data.uu.nl/vault-sand-compaction-chemistry-effects/Schimmel_et_al_2020_Cyclic_compaction_sand%5B1599588494%5D/original/Schimmel_et_al_2020_Data_description.docx"
                    }
                ],
                "researchAspects": [
                    "fluid-rock interaction",
                    "stress corrosion cracking"
                ]
            },
            {
                "title": "Rotary Shear Experiments on Glass Bead Aggregates",
                "name": "f7c0cf40a195dc0df5e2494e68b5a96a",
                "portalLink": "http://localhost:5000/data-publication/f7c0cf40a195dc0df5e2494e68b5a96a",
                "doi": "10.24416/UU01-HPZZ2M",
                "handle": "",
                "license": "",
                "version": "",
                "source": "http://dx.doi.org/10.24416/UU01-HPZZ2M",
                "publisher": "e2eaa4d7-e873-42c2-b449-7c1e1005bba3",
                "subdomain": [
                    "rock and melt physics"
                ],
                "description": "Constant sliding velocity (i.e. rate of rotation) friction experiments on mm-thick layers of glass beads, under room temperature and humidity conditions.\n\nStick-slip in sheared granular aggregates is considered to be an analog for the intermittent deformation of the earth’s lithosphere via earthquakes. Stick-slip can be regular, i.e. periodic and of consistent amplitude, or irregular, i.e. aperiodic and of varying amplitude. In the context of seismology, the former behavior resembles the Characteristic Earthquake Model, whereas the latter is equivalent to the Gutenberg-Richter Model.\n\nThis publication contains mechanical and acoustic emission (AE) data from sliding experiments on aggregates of soda-lime glass beads. By tuning certain parameters of the experiment, our system is able to produce either regular or irregular stick-slip. Mechanical data, namely forces (axial and lateral) and displacements (ditto), have been sampled in continuous mode (also known as “streaming mode” or “First In, First Out; FIFO”), whereas AE waveforms have been sampled in block mode (also known as “trigger mode”). A single, multichannel data acquisition system was used to acquire all of the data, ensuring a common time base.\n\nThe experiments have been performed under normal stress values of 4 or 8 MPa and the samples have been sheared to large displacements; many times larger than their initial thickness of approximately 4.5 mm. Therefore, this data set fills a gap between most experiments reported in the physics literature (low normal stress and large shear displacement) and the geoscience literature (high normal stress and small shear displacement).\n\nThe data is provided in 12 subfolders for 12 experiments/samples. Detailed information about the files in these subfolders as well as information on how the data is processed is given in the explanatory file Korkolis_et_al_2021_Data_Description.docx. Contact person is Evangelos Korkolis - Researcher - ekorko@gmail.com",
                "publicationDate": "2021-01-01",
                "citation": "Korkolis, E. (2021). Rotary Shear Experiments on Glass Bead Aggregates. <i>Utrecht University</i>. https://doi.org/10.24416/UU01-HPZZ2M",
                "creators": [
                    {
                        "authorName": "Korkolis, Evangelos",
                        "authorOrcid": "0000-0002-6485-1395",
                        "authorScopus": "",
                        "authorAffiliation": "Utrecht University;"
                    }
                ],
                "contributors": [
                    {
                        "contributorName": "Korkolis, Evangelos",
                        "contributorOrcid": "0000-0002-6485-1395",
                        "contributorScopus": "",
                        "contributorAffiliation": "Utrecht University;",
                        "contributorRole": "DataCollector"
                    },
                    {
                        "contributorName": "Korkolis, Evangelos",
                        "contributorOrcid": "0000-0002-6485-1395",
                        "contributorScopus": "",
                        "contributorAffiliation": "Utrecht University;",
                        "contributorRole": "ContactPerson"
                    },
                    {
                        "contributorName": "Niemeijer, Andre Rik",
                        "contributorOrcid": "0000-0003-3983-9308",
                        "contributorScopus": "",
                        "contributorAffiliation": "Utrecht University;",
                        "contributorRole": "Supervisor"
                    },
                    {
                        "contributorName": "Paulssen, Hanneke",
                        "contributorOrcid": "0000-0003-2799-7288",
                        "contributorScopus": "",
                        "contributorAffiliation": "Utrecht University;",
                        "contributorRole": "Researcher"
                    },
                    {
                        "contributorName": "Trampert, Jeannot A.",
                        "contributorOrcid": "0000-0002-5868-9491",
                        "contributorScopus": "",
                        "contributorAffiliation": "Utrecht University;",
                        "contributorRole": "Supervisor"
                    },
                    {
                        "contributorName": "Experimental rock deformation/HPT-Lab (Utrecht University, The Netherlands)",
                        "contributorOrcid": "",
                        "contributorScopus": "",
                        "contributorAffiliation": "Utrecht University;",
                        "contributorRole": "HostingInstitution"
                    }
                ],
                "references": [
                    {
                        "referenceDoi": "10.1002/essoar.10505896.1",
                        "referenceHandle": "",
                        "referenceTitle": "Korkolis, E., Niemeijer, A., Paulssen, H., & Trampert, J. (2021). A Laboratory Perspective on the Gutenberg-Richter and Characteristic Earthquake Models. https://doi.org/10.1002/essoar.10505896.1\n",
                        "referenceType": "IsSupplementTo"
                    },
                    {
                        "referenceDoi": "",
                        "referenceHandle": "",
                        "referenceTitle": "",
                        "referenceType": "References"
                    }
                ],
                "laboratories": [
                    "Experimental rock deformation/HPT-Lab (Utrecht University,  The Netherlands)"
                ],
                "materials": [
                    "glass microspheres"
                ],
                "spatial": [],
                "locations": [],
                "coveredPeriods": [],
                "collectionPeriods": [
                    {
                        "startDate": "2017-06-21",
                        "endDate": "2018-03-14"
                    }
                ],
                "maintainer": "",
                "downloads": [
                    {
                        "fileName": "Korkolis_et_al_2021_Data_documentation",
                        "downloadLink": "https://geo.public.data.uu.nl/vault-rotary-shear-experiments-on-glass-bead-aggregates/Korkolis_2021%5B1613641324%5D/original/Korkolis_et_al_2021_Data_documentation.docx"
                    }
                ],
                "researchAspects": []
            }
        ]
    }
}
```

</details>

#### /analogue
This endpoint gives access to all data-publications available that are marked as belonging to the analogue modelling (sub)domain. 

##### Search all analogue modelling data-publications [GET]
+ Parameters

    + rows (number, optional) - The number of results to return.
        + Default: `10`
    + start (number, optional) - The number to start results from. 
        + Default: `0`
    + query (text, optional) - Words to search for. 
        + Default: ``
    + authorName (text, optional) - Author names to search for. 
        + Default: ``
    + labName (text, optional) - Lab names to search for. 
        + Default: ``
    + title (text, optional) - Title to search for. 
        + Default: ``
    + tags (text, optional) - Tags to search for. 
        + Default: ``
    + hasDownloads (boolean, optional) - Filter results to only include results with download links.
        + Default: `false`

        
+ Response 200 (application/json)

<details>
  <summary>view response</summary>
  
```json
{
    "success": true,
    "message": "",
    "result": {
        "count": 100,
        "resultCount": 4,
        "results": [
            {
                "title": "Stress-cycling data uniaxial compaction of quartz sand in various chemical environments",
                "name": "25c6bbb8590ad766b48a08c83c028899",
                "portalLink": "http://localhost:5000/data-publication/25c6bbb8590ad766b48a08c83c028899",
                "doi": "10.24416/UU01-VM3Z6I",
                "handle": "",
                "license": "",
                "version": "",
                "source": "http://dx.doi.org/10.24416/UU01-VM3Z6I",
                "publisher": "e2eaa4d7-e873-42c2-b449-7c1e1005bba3",
                "subdomain": [
                    "rock and melt physics",
                    "analogue modelling of geologic processes"
                ],
                "description": "Decarbonisation of the energy system requires new uses of porous subsurface reservoirs, where hot porous reservoirs can be utilised as sustainable sources of heat and electricity, while depleted ones can be employed to temporary store energy or permanently store waste. However, fluid injection induces a poro-elastic response of the reservoir rock, as well as a chemical response that is not well understood. We conducted uniaxial stress-cycling experiments on quartz sand aggregates to investigate the effect of pore fluid chemistry on short-term compaction. Two of the tested environments, low-vacuum (dry) and n-decane, were devoid of water, and the other environments included distilled water and five aqueous solutions with dissolved HCl and NaOH in various concentrations, covering pH values in the range 1 to 14. In addition, we collected acoustic emission data and performed microstructural analyses to gain insight into the deformation mechanisms.\n\nThe data is provided in one folder for 26 experiments/samples. Detailed information about the files in these subfolders as well as information on how the data is processed is given in the explanatory file Schimmel-et-al_2020_data-description.docx. Contact person is Mariska Schimmel - PhD - m.t.w.schimmel@uu.nl / marischimmel@gmail.com",
                "publicationDate": "2020-01-01",
                "citation": "Schimmel, M. T. W. (2020). Stress-cycling data uniaxial compaction of quartz sand in various chemical environments. <i>Utrecht University</i>. https://doi.org/10.24416/UU01-VM3Z6I",
                "creators": [
                    {
                        "authorName": "Schimmel, Mariska T.W.",
                        "authorOrcid": "0000-0002-9854-0552",
                        "authorScopus": "",
                        "authorAffiliation": "Utrecht University;"
                    }
                ],
                "contributors": [
                    {
                        "contributorName": "Schimmel, Mariska T.W.",
                        "contributorOrcid": "0000-0002-9854-0552",
                        "contributorScopus": "",
                        "contributorAffiliation": "Utrecht University;",
                        "contributorRole": "DataCollector"
                    },
                    {
                        "contributorName": "Schimmel, Mariska T.W.",
                        "contributorOrcid": "0000-0002-9854-0552",
                        "contributorScopus": "",
                        "contributorAffiliation": "Utrecht University;",
                        "contributorRole": "ContactPerson"
                    },
                    {
                        "contributorName": "Hangx, Suzanne J.T.",
                        "contributorOrcid": "0000-0003-2253-3273",
                        "contributorScopus": "",
                        "contributorAffiliation": "Utrecht University;",
                        "contributorRole": "Supervisor"
                    },
                    {
                        "contributorName": "Spiers, Christopher James",
                        "contributorOrcid": "0000-0002-3436-8941",
                        "contributorScopus": "",
                        "contributorAffiliation": "Utrecht University;",
                        "contributorRole": "Supervisor"
                    },
                    {
                        "contributorName": "Experimental rock deformation/HPT-Lab (Utrecht University, The Netherlands)",
                        "contributorOrcid": "",
                        "contributorScopus": "",
                        "contributorAffiliation": "Utrecht University;",
                        "contributorRole": "HostingInstitution"
                    }
                ],
                "references": [
                    {
                        "referenceDoi": "10.1007/s00603-020-02267-0",
                        "referenceHandle": "",
                        "referenceTitle": "Schimmel, M. T. W., Hangx, S. J. T., & Spiers, C. J. (2020). Impact of Chemical Environment on Compaction Behaviour of Quartz Sands during Stress-Cycling. Rock Mechanics and Rock Engineering, 54(3), 981–1003. https://doi.org/10.1007/s00603-020-02267-0\n",
                        "referenceType": "IsSupplementTo"
                    },
                    {
                        "referenceDoi": "10.5880/fidgeo.2019.005",
                        "referenceHandle": "",
                        "referenceTitle": "Schimmel, M., Hangx, S., &amp; Spiers, C. (2019). <i>Compaction creep data uniaxial compaction of quartz sand in various chemical environments</i> [Data set]. GFZ Data Services. https://doi.org/10.5880/FIDGEO.2019.005",
                        "referenceType": "Continues"
                    }
                ],
                "laboratories": [
                    "Experimental rock deformation/HPT-Lab (Utrecht University,  The Netherlands)"
                ],
                "materials": [
                    "sand",
                    "quartz"
                ],
                "spatial": [],
                "locations": [
                    "Heksenberg Formation at the Beaujean Quarry in Heerlen, the Netherlands"
                ],
                "coveredPeriods": [],
                "collectionPeriods": [],
                "maintainer": "",
                "downloads": [
                    {
                        "fileName": "Schimmel_et_al_2020_Data_description",
                        "downloadLink": "https://geo.public.data.uu.nl/vault-sand-compaction-chemistry-effects/Schimmel_et_al_2020_Cyclic_compaction_sand%5B1599588494%5D/original/Schimmel_et_al_2020_Data_description.docx"
                    }
                ],
                "researchAspects": [
                    "fluid-rock interaction"
                ]
            },
            {
                "title": "Intergranular clay films control inelastic deformation in the Groningen gas reservoir: Evidence from split-cylinder deformation tests",
                "name": "b8cc7a14dfe9acbe30eede1126d18565",
                "portalLink": "http://localhost:5000/data-publication/b8cc7a14dfe9acbe30eede1126d18565",
                "doi": "10.24416/UU01-8AVM9K",
                "handle": "",
                "license": "",
                "version": "",
                "source": "http://dx.doi.org/10.24416/UU01-8AVM9K",
                "publisher": "e2eaa4d7-e873-42c2-b449-7c1e1005bba3",
                "subdomain": [
                    "rock and melt physics",
                    "analogue modelling of geologic processes"
                ],
                "description": "Production of oil and gas from sandstone reservoirs leads to small elastic and inelastic strains in the reservoir, which may induce surface subsidence and seismicity. While the elastic component is easily described, the inelastic component, and any rate-sensitivity thereof remain poorly understood in the relevant small strain range (≤ 1.0%). To address this, we performed a sequence of five stress/strain-cycling plus strain-marker-imaging experiments on a single split-cylinder sample (porosity 20.4%) of Slochteren sandstone from the seismogenic Groningen gas field. The tests were performed under in-situ conditions of effective confining pressure (40 MPa) and temperature (100°C), exploring increasingly large differential stresses (up to 75 MPa) and/or axial strains (up to 4.8%) in consecutive runs. At the small strains relevant to producing reservoirs (≤ 1.0%), inelastic deformation was largely accommodated by deformation of clay-filled grain contacts. High axial strains (>1.4%) led to pervasive intragranular cracking plus intergranular slip within localized, conjugate bands. Using a simplified sandstone model, we show that the magnitude of inelastic deformation produced in our experiments at small strains (≤ 1.0%) and stresses relevant to the Groningen reservoir can indeed be roughly accounted for by clay film deformation. Thus, inelastic compaction of the Groningen reservoir is expected to be largely governed by clay film deformation. Compaction by this mechanism is shown to be rate-insensitive on production time-scales, and is anticipated to halt when gas production stops. However, creep by other processes cannot be eliminated. Similar, clay-bearing sandstone reservoirs occur widespread globally, implying a wide relevance of our results.\n\nThe data is provided in a folder with 3 subfolders for 5 experiments/samples. Detailed information about the files in these subfolders as well as information on how the data is processed is given in the explanatory file Pijnenburg-et-al_2019_data-description.docx. Contact person is Ronald Pijnenburg - Researcher - r.p.j.pijnenburg@uu.nl.",
                "publicationDate": "2019-01-01",
                "citation": "Pijnenburg, R., Verberne, B. A., Hangx, S., &amp; Spiers, C. J. (2019). Intergranular clay films control inelastic deformation in the Groningen gas reservoir: Evidence from split-cylinder deformation tests. <i>Utrecht University</i>. https://doi.org/10.24416/UU01-8AVM9K",
                "creators": [
                    {
                        "authorName": "Pijnenburg, Ronald",
                        "authorOrcid": "0000-0003-0653-7565",
                        "authorScopus": "",
                        "authorAffiliation": "Utrecht University;"
                    },
                    {
                        "authorName": "Verberne, Berend Antonie",
                        "authorOrcid": "0000-0002-1208-6193",
                        "authorScopus": "",
                        "authorAffiliation": "National Institute of Advanced Industrial Science and Technology, Geological Survey of Japan, Tsukuba, Japan;"
                    },
                    {
                        "authorName": "Hangx, Suzanne",
                        "authorOrcid": "0000-0003-2253-3273",
                        "authorScopus": "",
                        "authorAffiliation": "Utrecht University;"
                    },
                    {
                        "authorName": "Spiers, Christopher James",
                        "authorOrcid": "0000-0002-3436-8941",
                        "authorScopus": "",
                        "authorAffiliation": "Utrecht University;"
                    }
                ],
                "contributors": [
                    {
                        "contributorName": "Pijnenburg, Ronald",
                        "contributorOrcid": "0000-0003-0653-7565",
                        "contributorScopus": "",
                        "contributorAffiliation": "Utrecht University;",
                        "contributorRole": "ContactPerson"
                    },
                    {
                        "contributorName": "Verberne, Berend Antonie",
                        "contributorOrcid": "0000-0002-1208-6193",
                        "contributorScopus": "",
                        "contributorAffiliation": "National Institute of Advanced Industrial Science and Technology, Geological Survey of Japan, Tsukuba, Japan;",
                        "contributorRole": "Researcher"
                    },
                    {
                        "contributorName": "Hangx, Suzanne",
                        "contributorOrcid": "0000-0003-2253-3273",
                        "contributorScopus": "",
                        "contributorAffiliation": "Utrecht University;",
                        "contributorRole": "Supervisor"
                    },
                    {
                        "contributorName": "Spiers, Christopher James",
                        "contributorOrcid": "0000-0002-3436-8941",
                        "contributorScopus": "",
                        "contributorAffiliation": "Utrecht University;",
                        "contributorRole": "Supervisor"
                    },
                    {
                        "contributorName": "Experimental rock deformation/HPT-Lab (Utrecht University, The Netherlands)",
                        "contributorOrcid": "",
                        "contributorScopus": "",
                        "contributorAffiliation": "Utrecht University;",
                        "contributorRole": "HostingInstitution"
                    }
                ],
                "references": [
                    {
                        "referenceDoi": "10.1029/2019JB018702",
                        "referenceHandle": "",
                        "referenceTitle": "Pijnenburg, R. P. J., Verberne, B. A., Hangx, S. J. T., & Spiers, C. J. (2019). Intergranular Clay Films Control Inelastic Deformation in the Groningen Gas Reservoir: Evidence From Split‐Cylinder Deformation Tests. Journal of Geophysical Research: Solid Earth, 124(12), 12679–12702. Portico. https://doi.org/10.1029/2019jb018702\n",
                        "referenceType": "IsSupplementTo"
                    },
                    {
                        "referenceDoi": "10.1093/bioinformatics/btp184",
                        "referenceHandle": "",
                        "referenceTitle": "Preibisch, S., Saalfeld, S., & Tomancak, P. (2009). Globally optimal stitching of tiled 3D microscopic image acquisitions. Bioinformatics, 25(11), 1463–1465. https://doi.org/10.1093/bioinformatics/btp184\n",
                        "referenceType": "IsReferencedBy"
                    }
                ],
                "laboratories": [
                    "Experimental rock deformation/HPT-Lab (Utrecht University,  The Netherlands)"
                ],
                "materials": [
                    "sandstone",
                    "clay"
                ],
                "spatial": [],
                "locations": [
                    "53.14228274279933, 6.447993994041667, 53.460706452448434, 7.041255712791667"
                ],
                "coveredPeriods": [],
                "collectionPeriods": [
                    {
                        "startDate": "2017-01-01",
                        "endDate": "2019-01-01"
                    }
                ],
                "maintainer": "",
                "downloads": [
                    {
                        "fileName": "Pijnenburg-et-al_2019_data-description",
                        "downloadLink": "https://geo.public.data.uu.nl/vault-groningen-sandstone-compaction/Pijnenburg_et_al_2019_Splitcylinder_tests%5B1573723940%5D/original/Pijnenburg-et-al_2019_data-description.docx"
                    }
                ],
                "researchAspects": [
                    "strength"
                ]
            },
            {
                "title": "Hydrothermal friction data of samples obtained from outcrops of the Mai'iu Fault, Papua New Guinae",
                "name": "d6a53a8c97d7d57651f704d4f09dbe7a",
                "portalLink": "http://localhost:5000/data-publication/d6a53a8c97d7d57651f704d4f09dbe7a",
                "doi": "10.24416/UU01-7GJW0G",
                "handle": "",
                "license": "",
                "version": "",
                "source": "http://dx.doi.org/10.24416/UU01-7GJW0G",
                "publisher": "e2eaa4d7-e873-42c2-b449-7c1e1005bba3",
                "subdomain": [
                    "rock and melt physics",
                    "analogue modelling of geologic processes"
                ],
                "description": "General friction and rate and state friction data of powdered fault gouge samples derived from outcrops of the Mai'iu Fault, Papua New Guinae, which is one of the world's most rapidly slipping low-angle normal faults, The experiments show that gouges from the shallowest portion of the fault zone are predominantly weak and velocity-strengthening, while fault rocks deformed at greater depths are stronger and velocity-weakening. Evaluating the geodetic and friction results together with geophysical and microstructural evidence for mixed-mode seismic and aseismic slip at depth, we find that the Mai'iu fault is most likely strongly locked at depths of ~ 5 -16 km and creeping updip and downdip of this region. Our results suggest that the Mai'iu fault and other active low-angle normal faults can slip in large (M > 7) earthquakes despite near-surface interseismic creep on frictionally stable clay-rich gouges.\n\nThe data is provided in 10 subfolders for 10 experiments/samples. Detailed information about the files in these subfolders as well as information on how the data is processed is given in the explanatory file Biemiller-et-al-2020-Data-Description.docx. Contact person is André Niemeijer - Assistant proffesor - a.r.niemeijer@uu.nl - https://www.uu.nl/staff/ARNiemeijer?t=0",
                "publicationDate": "2020-01-01",
                "citation": "Niemeijer, A. R. (2020). Hydrothermal friction data of samples obtained from outcrops of the Mai'iu Fault, Papua New Guinae. <i>Utrecht University</i>. https://doi.org/10.24416/UU01-7GJW0G",
                "creators": [
                    {
                        "authorName": "Niemeijer, Andre Rik",
                        "authorOrcid": "0000-0003-3983-9308",
                        "authorScopus": "",
                        "authorAffiliation": "Utrecht University;"
                    }
                ],
                "contributors": [
                    {
                        "contributorName": "Niemeijer, Andre Rik",
                        "contributorOrcid": "0000-0003-3983-9308",
                        "contributorScopus": "",
                        "contributorAffiliation": "Utrecht University;",
                        "contributorRole": "ContactPerson"
                    },
                    {
                        "contributorName": "Niemeijer, Andre Rik",
                        "contributorOrcid": "0000-0003-3983-9308",
                        "contributorScopus": "",
                        "contributorAffiliation": "Utrecht University;",
                        "contributorRole": "DataCollector"
                    },
                    {
                        "contributorName": "Boulton, Carolyn",
                        "contributorOrcid": "0000-0003-0597-6152",
                        "contributorScopus": "",
                        "contributorAffiliation": "Victoria University of Wellington;",
                        "contributorRole": "DataCollector"
                    },
                    {
                        "contributorName": "Boulton, Carolyn",
                        "contributorOrcid": "0000-0003-0597-6152",
                        "contributorScopus": "",
                        "contributorAffiliation": "Victoria University of Wellington;",
                        "contributorRole": "ProjectMember"
                    },
                    {
                        "contributorName": "Biemiller, James B.",
                        "contributorOrcid": "0000-0001-6663-7811",
                        "contributorScopus": "",
                        "contributorAffiliation": "University of Texas;",
                        "contributorRole": "ProjectMember"
                    },
                    {
                        "contributorName": "Wallace, Laura M.",
                        "contributorOrcid": "0000-0003-2070-0891",
                        "contributorScopus": "",
                        "contributorAffiliation": "GNS Science, Lower Hutt, New Zealand;",
                        "contributorRole": "ProjectMember"
                    },
                    {
                        "contributorName": "Ellis, Susan",
                        "contributorOrcid": "0000-0002-2754-4687",
                        "contributorScopus": "",
                        "contributorAffiliation": "GNS Science, Lower Hutt, New Zealand;",
                        "contributorRole": "ProjectMember"
                    },
                    {
                        "contributorName": "Little, Timothy",
                        "contributorOrcid": "0000-0002-5783-6429",
                        "contributorScopus": "",
                        "contributorAffiliation": "Victoria University of Wellington;",
                        "contributorRole": "ProjectLeader"
                    },
                    {
                        "contributorName": "Mizera, Marcel",
                        "contributorOrcid": "0000-0002-6439-0103",
                        "contributorScopus": "",
                        "contributorAffiliation": "Victoria University of Wellington;",
                        "contributorRole": "ProjectMember"
                    },
                    {
                        "contributorName": "Lavier, Luc L.",
                        "contributorOrcid": "0000-0001-7839-4263",
                        "contributorScopus": "",
                        "contributorAffiliation": "University of Texas;",
                        "contributorRole": "ProjectMember"
                    },
                    {
                        "contributorName": "Experimental rock deformation/HPT-Lab (Utrecht University, The Netherlands)",
                        "contributorOrcid": "",
                        "contributorScopus": "",
                        "contributorAffiliation": "Utrecht University;",
                        "contributorRole": "HostingInstitution"
                    }
                ],
                "references": [
                    {
                        "referenceDoi": "10.1002/essoar.10503084.1",
                        "referenceHandle": "",
                        "referenceTitle": "Biemiller, J. B., Boulton, C., Wallace, L., Ellis, S., Little, T., Mizera, M., Niemeijer, A., & Lavier, L. L. (2020). Mechanical Implications of Creep and Partial Coupling on the World’s Fastest Slipping Low-angle Normal Fault in Southeastern Papua New Guinea. https://doi.org/10.1002/essoar.10503084.1\n",
                        "referenceType": "IsSupplementTo"
                    },
                    {
                        "referenceDoi": "10.1038/34157",
                        "referenceHandle": "",
                        "referenceTitle": "Marone, C. (1998). The effect of loading rate on static friction and the rate of fault healing during the earthquake cycle. Nature, 391(6662), 69–72. https://doi.org/10.1038/34157\n",
                        "referenceType": "References"
                    }
                ],
                "laboratories": [
                    "Experimental rock deformation/HPT-Lab (Utrecht University,  The Netherlands)"
                ],
                "materials": [
                    "chlorite",
                    "quartz",
                    "serpentine",
                    "saponite"
                ],
                "spatial": [],
                "locations": [
                    "outcrops of the Mai'iu Fault, Papoa New Guinea"
                ],
                "coveredPeriods": [],
                "collectionPeriods": [
                    {
                        "startDate": "2015-03-24",
                        "endDate": "2018-10-24"
                    }
                ],
                "maintainer": "",
                "downloads": [
                    {
                        "fileName": "Biemiller_et_al_2020_Data_description",
                        "downloadLink": "https://geo.public.data.uu.nl/vault-biemiller/Biemiller_et_al_2020%5B1591878065%5D/original/Biemiller_et_al_2020_Data_description.docx"
                    }
                ],
                "researchAspects": []
            },
            {
                "title": "Top-view and cross-section photographs from analogue experiments of strain partitioning around a rigid indenter performed in the Tectonic modelling laboratory (TecLab) at Utrecht University",
                "name": "edbe75e7fd83de8e78bebfef524f38ba",
                "portalLink": "http://localhost:5000/data-publication/edbe75e7fd83de8e78bebfef524f38ba",
                "doi": "10.24416/UU01-8TX6RL",
                "handle": "",
                "license": "Creative Commons Attribution 4.0 International Public License",
                "version": "",
                "source": "http://dx.doi.org/10.24416/UU01-8TX6RL",
                "publisher": "e2eaa4d7-e873-42c2-b449-7c1e1005bba3",
                "subdomain": [
                    "analogue modelling of geologic processes",
                    "rock and melt physics"
                ],
                "description": "This dataset contains original top-view and cross-section photographs of 12 crustal-scale analogue models. Top-view photographs were taken in regular time intervals from the beginning until the end of each experiment (for details see below). Cross-section photographs were taken at the end of each experiment. Therefore, top-view photographs provide means to track and analyse surface deformation through time and space and cross-sections allow to demonstrate overall vertical deformation of each model. \nThe data are grouped in 12 folders that contain the data for the individual models (model1 to model12). The numbering of the folders corresponds to the model numbers as described in Krstekanić et al. (2020, in prep.). Each folder contains two sub-folders named m#-cross-sections and m#-top-views where m stands for the model and # for the number of the model (i.e., the same number as the parent folder). The m#-cross-sections sub-folder contains one top-view photograph indicating the locations of the cross-sections as well as the pertinent cross-section photographs. A number in each cross-section photograph corresponds to the number next to the cross-section location in the top-view. The m#-top-views sub-folder contains all top-view photographs of that particular model, from its initial, undeformed state to the end of the experiment. All photographs are in .jpg format and their names are original generic names created by the camera software at the moment of acquisition.\n\nThe data is provided in 12 subfolders for 12 models. Detailed information about the files in these subfolders as well as information on how the data is processed is given in the explanatory file krstekanic-et-al-2020-data-documentation.docx. Contact person is Nemanja Krstekanić  - PhD Candidate - n.krstekanic@uu.nl - https://www.uu.nl/staff/nkrstekanic",
                "publicationDate": "2020-01-01",
                "citation": "Krstekanić, N., &amp; Willingshofer, E. (2020). <i>Top-view and cross-section photographs from analogue experiments of strain partitioning around a rigid indenter performed in the Tectonic modelling laboratory (TecLab) at Utrecht University</i> (Version 1.0) [Data set]. Utrecht University. https://doi.org/10.24416/UU01-8TX6RL",
                "creators": [
                    {
                        "authorName": "Krstekanić, Nemanja",
                        "authorOrcid": "0000-0002-2798-2003",
                        "authorScopus": "",
                        "authorAffiliation": "Utrecht University; University of Belgrade;"
                    },
                    {
                        "authorName": "Willingshofer, Ernst",
                        "authorOrcid": "0000-0002-9119-5557",
                        "authorScopus": "",
                        "authorAffiliation": "Utrecht University;"
                    }
                ],
                "contributors": [
                    {
                        "contributorName": "Krstekanić, Nemanja",
                        "contributorOrcid": "0000-0002-2798-2003",
                        "contributorScopus": "",
                        "contributorAffiliation": "Utrecht University; University of Belgrade;",
                        "contributorRole": "Researcher"
                    },
                    {
                        "contributorName": "Krstekanić, Nemanja",
                        "contributorOrcid": "0000-0002-2798-2003",
                        "contributorScopus": "",
                        "contributorAffiliation": "Utrecht University; University of Belgrade;",
                        "contributorRole": "ContactPerson"
                    },
                    {
                        "contributorName": "Willingshofer, Ernst",
                        "contributorOrcid": "0000-0002-9119-5557",
                        "contributorScopus": "",
                        "contributorAffiliation": "Utrecht University;",
                        "contributorRole": "Supervisor"
                    },
                    {
                        "contributorName": "Broerse, Taco",
                        "contributorOrcid": "0000-0002-3235-0844",
                        "contributorScopus": "",
                        "contributorAffiliation": "Utrecht University;",
                        "contributorRole": "Researcher"
                    },
                    {
                        "contributorName": "Matenco, Liviu",
                        "contributorOrcid": "0000-0001-7448-6929",
                        "contributorScopus": "",
                        "contributorAffiliation": "Utrecht University;",
                        "contributorRole": "Supervisor"
                    },
                    {
                        "contributorName": "Toljić, Marinko",
                        "contributorOrcid": "0000-0002-0231-9969",
                        "contributorScopus": "",
                        "contributorAffiliation": "University of Belgrade;",
                        "contributorRole": "Supervisor"
                    },
                    {
                        "contributorName": "Stojadinovic, Uros",
                        "contributorOrcid": "0000-0002-4420-2988",
                        "contributorScopus": "",
                        "contributorAffiliation": "University of Belgrade;",
                        "contributorRole": "Researcher"
                    },
                    {
                        "contributorName": "TecLab - Tectonic  Modelling Laboratory  (Utrecht University,  The Netherlands)",
                        "contributorOrcid": "",
                        "contributorScopus": "",
                        "contributorAffiliation": "Utrecht University;",
                        "contributorRole": "HostingInstitution"
                    }
                ],
                "references": [
                    {
                        "referenceDoi": "10.1016/0040-1951(91)90311-F",
                        "referenceHandle": "",
                        "referenceTitle": "Davy, Ph., & Cobbold, P. R. (1991). Experiments on shortening of a 4-layer model of the continental lithosphere. Tectonophysics, 188(1–2), 1–25. https://doi.org/10.1016/0040-1951(91)90311-f\n",
                        "referenceType": "References"
                    },
                    {
                        "referenceDoi": "10.1130/GSAB-48-1459",
                        "referenceHandle": "",
                        "referenceTitle": "HUBBERT, M. K. (1937). Theory of scale models as applied to the study of geologic structures. Geological Society of America Bulletin, 48(10), 1459–1520. https://doi.org/10.1130/gsab-48-1459\n",
                        "referenceType": "References"
                    },
                    {
                        "referenceDoi": "",
                        "referenceHandle": "",
                        "referenceTitle": "",
                        "referenceType": "References"
                    },
                    {
                        "referenceDoi": "http://dx.doi.org/10.5334/jors.bl",
                        "referenceHandle": "",
                        "referenceTitle": "",
                        "referenceType": "References"
                    },
                    {
                        "referenceDoi": "10.1016/0031-9201(86)90021-X",
                        "referenceHandle": "",
                        "referenceTitle": "Weijermars, R., & Schmeling, H. (1986). Scaling of Newtonian and non-Newtonian fluid dynamics without inertia for quantitative modelling of rock flow due to gravity (including the concept of rheological similarity). Physics of the Earth and Planetary Interiors, 43(4), 316–330. https://doi.org/10.1016/0031-9201(86)90021-x\n",
                        "referenceType": "References"
                    },
                    {
                        "referenceDoi": "10.5880/fidgeo.2018.072",
                        "referenceHandle": "",
                        "referenceTitle": "Willingshofer, E., Sokoutis, D., Beekman, F., Schönebeck, J.-M., Warsitzka, M., &amp; Rosenau, M. (2018). <i>Ring shear test data of feldspar sand and quartz sand used in the Tectonic Laboratory (TecLab) at Utrecht University for experimental Earth Science applications</i> [Data set]. GFZ Data Services. https://doi.org/10.5880/FIDGEO.2018.072",
                        "referenceType": "References"
                    }
                ],
                "laboratories": [
                    "TecLab - Tectonic Modelling Laboratory (Utrecht University, The Netherlands)"
                ],
                "materials": [
                    "quartz sand"
                ],
                "spatial": [],
                "locations": [
                    "Serbian Carpathians"
                ],
                "coveredPeriods": [],
                "collectionPeriods": [
                    {
                        "startDate": "2018-08-23",
                        "endDate": "2019-09-18"
                    }
                ],
                "maintainer": "",
                "downloads": [
                    {
                        "fileName": "krstekanic-et-al-2020-data-documentation",
                        "downloadLink": "https://geo.public.data.uu.nl/vault-srcstrain/krstekanic-et-al-2020%5B1603200702%5D/original/krstekanic-et-al-2020-data-documentation.docx"
                    }
                ],
                "researchAspects": [
                    "graben",
                    "fracture",
                    "wrench fault",
                    "thrust fault",
                    "normal fault",
                    "strike-slip fault",
                    "reverse fault",
                    "mountain",
                    "friction coefficient",
                    "strain"
                ]
            }
        ]
    }
}
```

</details>

#### /paleo
This endpoint gives access to all data-publications available that are marked as belonging to the paleomagnetism (sub)domain. 

##### Search all paleomagnetism data-publications [GET]
+ Parameters

    + rows (number, optional) - The number of results to return.
        + Default: `10`
    + start (number, optional) - The number to start results from. 
        + Default: `0`
    + query (text, optional) - Words to search for. 
        + Default: ``
    + authorName (text, optional) - Author names to search for. 
        + Default: ``
    + labName (text, optional) - Lab names to search for. 
        + Default: ``
    + title (text, optional) - Title to search for. 
        + Default: ``
    + tags (text, optional) - Tags to search for. 
        + Default: ``
    + hasDownloads (boolean, optional) - Filter results to only include results with download links.
        + Default: `false`

        
+ Response 200 (application/json)

<details>
  <summary>view response</summary>
  
```json
{
    "success": true,
    "message": "",
    "result": {
        "count": 13,
        "resultCount": 1,
        "results": [
            {
                "title": "Rock magnetic properties, IODP Site U1518, depth interval 250-400 mbsf",
                "name": "df388daa62328e12be86bee242d3e862",
                "portalLink": "http://localhost:5000/data-publication/df388daa62328e12be86bee242d3e862",
                "doi": "10.24416/UU01-XZSKHP",
                "handle": "",
                "license": "",
                "version": "",
                "source": "http://dx.doi.org/10.24416/UU01-XZSKHP",
                "publisher": "e2eaa4d7-e873-42c2-b449-7c1e1005bba3",
                "subdomain": [
                    "paleomagnetism"
                ],
                "description": "Rock magnetic properties for hemipelagic sediments sampled at Site U1518 of IODP Expedition 375 (depth interval: 250-400 mbsf). \n\nData and file information:\n ‘hysteresis_parameters_curated.txt’ summarizes composition specific parameters from hysteresis and backfield curves.\n‘suceptibility_remanence_curated.txt’ summarizes curational information for each sample, susceptibility and remanence data measured on 7cc sample cubes.\n\nData Curation\nDepth: CSF-A and CSF-B refer to core depth scales of International Ocean Discovery Program (see pdf file “IODP Depth Scales Terminology” on https://www.iodp.org/policies-and-guidelines)\nCSF: core depth below sea floor, in this study, top depth CSF-B is used as sample depth in meters below sea floor (mbsf).\nSection Type: R for Rotary Core Barrel\n\nSusceptibility and Remanence data\nKm is the mean susceptibility extracted from anisotropy of magnetic susceptibility datafiles. These data are presented in Greve et al., 2020, https://doi.org/10.1016/j.epsl.2020.116322\nNRM: Natural Remanent Magnetization in emu\nARM: Anhysteretic Remanent Magnetization in emu. \nSIRM: Saturation Isothermal Remanent Magnetization imparted at 1.2 T in emu. \n\nHysteresis parameters\nAll hysteresis parameters were determined following mass normalization and automated slope correction. \nMs: mass-normalized saturation magnetization in Am²/kg \nMr: mass-normalized saturation remanent magnetization in Am²/kg \nBc: coercivity in mT\nBcr: remanent coercivity field in mT\n\nContact person: A. Greve - Researcher - a.greve@uu.nl",
                "publicationDate": "2021-01-01",
                "citation": "Greve, A. (2021). Rock magnetic properties, IODP Site U1518, depth interval 250-400 mbsf. <i>Utrecht University</i>. https://doi.org/10.24416/UU01-XZSKHP",
                "creators": [
                    {
                        "authorName": "Greve, Annika",
                        "authorOrcid": "0000-0001-8670-8242",
                        "authorScopus": "",
                        "authorAffiliation": "Utrecht University;"
                    }
                ],
                "contributors": [
                    {
                        "contributorName": "Greve, Annika",
                        "contributorOrcid": "0000-0001-8670-8242",
                        "contributorScopus": "",
                        "contributorAffiliation": "Utrecht University;",
                        "contributorRole": "ProjectLeader"
                    },
                    {
                        "contributorName": "Kars, Myriam",
                        "contributorOrcid": "0000-0002-4984-1412",
                        "contributorScopus": "",
                        "contributorAffiliation": "Kochi University;",
                        "contributorRole": "DataCollector"
                    },
                    {
                        "contributorName": "Dekkers, Mark. J.",
                        "contributorOrcid": "0000-0002-4156-3841",
                        "contributorScopus": "",
                        "contributorAffiliation": "Utrecht University;",
                        "contributorRole": "Supervisor"
                    },
                    {
                        "contributorName": "Paleomagnetic Laboratory Fort Hoofddijk  (Utrecht University, The Netherlands)",
                        "contributorOrcid": "",
                        "contributorScopus": "",
                        "contributorAffiliation": "Utrecht University;",
                        "contributorRole": "HostingInstitution"
                    }
                ],
                "references": [],
                "laboratories": [
                    "Paleomagnetic Laboratory Fort Hoofddijk (Utrecht University, The Netherlands)"
                ],
                "materials": [
                    "siltstone",
                    "claystone"
                ],
                "spatial": [],
                "locations": [
                    "IODP Site U1518 (lat, long), NE New Zealand",
                    "IODP Site U1518, Hole U1518F (38°51.5694ʹS, 178°53.7619ʹE; 2626.1 mbsl)"
                ],
                "coveredPeriods": [],
                "collectionPeriods": [
                    {
                        "startDate": "2017-03-17",
                        "endDate": "2017-03-20"
                    }
                ],
                "maintainer": "",
                "downloads": [
                    {
                        "fileName": "Notes",
                        "downloadLink": "https://geo.public.data.uu.nl/vault-papaku-rockmagnetism/Greve_et_al_2021%5B1611243312%5D/original/Notes.docx"
                    }
                ],
                "researchAspects": []
            }
        ]
    }
}
```

</details>

#### /microscopy
This endpoint gives access to all data-publications available that are marked as belonging to the microscopy and tomography (sub)domain. 

##### Search all microscopy and tomography data-publications [GET]
+ Parameters

    + rows (number, optional) - The number of results to return.
        + Default: `10`
    + start (number, optional) - The number to start results from. 
        + Default: `0`
    + query (text, optional) - Words to search for. 
        + Default: ``
    + authorName (text, optional) - Author names to search for. 
        + Default: ``
    + labName (text, optional) - Lab names to search for. 
        + Default: ``
    + title (text, optional) - Title to search for. 
        + Default: ``
    + tags (text, optional) - Tags to search for. 
        + Default: ``
    + hasDownloads (boolean, optional) - Filter results to only include results with download links.
        + Default: `false`

        
+ Response 200 (application/json)

<details>
  <summary>view response</summary>
  
```json
{
    "success": true,
    "message": "",
    "result": {
        "count": 4,
        "resultCount": 2,
        "results": [
            {
                "title": "Original microstructural data of altered rocks and reconstructions using generative adversarial networks (GANs)",
                "name": "da27c86c28542af2cc6d931d0944ea4b",
                "portalLink": "http://localhost:5000/data-publication/da27c86c28542af2cc6d931d0944ea4b",
                "doi": "10.24416/UU01-ACSDR4",
                "handle": "",
                "license": "",
                "version": "",
                "source": "http://dx.doi.org/10.24416/UU01-ACSDR4",
                "publisher": "e2eaa4d7-e873-42c2-b449-7c1e1005bba3",
                "subdomain": [
                    "microscopy and tomography"
                ],
                "description": "We image two altered rock samples consisting of a meta-igneous and a serpentinite showing an isolated porous and fracture network, respectively. The rock samples are collected during previous visits to Swartberget, Norway in 2009 and Tønsberg, Norway in 2012. The objective is to employ a deep-learning-based model called generative adversarial network (GAN) to reconstruct statistically-equivalent microstructures. To evaluate the reconstruction accuracy, different polytope functions are calculated and compared in both original and reconstructed images. Compared with a common stochastic reconstruction method, our analysis shows that GAN is able to reconstruct more realistic microstructures. The data are organized into 12 folders: one containing original segmented images of rock samples, one with python codes used,  and the other 10 folder containing data and individual figures used to create figures in the main publication.",
                "publicationDate": "2022-01-01",
                "citation": "Amiri, H. (2022). <i>Original microstructural data of altered rocks and reconstructions using generative adversarial networks (GANs)</i> (Version 1.0) [Data set]. Utrecht University. https://doi.org/10.24416/UU01-ACSDR4",
                "creators": [
                    {
                        "authorName": "Amiri, Hamed",
                        "authorOrcid": "0000-0002-2981-1398",
                        "authorScopus": "",
                        "authorAffiliation": "Utrecht University;"
                    }
                ],
                "contributors": [
                    {
                        "contributorName": "Plümper, Oliver",
                        "contributorOrcid": "0000-0001-7405-1490",
                        "contributorScopus": "",
                        "contributorAffiliation": "Utrecht University;",
                        "contributorRole": "ProjectManager"
                    },
                    {
                        "contributorName": "Vasconcelos, Ivan",
                        "contributorOrcid": "0000-0001-7405-1490",
                        "contributorScopus": "",
                        "contributorAffiliation": "Utrecht University;",
                        "contributorRole": "Researcher"
                    },
                    {
                        "contributorName": "Jiao, Yang",
                        "contributorOrcid": "0000-0001-6501-8787",
                        "contributorScopus": "",
                        "contributorAffiliation": "Arizona state university;",
                        "contributorRole": "Researcher"
                    },
                    {
                        "contributorName": "Chen, Pei-En",
                        "contributorOrcid": "0000-0001-5107-6281",
                        "contributorScopus": "",
                        "contributorAffiliation": "Arizona state university;",
                        "contributorRole": "Researcher"
                    },
                    {
                        "contributorName": "Amiri, Hamed",
                        "contributorOrcid": "0000-0002-2981-1398",
                        "contributorScopus": "",
                        "contributorAffiliation": "Utrecht University;",
                        "contributorRole": "ContactPerson"
                    }
                ],
                "references": [],
                "laboratories": [
                    "Electron Microscopy Facilities (Utrecht University, The Netherlands)"
                ],
                "materials": [],
                "spatial": [],
                "locations": [],
                "coveredPeriods": [],
                "collectionPeriods": [
                    {
                        "startDate": "2020-04-01",
                        "endDate": "2022-02-25"
                    }
                ],
                "maintainer": "",
                "downloads": [
                    {
                        "fileName": "data-documentation",
                        "downloadLink": "https://geo.public.data.uu.nl/vault-gan/research-gan%5B1647946088%5D/original/data-documentation.pdf"
                    },
                    {
                        "fileName": "data",
                        "downloadLink": "https://geo.public.data.uu.nl/vault-gan/research-gan%5B1647946088%5D/original/data.zip"
                    }
                ],
                "researchAspects": []
            },
            {
                "title": "Microstructural data and microscopic stress measurements of natural mineral-hydration reactions",
                "name": "a06c82352950f16377190270eca462cb",
                "portalLink": "http://localhost:5000/data-publication/a06c82352950f16377190270eca462cb",
                "doi": "10.24416/UU01-XVJYBS",
                "handle": "",
                "license": "",
                "version": "",
                "source": "http://dx.doi.org/10.24416/UU01-XVJYBS",
                "publisher": "e2eaa4d7-e873-42c2-b449-7c1e1005bba3",
                "subdomain": [
                    "microscopy and tomography",
                    "rock and melt physics",
                    "analogue modelling of geologic processes"
                ],
                "description": "We investigated the microstructures of periclase-to-brucite hydration domains within marble from the Adamello contact aureole (Italy). The microstructure preserve high differential stresses within the calcite surrounding the hydration domains of up to 1.5 GPa. Samples were investigated using optical, scanning and transmission electron microscopy as well as high-angular resolution electron backscatter diffraction (HREBSD). Stress measurements obtained via HREBSD are compared to analytical solutions. The data is organised in 10 folders termed (Supplementary) Figure X, where X is the figure number in the primary publication and supplementary information. An additional folder contains Matlab scripts for the analytical solutions to the stress measurements. Detailed information about the files and methods used is given in a readme file.",
                "publicationDate": "2021-01-01",
                "citation": "Plümper, O. (2021). <i>Microstructural data and microscopic stress measurements of natural mineral-hydration reactions</i> (Version 1.0) [Data set]. Utrecht University. https://doi.org/10.24416/UU01-XVJYBS",
                "creators": [
                    {
                        "authorName": "Plümper, Oliver",
                        "authorOrcid": "0000-0001-9726-0885",
                        "authorScopus": "",
                        "authorAffiliation": "Utrecht University;"
                    }
                ],
                "contributors": [
                    {
                        "contributorName": "Wallis, David",
                        "contributorOrcid": "0000-0001-9212-3734",
                        "contributorScopus": "",
                        "contributorAffiliation": "University of Cambridge;",
                        "contributorRole": "Researcher"
                    },
                    {
                        "contributorName": "Moulas, Evangelos",
                        "contributorOrcid": "0000-0002-2783-4633",
                        "contributorScopus": "",
                        "contributorAffiliation": "Johannes Gutenberg Universität Mainz;",
                        "contributorRole": "Researcher"
                    },
                    {
                        "contributorName": "Schmalholz, Stefan M.",
                        "contributorOrcid": "0000-0003-4724-2181",
                        "contributorScopus": "",
                        "contributorAffiliation": "University of Lausanne;",
                        "contributorRole": "Researcher"
                    },
                    {
                        "contributorName": "Amiri , Hamed",
                        "contributorOrcid": "0000-0002-2981-1398",
                        "contributorScopus": "",
                        "contributorAffiliation": "Utrecht University;",
                        "contributorRole": "Researcher"
                    },
                    {
                        "contributorName": "Müller, Thomas",
                        "contributorOrcid": "0000-0002-1045-2110",
                        "contributorScopus": "",
                        "contributorAffiliation": "Georg-August-Universität Göttingen;",
                        "contributorRole": "Researcher"
                    },
                    {
                        "contributorName": "Plümper, Oliver",
                        "contributorOrcid": "0000-0001-9726-0885",
                        "contributorScopus": "",
                        "contributorAffiliation": "Utrecht University;",
                        "contributorRole": "ContactPerson"
                    }
                ],
                "references": [],
                "laboratories": [
                    "Electron Microscopy Facilities (Utrecht University, The Netherlands)"
                ],
                "materials": [],
                "spatial": [],
                "locations": [],
                "coveredPeriods": [],
                "collectionPeriods": [
                    {
                        "startDate": "2019-06-01",
                        "endDate": "2021-11-18"
                    }
                ],
                "maintainer": "",
                "downloads": [
                    {
                        "fileName": "readme",
                        "downloadLink": "https://geo.public.data.uu.nl/vault-hydration/research-hydration%5B1638268339%5D/original/readme.txt"
                    }
                ],
                "researchAspects": []
            }
        ]
    }
}
```

</details>

#### /geochemistry
This endpoint gives access to all data-publications available that are marked as belonging to the geochemistry (sub)domain. 

##### Search all geochemistry data-publications [GET]
+ Parameters

    + rows (number, optional) - The number of results to return.
        + Default: `10`
    + start (number, optional) - The number to start results from. 
        + Default: `0`
    + query (text, optional) - Words to search for. 
        + Default: ``
    + authorName (text, optional) - Author names to search for. 
        + Default: ``
    + labName (text, optional) - Lab names to search for. 
        + Default: ``
    + title (text, optional) - Title to search for. 
        + Default: ``
    + tags (text, optional) - Tags to search for. 
        + Default: ``
    + hasDownloads (boolean, optional) - Filter results to only include results with download links.
        + Default: `false`

        
+ Response 200 (application/json)

<details>
  <summary>view response</summary>
  
```json
{
  "success": true,
  "message": "",
  "result": {
    "count": 14,
    "resultCount": 10,
    "results": [
      {
        "title": "Frictional slip weakening and shear-enhanced crystallinity in simulated coal fault gouges at subseismic slip rates",
        "name": "2d7d3045ac3d3ee06edac2fa61a9cc26",
        "portalLink": "http:\/\/localhost\/data-publication\/2d7d3045ac3d3ee06edac2fa61a9cc26",
        "doi": "10.24416\/UU01-48I5DA",
        "handle": "",
        "license": "",
        "version": "",
        "source": "http:\/\/dx.doi.org\/10.24416\/UU01-48I5DA",
        "publisher": "e716d725-6846-4f70-b9cc-63900473d18a",
        "subdomain": [
          "rock and melt physics",
          "analogue modelling of geologic processes",
          "microscopy and tomography",
          "geochemistry"
        ],
        "description": "We report seven velocity stepping (VS) and one slide-hold-slide (SHS) friction experiments performed on simulated fault gouges prepared from bituminous coal, collected from the upper Silesian Basin of Poland. These experiments were performed at 25-45 MPa effective normal stress and 100 \u2103, employing sliding velocities of 0.1-100 \u03bcm\/s, using a conventional triaxial apparatus plus direct shear assembly. All samples showed marked slip weakening behaviour at shear displacements beyond ~1-2 mm, from a peak friction coefficient approaching ~0.5 to (near) steady state values of ~0.3, regardless of effective normal stress or whether vacuum dry flooded with distilled (DI) water at 15 MPa pore fluid pressure. Analysis of both unsheared and sheared samples by means of microstructural observation, micro-area X-ray diffraction (XRD) and Raman spectroscopy suggests that the marked slip weakening behaviour can be attributed to the development of R-, B- and Y- shear bands, with internal shear-enhanced coal crystallinity development. The SHS experiment performed showed a transient peak healing (restrengthening) effect that increased with the logarithm of hold time at a linearized rate of ~0.006. We also determined the rate-dependence of steady state friction for all VS samples using a full rate and state friction approach. This showed a transition from velocity strengthening to velocity weakening at slip velocities >1 \u03bcm\/s in the coal sample under vacuum dry conditions, but at >10 \u03bcm\/s in coal samples exposed to DI water at 15 MPa pore pressure. This may be controlled by competition between dilatant granular flow and compaction enhanced by presence of water. Together with our previous work on frictional properties of coal-shale mixtures, our results imply that the presence of a weak, coal-dominated patch on faults that cut or smear-out coal seams may promote unstable, seismogenic slip behaviour, though the importance of this in enhancing either induced or natural seismicity depends on local conditions. The data is provided in a folder with 10 subfolders for 10 experiments\/samples, including friction, XRD and Raman data. Detailed information about the files in these subfolders as well as information on how the data is processed is given in the explanatory file Fan-et-al-2020-Data-Description.pdf. Contact person is Dr. Jinfeng Liu - Sun Yat-Sen University- liujinf5@mail.sysu.edu.cn",
        "publicationDate": "2020-01-01",
        "citation": "Liu, J., &amp; Hunfeld, L. B. (2020). Frictional slip weakening and shear-enhanced crystallinity in simulated coal fault gouges at subseismic slip rates. <i>Utrecht University<\/i>. https:\/\/doi.org\/10.24416\/UU01-48I5DA",
        "creators": [
          {
            "authorName": "Liu, Jinfeng",
            "authorOrcid": "0000-0002-6444-9427",
            "authorScopus": "",
            "authorAffiliation": "School of Earth Sciences and Engineering, Sun Yat-Sen University; Guangdong Provincial Key Lab of Geodynamics and Geohazards, Sun Yat-Sen University, Zhuhai, China; Southern Marine Science and Engineering Guangdong Laboratory, Zhuhai, China;"
          },
          {
            "authorName": "Hunfeld, Luuk Bernd",
            "authorOrcid": "0000-0001-9250-414X",
            "authorScopus": "",
            "authorAffiliation": "Utrecht University;"
          }
        ],
        "contributors": [
          {
            "contributorName": "Fan, Caiyuan",
            "contributorOrcid": "0000-0002-0413-8467",
            "contributorScopus": "",
            "contributorAffiliation": "School of Earth Sciences and Engineering, Sun Yat-Sen University;",
            "contributorRole": "DataCollector"
          },
          {
            "contributorName": "Fan, Caiyuan",
            "contributorOrcid": "0000-0002-0413-8467",
            "contributorScopus": "",
            "contributorAffiliation": "School of Earth Sciences and Engineering, Sun Yat-Sen University;",
            "contributorRole": "Researcher"
          },
          {
            "contributorName": "Liu, Jinfeng",
            "contributorOrcid": "0000-0002-6444-9427",
            "contributorScopus": "",
            "contributorAffiliation": "School of Earth Sciences and Engineering, Sun Yat-Sen University; Guangdong Provincial Key Lab of Geodynamics and Geohazards, Sun Yat-Sen University, Zhuhai, China; Southern Marine Science and Engineering Guangdong Laboratory, Zhuhai, China;",
            "contributorRole": "DataCollector"
          },
          {
            "contributorName": "Liu, Jinfeng",
            "contributorOrcid": "0000-0002-6444-9427",
            "contributorScopus": "",
            "contributorAffiliation": "School of Earth Sciences and Engineering, Sun Yat-Sen University; Guangdong Provincial Key Lab of Geodynamics and Geohazards, Sun Yat-Sen University, Zhuhai, China; Southern Marine Science and Engineering Guangdong Laboratory, Zhuhai, China;",
            "contributorRole": "ProjectLeader"
          },
          {
            "contributorName": "Liu, Jinfeng",
            "contributorOrcid": "0000-0002-6444-9427",
            "contributorScopus": "",
            "contributorAffiliation": "School of Earth Sciences and Engineering, Sun Yat-Sen University; Guangdong Provincial Key Lab of Geodynamics and Geohazards, Sun Yat-Sen University, Zhuhai, China; Southern Marine Science and Engineering Guangdong Laboratory, Zhuhai, China;",
            "contributorRole": "Researcher"
          },
          {
            "contributorName": "Hunfeld, Luuk Bernd",
            "contributorOrcid": "0000-0001-9250-414X",
            "contributorScopus": "",
            "contributorAffiliation": "Utrecht University;",
            "contributorRole": "Researcher"
          },
          {
            "contributorName": "Spiers, Christopher James",
            "contributorOrcid": "0000-0002-3436-8941",
            "contributorScopus": "",
            "contributorAffiliation": "Utrecht University;",
            "contributorRole": "Researcher"
          },
          {
            "contributorName": "Experimental rock deformation\/HPT-Lab (Utrecht University, The Netherlands)",
            "contributorOrcid": "",
            "contributorScopus": "",
            "contributorAffiliation": "Utrecht University;",
            "contributorRole": "HostingInstitution"
          },
          {
            "contributorName": "of Earth Sciences and Engineering (Sun Yat-Sen University), School",
            "contributorOrcid": "",
            "contributorScopus": "",
            "contributorAffiliation": "Sun Yat-Sen University;",
            "contributorRole": "HostingInstitution"
          },
          {
            "contributorName": "Liu, Jinfeng",
            "contributorOrcid": "0000-0002-6444-9427",
            "contributorScopus": "",
            "contributorAffiliation": "School of Earth Sciences and Engineering, Sun Yat-Sen University; Guangdong Provincial Key Lab of Geodynamics and Geohazards, Sun Yat-Sen University, Zhuhai, China; Southern Marine Science and Engineering Guangdong Laboratory, Zhuhai, China;",
            "contributorRole": "ContactPerson"
          }
        ],
        "references": [
          {
            "referenceDoi": "10.1002\/2017JB014876",
            "referenceHandle": "",
            "referenceTitle": "Hunfeld, L. B., Niemeijer, A. R., & Spiers, C. J. (2017). Frictional Properties of Simulated Fault Gouges from the Seismogenic Groningen Gas Field Under In Situ P\u2013T \u2010Chemical Conditions. Journal of Geophysical Research: Solid Earth, 122(11), 8969\u20138989. Portico. https:\/\/doi.org\/10.1002\/2017jb014876\n",
            "referenceType": "References"
          }
        ],
        "laboratories": [
          "Experimental rock deformation\/HPT-Lab (Utrecht University, The Netherlands)"
        ],
        "materials": [
          "sedimentary rock",
          "coal",
          "fault rock",
          "fault gouge",
          "mudstone",
          "shale",
          "bituminous coal",
          "simulated fault gouge"
        ],
        "spatial": [],
        "locations": [
          "49.96072880335346, 15.384399613612231, 51.57806093491139, 21.53674470471674",
          "Coal samples were collected from Brzeszcze Mine (Seam 364), in the Upper Silesian Basin of Poland, Poland"
        ],
        "coveredPeriods": [],
        "collectionPeriods": [
          {
            "startDate": "2016-10-01",
            "endDate": "2020-01-31"
          }
        ],
        "maintainer": "",
        "downloads": [
          {
            "fileName": "Fan-et-al-2020-Data-Description",
            "downloadLink": "https:\/\/geo.public.data.uu.nl\/vault-coal-friction-data\/Liu_et_al_2020_Solid_Earth%5B1585573908%5D\/original\/Fan-et-al-2020-Data-Description.pdf"
          }
        ],
        "researchAspects": [
          "equipment",
          "x-ray diffractometer",
          "tectonic deformation structure",
          "tectonic fault"
        ]
      }
    ]
  }
}
```
</details>


#### /geoenergy
This endpoint gives access to all data-publications available that are marked as belonging to the Geo-energy test beds (sub)domain. 

##### Search all Geo-energy test beds data-publications [GET]
+ Parameters

    + rows (number, optional) - The number of results to return.
        + Default: `10`
    + start (number, optional) - The number to start results from. 
        + Default: `0`
    + query (text, optional) - Words to search for. 
        + Default: ``
    + authorName (text, optional) - Author names to search for. 
        + Default: ``
    + labName (text, optional) - Lab names to search for. 
        + Default: ``
    + title (text, optional) - Title to search for. 
        + Default: ``
    + tags (text, optional) - Tags to search for. 
        + Default: ``
    + hasDownloads (boolean, optional) - Filter results to only include results with download links.
        + Default: `false`

        
+ Response 200 (application/json)

<details>
  <summary>view response</summary>
  
```json
{
  "success": true,
  "message": "",
  "result": {
    "count": 3,
    "resultCount": 3,
    "results": [
      {
        "title": "In-situ Distributed Strain Sensing (DSS) data from the Zeerijp-3a well in the Groningen gas field, the Netherlands. Period 2015-2021",
        "name": "4e0c10ff66e4b66624ca0efdedcbcdfc",
        "portalLink": "http:\/\/localhost\/data-publication\/4e0c10ff66e4b66624ca0efdedcbcdfc",
        "doi": "10.24416\/UU01-82HIJ4",
        "handle": "",
        "license": "",
        "version": "",
        "source": "http:\/\/dx.doi.org\/10.24416\/UU01-82HIJ4",
        "publisher": "e716d725-6846-4f70-b9cc-63900473d18a",
        "subdomain": [
          "rock and melt physics",
          "analogue modelling of geologic processes",
          "geo-energy test beds"
        ],
        "description": "The Groningen gas field is the largest gas field in Europe. Gas production in this field has led to seismicity and surface subsidence, both believed to be caused by compaction of the underlying reservoir sandstone. In 2015, the field operator (Nederlandse Aardolie Maatschappij - NAM) installed a fibre optics cable in the Zeerijp-3a well, at a true vertical depth of about 2900 to 3200 m, i.e. in and around the gas reservoir. The Zeerijp-3a well is situated in the center of the field, where seismicity (<3.4 M) and subsidence (up to 35 cm) are both greatest. The fibre optics cable allows real-time, continuous, in-situ monitoring of compaction of the reservoir and the over- and underlying formations, through the Distributed Strain Sensing (DSS) technique. DSS data (strain-time-depth) obtained from October 2015 to December 2021 are provided by NAM, open access. The data were processed by NAM, as detailed in the report accompanying the data. Raw data are available on request. The data presented in this data publication were used and analyzed in the research report: \"Zeerijp-3 in-situ Strain Analysis - update 2021\" which is provided along with this data publication and is individually freely accessible at: \"https:\/\/nam-onderzoeksrapporten.data-app.nl\/reports\/download\/groningen\/en\/5c6ccc55-707d-49b6-bcc3-9fb02dc72a16\"",
        "publicationDate": "2022-01-01",
        "citation": "Kole, P. R., &amp; van Eijs, R. M. H. E. (2022). <i>In-situ Distributed Strain Sensing (DSS) data from the Zeerijp-3a well in the Groningen gas field, the Netherlands. Period 2015-2021<\/i> (Version 1.1) [Data set]. Utrecht University. https:\/\/doi.org\/10.24416\/UU01-82HIJ4",
        "creators": [
          {
            "authorName": "Kole, Pepijn R.",
            "authorOrcid": "",
            "authorScopus": "8986439400",
            "authorAffiliation": "Nederlandse Aardolie Maatschappij;"
          },
          {
            "authorName": "van Eijs, Rob M.H.E.",
            "authorOrcid": "",
            "authorScopus": "6507367503",
            "authorAffiliation": "Nederlandse Aardolie Maatschappij;"
          }
        ],
        "contributors": [],
        "references": [
          {
            "referenceDoi": "",
            "referenceHandle": "",
            "referenceTitle": "",
            "referenceType": "IsSupplementTo"
          }
        ],
        "laboratories": [],
        "materials": [
          "sedimentary rock",
          "sandstone",
          "wacke",
          "Slochteren sandstone"
        ],
        "spatial": [],
        "locations": [
          "Groningen gas field",
          "Zeerijp-3a well"
        ],
        "coveredPeriods": [],
        "collectionPeriods": [
          {
            "startDate": "2015-10-12",
            "endDate": "2021-12-31"
          }
        ],
        "maintainer": "",
        "downloads": [
          {
            "fileName": "readme",
            "downloadLink": "https:\/\/geo.public.data.uu.nl\/vault-nam-geological-model\/Publication_DSS_Zeerijp-2015-2021%5B1669890990%5D\/original\/readme.txt"
          },
          {
            "fileName": "2015-2021",
            "downloadLink": "https:\/\/geo.public.data.uu.nl\/vault-nam-geological-model\/Publication_DSS_Zeerijp-2015-2021%5B1669890990%5D\/original\/2015-2021.zip"
          }
        ],
        "researchAspects": [
          "distributed fibre optic sensing",
          "distributed strain sensing",
          "antropogenic setting",
          "Induced seismicity",
          "surface subsidence",
          "gas field"
        ]
      }
    ]
  }
}
```
</details>

#### /all
This endpoint gives access to all data-publications available that are marked as belonging to the rock physics (sub)domain. 

##### Search all data-publications [GET]
+ Parameters

    + rows (number, optional) - The number of results to return.
        + Default: `10`
    + start (number, optional) - The number to start results from. 
        + Default: `0`
    + query (text, optional) - Words to search for. 
        + Default: ``
    + subDomain (text, optional) - subDomain to filter on. 
        + Default: ``
    + authorName (text, optional) - Author names to search for. 
        + Default: ``
    + labName (text, optional) - Lab names to search for. 
        + Default: ``
    + title (text, optional) - Title to search for. 
        + Default: ``
    + tags (text, optional) - Tags to search for. 
        + Default: ``
    + hasDownloads (boolean, optional) - Filter results to only include results with download links.
        + Default: `false`

        
+ Response 200 (application/json)

<details>
  <summary>view response</summary>
  
```json
{
    "success": true,
    "message": "",
    "result": {
        "count": 137,
        "resultCount": 2,
        "results": [
            {
                "title": "Stress-cycling data uniaxial compaction of quartz sand in various chemical environments",
                "name": "25c6bbb8590ad766b48a08c83c028899",
                "portalLink": "http://localhost:5000/data-publication/25c6bbb8590ad766b48a08c83c028899",
                "doi": "10.24416/UU01-VM3Z6I",
                "handle": "",
                "license": "",
                "version": "",
                "source": "http://dx.doi.org/10.24416/UU01-VM3Z6I",
                "publisher": "e2eaa4d7-e873-42c2-b449-7c1e1005bba3",
                "subdomain": [
                    "rock and melt physics",
                    "analogue modelling of geologic processes"
                ],
                "description": "Decarbonisation of the energy system requires new uses of porous subsurface reservoirs, where hot porous reservoirs can be utilised as sustainable sources of heat and electricity, while depleted ones can be employed to temporary store energy or permanently store waste. However, fluid injection induces a poro-elastic response of the reservoir rock, as well as a chemical response that is not well understood. We conducted uniaxial stress-cycling experiments on quartz sand aggregates to investigate the effect of pore fluid chemistry on short-term compaction. Two of the tested environments, low-vacuum (dry) and n-decane, were devoid of water, and the other environments included distilled water and five aqueous solutions with dissolved HCl and NaOH in various concentrations, covering pH values in the range 1 to 14. In addition, we collected acoustic emission data and performed microstructural analyses to gain insight into the deformation mechanisms.\n\nThe data is provided in one folder for 26 experiments/samples. Detailed information about the files in these subfolders as well as information on how the data is processed is given in the explanatory file Schimmel-et-al_2020_data-description.docx. Contact person is Mariska Schimmel - PhD - m.t.w.schimmel@uu.nl / marischimmel@gmail.com",
                "publicationDate": "2020-01-01",
                "citation": "Schimmel, M. T. W. (2020). Stress-cycling data uniaxial compaction of quartz sand in various chemical environments. <i>Utrecht University</i>. https://doi.org/10.24416/UU01-VM3Z6I",
                "creators": [
                    {
                        "authorName": "Schimmel, Mariska T.W.",
                        "authorOrcid": "0000-0002-9854-0552",
                        "authorScopus": "",
                        "authorAffiliation": "Utrecht University;"
                    }
                ],
                "contributors": [
                    {
                        "contributorName": "Schimmel, Mariska T.W.",
                        "contributorOrcid": "0000-0002-9854-0552",
                        "contributorScopus": "",
                        "contributorAffiliation": "Utrecht University;",
                        "contributorRole": "DataCollector"
                    },
                    {
                        "contributorName": "Schimmel, Mariska T.W.",
                        "contributorOrcid": "0000-0002-9854-0552",
                        "contributorScopus": "",
                        "contributorAffiliation": "Utrecht University;",
                        "contributorRole": "ContactPerson"
                    },
                    {
                        "contributorName": "Hangx, Suzanne J.T.",
                        "contributorOrcid": "0000-0003-2253-3273",
                        "contributorScopus": "",
                        "contributorAffiliation": "Utrecht University;",
                        "contributorRole": "Supervisor"
                    },
                    {
                        "contributorName": "Spiers, Christopher James",
                        "contributorOrcid": "0000-0002-3436-8941",
                        "contributorScopus": "",
                        "contributorAffiliation": "Utrecht University;",
                        "contributorRole": "Supervisor"
                    },
                    {
                        "contributorName": "Experimental rock deformation/HPT-Lab (Utrecht University, The Netherlands)",
                        "contributorOrcid": "",
                        "contributorScopus": "",
                        "contributorAffiliation": "Utrecht University;",
                        "contributorRole": "HostingInstitution"
                    }
                ],
                "references": [
                    {
                        "referenceDoi": "10.1007/s00603-020-02267-0",
                        "referenceHandle": "",
                        "referenceTitle": "Schimmel, M. T. W., Hangx, S. J. T., & Spiers, C. J. (2020). Impact of Chemical Environment on Compaction Behaviour of Quartz Sands during Stress-Cycling. Rock Mechanics and Rock Engineering, 54(3), 981–1003. https://doi.org/10.1007/s00603-020-02267-0\n",
                        "referenceType": "IsSupplementTo"
                    },
                    {
                        "referenceDoi": "10.5880/fidgeo.2019.005",
                        "referenceHandle": "",
                        "referenceTitle": "Schimmel, M., Hangx, S., &amp; Spiers, C. (2019). <i>Compaction creep data uniaxial compaction of quartz sand in various chemical environments</i> [Data set]. GFZ Data Services. https://doi.org/10.5880/FIDGEO.2019.005",
                        "referenceType": "Continues"
                    }
                ],
                "laboratories": [
                    "Experimental rock deformation/HPT-Lab (Utrecht University,  The Netherlands)"
                ],
                "materials": [
                    "sand",
                    "quartz"
                ],
                "spatial": [],
                "locations": [
                    "Heksenberg Formation at the Beaujean Quarry in Heerlen, the Netherlands"
                ],
                "coveredPeriods": [],
                "collectionPeriods": [],
                "maintainer": "",
                "downloads": [
                    {
                        "fileName": "Schimmel_et_al_2020_Data_description",
                        "downloadLink": "https://geo.public.data.uu.nl/vault-sand-compaction-chemistry-effects/Schimmel_et_al_2020_Cyclic_compaction_sand%5B1599588494%5D/original/Schimmel_et_al_2020_Data_description.docx"
                    }
                ],
                "researchAspects": [
                    "fluid-rock interaction",
                    "stress corrosion cracking"
                ]
            },
            {
                "title": "Rotary Shear Experiments on Glass Bead Aggregates",
                "name": "f7c0cf40a195dc0df5e2494e68b5a96a",
                "portalLink": "http://localhost:5000/data-publication/f7c0cf40a195dc0df5e2494e68b5a96a",
                "doi": "10.24416/UU01-HPZZ2M",
                "handle": "",
                "license": "",
                "version": "",
                "source": "http://dx.doi.org/10.24416/UU01-HPZZ2M",
                "publisher": "e2eaa4d7-e873-42c2-b449-7c1e1005bba3",
                "subdomain": [
                    "rock and melt physics"
                ],
                "description": "Constant sliding velocity (i.e. rate of rotation) friction experiments on mm-thick layers of glass beads, under room temperature and humidity conditions.\n\nStick-slip in sheared granular aggregates is considered to be an analog for the intermittent deformation of the earth’s lithosphere via earthquakes. Stick-slip can be regular, i.e. periodic and of consistent amplitude, or irregular, i.e. aperiodic and of varying amplitude. In the context of seismology, the former behavior resembles the Characteristic Earthquake Model, whereas the latter is equivalent to the Gutenberg-Richter Model.\n\nThis publication contains mechanical and acoustic emission (AE) data from sliding experiments on aggregates of soda-lime glass beads. By tuning certain parameters of the experiment, our system is able to produce either regular or irregular stick-slip. Mechanical data, namely forces (axial and lateral) and displacements (ditto), have been sampled in continuous mode (also known as “streaming mode” or “First In, First Out; FIFO”), whereas AE waveforms have been sampled in block mode (also known as “trigger mode”). A single, multichannel data acquisition system was used to acquire all of the data, ensuring a common time base.\n\nThe experiments have been performed under normal stress values of 4 or 8 MPa and the samples have been sheared to large displacements; many times larger than their initial thickness of approximately 4.5 mm. Therefore, this data set fills a gap between most experiments reported in the physics literature (low normal stress and large shear displacement) and the geoscience literature (high normal stress and small shear displacement).\n\nThe data is provided in 12 subfolders for 12 experiments/samples. Detailed information about the files in these subfolders as well as information on how the data is processed is given in the explanatory file Korkolis_et_al_2021_Data_Description.docx. Contact person is Evangelos Korkolis - Researcher - ekorko@gmail.com",
                "publicationDate": "2021-01-01",
                "citation": "Korkolis, E. (2021). Rotary Shear Experiments on Glass Bead Aggregates. <i>Utrecht University</i>. https://doi.org/10.24416/UU01-HPZZ2M",
                "creators": [
                    {
                        "authorName": "Korkolis, Evangelos",
                        "authorOrcid": "0000-0002-6485-1395",
                        "authorScopus": "",
                        "authorAffiliation": "Utrecht University;"
                    }
                ],
                "contributors": [
                    {
                        "contributorName": "Korkolis, Evangelos",
                        "contributorOrcid": "0000-0002-6485-1395",
                        "contributorScopus": "",
                        "contributorAffiliation": "Utrecht University;",
                        "contributorRole": "DataCollector"
                    },
                    {
                        "contributorName": "Korkolis, Evangelos",
                        "contributorOrcid": "0000-0002-6485-1395",
                        "contributorScopus": "",
                        "contributorAffiliation": "Utrecht University;",
                        "contributorRole": "ContactPerson"
                    },
                    {
                        "contributorName": "Niemeijer, Andre Rik",
                        "contributorOrcid": "0000-0003-3983-9308",
                        "contributorScopus": "",
                        "contributorAffiliation": "Utrecht University;",
                        "contributorRole": "Supervisor"
                    },
                    {
                        "contributorName": "Paulssen, Hanneke",
                        "contributorOrcid": "0000-0003-2799-7288",
                        "contributorScopus": "",
                        "contributorAffiliation": "Utrecht University;",
                        "contributorRole": "Researcher"
                    },
                    {
                        "contributorName": "Trampert, Jeannot A.",
                        "contributorOrcid": "0000-0002-5868-9491",
                        "contributorScopus": "",
                        "contributorAffiliation": "Utrecht University;",
                        "contributorRole": "Supervisor"
                    },
                    {
                        "contributorName": "Experimental rock deformation/HPT-Lab (Utrecht University, The Netherlands)",
                        "contributorOrcid": "",
                        "contributorScopus": "",
                        "contributorAffiliation": "Utrecht University;",
                        "contributorRole": "HostingInstitution"
                    }
                ],
                "references": [
                    {
                        "referenceDoi": "10.1002/essoar.10505896.1",
                        "referenceHandle": "",
                        "referenceTitle": "Korkolis, E., Niemeijer, A., Paulssen, H., & Trampert, J. (2021). A Laboratory Perspective on the Gutenberg-Richter and Characteristic Earthquake Models. https://doi.org/10.1002/essoar.10505896.1\n",
                        "referenceType": "IsSupplementTo"
                    },
                    {
                        "referenceDoi": "",
                        "referenceHandle": "",
                        "referenceTitle": "",
                        "referenceType": "References"
                    }
                ],
                "laboratories": [
                    "Experimental rock deformation/HPT-Lab (Utrecht University,  The Netherlands)"
                ],
                "materials": [
                    "glass microspheres"
                ],
                "spatial": [],
                "locations": [],
                "coveredPeriods": [],
                "collectionPeriods": [
                    {
                        "startDate": "2017-06-21",
                        "endDate": "2018-03-14"
                    }
                ],
                "maintainer": "",
                "downloads": [
                    {
                        "fileName": "Korkolis_et_al_2021_Data_documentation",
                        "downloadLink": "https://geo.public.data.uu.nl/vault-rotary-shear-experiments-on-glass-bead-aggregates/Korkolis_2021%5B1613641324%5D/original/Korkolis_et_al_2021_Data_documentation.docx"
                    }
                ],
                "researchAspects": []
            }
        ]
    }
}
```

</details>




#### /facilities
This endpoint gives access to all facilities and the equipment pieces on site.  

##### Search all facilities including equipment [GET]
+ Parameters

    + rows (number, optional) - The number of results to return.
        + Default: `10`
    + start (number, optional) - The number to start results from. 
        + Default: `0`
    + facilityQuery (text, optional) - Search query filtering using facility information. 
        + Default: ``
    + equipmentQuery (text, optional) - Search query filtering using equipment information. 
        + Default: ``
    + boundingBox 	(minx,miny,maxx,maxy) decimals, optional - Bounding box geographically filtering the results. If provided the bounding box must be valid. bounds: (-180, -90, 180, 90). 
        + Default: ``

        
+ Response 200 (application/json)

<details>
  <summary>view response</summary>
  
```json
{
  "success": true,
  "message": "",
  "result": {
    "count": 66,
    "resultCount": 10,
    "results": [
      {
        "name": "EM Centre",
        "description": "A full multi-scale workflow of innovative scanning electron microscopy (SEM) and transmission electron microscopy (TEM) techniques, as well as an X-ray microscopy (XRM) system, are used to understand the micro-physical basis of Earth-material properties. These instruments come together in the Utrecht University Electron Microscopy Centre and the Multi-scale Imaging and Tomography Facility (MINT) as part of EPOS-NL and NEMI.\r\n\r\nWe use electron backscattered diffraction (EBSD) in the SEM to map the crystal orientations and textures of geological materials and ice (cryo-EBSD); cathodoluminescence to probe defect structures and chemistry of a wide range of microstructures; and focused ion beam (FIB)-SEM tomography to study materials in three-dimensions, for example, pore or fracture networks.\r\n\r\nAutomated mineralogy mapping using energy-dispersive spectroscopy (EDS) and wavelength-dispersive spectroscopy analysis (WDS) in the microprobe allow us to determine mineral compositions as well as the reconstruction of bulk rock compositions. Via FIB-SEM nanomanipulation we obtain electron transparent foils for TEM analysis to study structure, composition and processes in Earth materials down to the atomic scale.\r\n\r\nWe also use innovative computational approaches such as machine learning to extract quantitative information from two- and three-dimensional microstructural datasets. For this, we have several high-powered computer workstations.\r\n\r\nScanning Electron Microscopes:\r\n- FEI (now Thermo Fisher) Helios G3 Nanolab FIB-SEM with EDS, EBSD, cryo-stage, panchromatic and wavelength filtered CL\r\n- Zeiss Gemini 450 variable pressure SEM with EDS, EBSD, cryo-stage, panchromatic and wavelength-filtered CL\r\n- Zeiss EVO 15 environmental SEM with Peltier cooling stage, EDS and automated mineralogy\r\n- JEOL Neoscope II JCM-6000 table-top SEM with EDS\r\n\r\nTransmission Electron Microscopes:\r\n- Thermo Fisher Spectra 300 monochromated, double-aberration corrected (S)TEM with high-sensitivity EDS and electron energy loss spectroscopy (EELS)\r\n- Thermo Fisher Talos F200X: 200 kV (S)TEM with HAADF, EDS and electron tomography\r\n- Several additional TEMs including cryo-systems\r\n\r\nMicroprobe:\r\n- JEOL JXA-8530F Hyperprobe Field Emission Electron probe microanalyser, equipped with 5 WDS spectrometers, SDD ED system, CL system (panchromatic imaging and xCLent hyperspectral CL).\r\n\r\nX-ray microscopy and tomography:\r\n- Zeiss Xradia 610 Versa high-resolution X-ray tomography microscope system equipped with a 160kV high-energy, high-power microfocus X-ray source, several high-contrast detectors and a large flat panel detector as well as in situ experimental capabilities.\r\n\r\nAdditional specialist equipment:\r\n- Atomic Force Microscope (Bruker MultiMode 3)\r\n- Several workstations for image analysis including GPU clusters\r\n- Several ion beam polishing systems\r\n\r\nProcessing and acquisition software available at the facility:\r\n- Avizo\r\n- Aztec\r\n- Esprit\r\n- Zen\r\n- Atlas\r\n- Velox\r\n- GMS3\r\n- STEMx\r\n- Donovan\r\n- Zeiss Reconstructor",
        "descriptionHtml": "<p>A full multi-scale workflow of innovative scanning electron microscopy (SEM) and transmission electron microscopy (TEM) techniques, as well as an X-ray microscopy (XRM) system, are used to understand the micro-physical basis of Earth-material properties. These instruments come together in the Utrecht University Electron Microscopy Centre and the Multi-scale Imaging and Tomography Facility (MINT) as part of EPOS-NL and NEMI.</p>\n<p>We use electron backscattered diffraction (EBSD) in the SEM to map the crystal orientations and textures of geological materials and ice (cryo-EBSD); cathodoluminescence to probe defect structures and chemistry of a wide range of microstructures; and focused ion beam (FIB)-SEM tomography to study materials in three-dimensions, for example, pore or fracture networks.</p>\n<p>Automated mineralogy mapping using energy-dispersive spectroscopy (EDS) and wavelength-dispersive spectroscopy analysis (WDS) in the microprobe allow us to determine mineral compositions as well as the reconstruction of bulk rock compositions. Via FIB-SEM nanomanipulation we obtain electron transparent foils for TEM analysis to study structure, composition and processes in Earth materials down to the atomic scale.</p>\n<p>We also use innovative computational approaches such as machine learning to extract quantitative information from two- and three-dimensional microstructural datasets. For this, we have several high-powered computer workstations.</p>\n<p>Scanning Electron Microscopes:</p>\n<ul>\n<li>FEI (now Thermo Fisher) Helios G3 Nanolab FIB-SEM with EDS, EBSD, cryo-stage, panchromatic and wavelength filtered CL</li>\n<li>Zeiss Gemini 450 variable pressure SEM with EDS, EBSD, cryo-stage, panchromatic and wavelength-filtered CL</li>\n<li>Zeiss EVO 15 environmental SEM with Peltier cooling stage, EDS and automated mineralogy</li>\n<li>JEOL Neoscope II JCM-6000 table-top SEM with EDS</li>\n</ul>\n<p>Transmission Electron Microscopes:</p>\n<ul>\n<li>Thermo Fisher Spectra 300 monochromated, double-aberration corrected (S)TEM with high-sensitivity EDS and electron energy loss spectroscopy (EELS)</li>\n<li>Thermo Fisher Talos F200X: 200 kV (S)TEM with HAADF, EDS and electron tomography</li>\n<li>Several additional TEMs including cryo-systems</li>\n</ul>\n<p>Microprobe:</p>\n<ul>\n<li>JEOL JXA-8530F Hyperprobe Field Emission Electron probe microanalyser, equipped with 5 WDS spectrometers, SDD ED system, CL system (panchromatic imaging and xCLent hyperspectral CL).</li>\n</ul>\n<p>X-ray microscopy and tomography:</p>\n<ul>\n<li>Zeiss Xradia 610 Versa high-resolution X-ray tomography microscope system equipped with a 160kV high-energy, high-power microfocus X-ray source, several high-contrast detectors and a large flat panel detector as well as in situ experimental capabilities.</li>\n</ul>\n<p>Additional specialist equipment:</p>\n<ul>\n<li>Atomic Force Microscope (Bruker MultiMode 3)</li>\n<li>Several workstations for image analysis including GPU clusters</li>\n<li>Several ion beam polishing systems</li>\n</ul>\n<p>Processing and acquisition software available at the facility:</p>\n<ul>\n<li>Avizo</li>\n<li>Aztec</li>\n<li>Esprit</li>\n<li>Zen</li>\n<li>Atlas</li>\n<li>Velox</li>\n<li>GMS3</li>\n<li>STEMx</li>\n<li>Donovan</li>\n<li>Zeiss Reconstructor</li>\n</ul>\n",
        "domain": "Microscopy and tomography",
        "latitude": "52.086507941561706",
        "longitude": "5.175506283838553",
        "altitude": "",
        "portalLink": "http://localhost:8000/lab/c4ca4238a0b923820dcc509a6f75849b",
        "organization": "Universiteit Utrecht (UU)",
        "equipment": [
          {
            "title": "EVO 15 (SEM)",
            "description": "Environmental SEM with Peltier cooling stage, 2x Bruker EDS and automated mineralogy.",
            "descriptionHtml": "<p>Environmental SEM with Peltier cooling stage, 2x Bruker EDS and automated mineralogy.</p>\n",
            "domain": "Microscopy and tomography",
            "category": "Permanent",
            "type": "Electron Microscopy",
            "group": "Scanning Electron Microscope (SEM)",
            "brand": "ZEISS"
          },
          {
            "title": "Neoscope II JCM-6000 table-top",
            "description": "JEOL Neoscope II JCM-6000 table-top SEM with EDS",
            "descriptionHtml": "<p>JEOL Neoscope II JCM-6000 table-top SEM with EDS</p>\n",
            "domain": "Microscopy and tomography",
            "category": "Permanent",
            "type": "Electron Microscopy",
            "group": "Scanning Electron Microscope (SEM)",
            "brand": "JEOL"
          },
          {
            "title": "MultiMode 3",
            "description": "",
            "descriptionHtml": "",
            "domain": "Microscopy and tomography",
            "category": "Permanent",
            "type": "Atomic Force Microscopy",
            "group": "Atomic Force Microscope (AFM)",
            "brand": "Bruker"
          },
          {
            "title": "Xradia 610 Versa (μ-CT)",
            "description": "High-resolution X-ray tomography microscope system equipped with a 160kV high-energy, high-power microfocus X-ray source, several high-contrast detectors and a large flat panel detector as well as in situ experimental capabilities.",
            "descriptionHtml": "<p>High-resolution X-ray tomography microscope system equipped with a 160kV high-energy, high-power microfocus X-ray source, several high-contrast detectors and a large flat panel detector as well as in situ experimental capabilities.</p>\n",
            "domain": "Microscopy and tomography",
            "category": "Permanent",
            "type": "X-Ray Tomography",
            "group": "X-ray CT (Computed Tomography)",
            "brand": "ZEISS"
          },
          {
            "title": "Helios Nanolab G3 (FIB-SEM)",
            "description": "FIB-SEM with Cryostage. Nordlys EBSD, Oxford xxx EDS, Gatan CL.",
            "descriptionHtml": "<p>FIB-SEM with Cryostage. Nordlys EBSD, Oxford xxx EDS, Gatan CL.</p>\n",
            "domain": "Microscopy and tomography",
            "category": "Permanent",
            "type": "Electron Microscopy",
            "group": "Focused Ion Beam - Scanning Electron Microscope (FIB-SEM)",
            "brand": "ThermoFisher Scientific"
          },
          {
            "title": "Spectra 300 (STEM)",
            "description": "30-300 kV (S)TEM. Double aberration corrected microscope with a variable acceleration voltage (30, 80, 200 and 300 kV), enabling high-resolution imaging up to 50 pm both in TEM and STEM imaging mode. Equipped with EDX spectrometry for chemical mapping, and ultra-high-resolution electron energy loss spectrometry (UHR-EELS) enabled by its double monochromator and Gatan Continuum filter. It also has a direct-direction Gatan K3 IS camera allowing imaging of soft and beam-sensitive materials.",
            "descriptionHtml": "<p>30-300 kV (S)TEM. Double aberration corrected microscope with a variable acceleration voltage (30, 80, 200 and 300 kV), enabling high-resolution imaging up to 50 pm both in TEM and STEM imaging mode. Equipped with EDX spectrometry for chemical mapping, and ultra-high-resolution electron energy loss spectrometry (UHR-EELS) enabled by its double monochromator and Gatan Continuum filter. It also has a direct-direction Gatan K3 IS camera allowing imaging of soft and beam-sensitive materials.</p>\n",
            "domain": "Microscopy and tomography",
            "category": "Permanent",
            "type": "Electron Microscopy",
            "group": "Transmission Electron Microscope (TEM)",
            "brand": "ThermoFisher Scientific"
          },
          {
            "title": "Talos F200X (STEM)",
            "description": "200 kV (S)TEM. High-brightness X-FEG electron gun, high-resolution imaging up tot 1.1 Å, electron diffraction, electron tomography, and high-sensitivity 2D EDX chemical mapping (Super-X).",
            "descriptionHtml": "<p>200 kV (S)TEM. High-brightness X-FEG electron gun, high-resolution imaging up tot 1.1 Å, electron diffraction, electron tomography, and high-sensitivity 2D EDX chemical mapping (Super-X).</p>\n",
            "domain": "Microscopy and tomography",
            "category": "Permanent",
            "type": "Electron Microscopy",
            "group": "Transmission Electron Microscope (TEM)",
            "brand": "ThermoFisher Scientific"
          },
          {
            "title": "JXA-8530F Hyperprobe (EPMA)",
            "description": "Field Emission Electron probe microanalyser, equipped with 5 WDS spectrometers, SDD ED system, CL system (panchromatic imaging and xCLent hyperspectral CL).",
            "descriptionHtml": "<p>Field Emission Electron probe microanalyser, equipped with 5 WDS spectrometers, SDD ED system, CL system (panchromatic imaging and xCLent hyperspectral CL).</p>\n",
            "domain": "Microscopy and tomography",
            "category": "Permanent",
            "type": "Electron Probe Micro Analyzer",
            "group": "Electron Probe Micro Analyzer (EPMA)",
            "brand": "JEOL"
          },
          {
            "title": "Gemini 450 (SEM)",
            "description": "High-end SEM with low vacuum capabilities. Symmetry EBSD detector, Oxford xxx EDS, Delmic CL, Quorum Cryostage.",
            "descriptionHtml": "<p>High-end SEM with low vacuum capabilities. Symmetry EBSD detector, Oxford xxx EDS, Delmic CL, Quorum Cryostage.</p>\n",
            "domain": "Microscopy and tomography",
            "category": "Permanent",
            "type": "Electron Microscopy",
            "group": "Scanning Electron Microscope (SEM)",
            "brand": "ZEISS"
          }
        ]
      }
    ]
  }
}
```

</details>
