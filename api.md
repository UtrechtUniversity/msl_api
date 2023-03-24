# EPOS MSL API
The EPOS MSL API offers access to data available within our CKAN portal. This document describes the API per available endpoint.
A Postman collection file is available [here](../master/MSL%20API.postman_collection.json).

## Available resources
The API offers 5 domain specific endpoints and 1 endpoint offering access to all data-publications available. All data is open accessible, no authorization is required.
+ [rock_physics](#rock_physics)
+ [analogue](#analogue)
+ [paleo](#paleo)
+ [microscopy](#microscopy)
+ [geochemistry](#geochemistry)
+ [all](#all)

## Base url

The base url for the API:

```
https://epos-msl.uu.nl/webservice/api
```

# /rock_physics
This endpoint gives access to all data-publications available that are marked as belonging to the rock physics (sub)domain. 

## Search all rock physics data-publications [GET]
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
        + Default: `true`

        
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

# /analogue
This endpoint gives access to all data-publications available that are marked as belonging to the rock physics (sub)domain. 

## Search all analogue modelling data-publications [GET]
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
        + Default: `true`

        
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

# /paleo
This endpoint gives access to all data-publications available that are marked as belonging to the rock physics (sub)domain. 

## Search all paleomagnetism data-publications [GET]
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
        + Default: `true`

        
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

# /microscopy
This endpoint gives access to all data-publications available that are marked as belonging to the rock physics (sub)domain. 

## Search all microscopy and tomography data-publications [GET]
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
        + Default: `true`

        
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

# /geochemistry
This endpoint gives access to all data-publications available that are marked as belonging to the rock physics (sub)domain. 

## Search all geochemistry data-publications [GET]
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
        + Default: `true`

        
+ Response 200 (application/json)

<details>
  <summary>view response</summary>
  
```json
{
    "success": true,
    "message": "",
    "result": {
        "count": 0,
        "resultCount": 0,
        "results": []
    }
}
```

</details>

# /all
This endpoint gives access to all data-publications available that are marked as belonging to the rock physics (sub)domain. 

## Search all data-publications [GET]
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
        + Default: `true`

        
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
