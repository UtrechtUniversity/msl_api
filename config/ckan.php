<?php

return [
    'ckan_api_url'=> env('CKAN_API_URL', ''),
    'ckan_api_token'=> env('CKAN_API_TOKEN', ''),
    'ckan_root_url'=> env('CKAN_ROOT_URL', ''),
    'facets' => [
        'data-publications' => [
            "msl_subdomain" => "Sub domain",
            "msl_has_material" => "Material",
            "msl_has_material_original" => "Material",
            "msl_has_porefluid" => "Pore fluid",
            "msl_has_porefluid_original" => "Pore fluid",
            "msl_has_rockphysic" => "Rock and melt physics",
            "msl_has_rockphysic_original" => "Rock and melt physics",
            "msl_has_analogue" => "Analogue modelling of geological processes",
            "msl_has_analogue_original" => "Analogue modelling of geological processes",
            "msl_has_geologicalage" => "Geological age",
            "msl_has_geologicalage_original" => "Geological age",
            "msl_has_geologicalsetting" => "Geological setting",
            "msl_has_geologicalsetting_original" => "Geological setting",
            "msl_has_paleomagnetism" => "Paleomagnetism",
            "msl_has_paleomagnetism_original" => "Paleomagnetism",
            "msl_has_geochemistry" => "Geochemistry",
            "msl_has_geochemistry_original" => "Geochemistry",
            "msl_has_microscopy" => "Microscopy and tomography",
            "msl_has_microscopy_original" => "Microscopy and tomography",
            "msl_has_subsurface" => "(sub)surface utilization setting",
            "msl_has_subsurface_original" => "(sub)surface utilization setting",
            "msl_has_geoenergy" => "Geo-energy test beds",
            "msl_has_geoenergy_original" => "Geo-energy test beds",
            "msl_enriched_keyword_label" => "Enriched keyword label",
            "msl_enriched_keyword_uri" => "Enriched keyword uri",
            "msl_original_keyword_label" => "Original keyword label",
            "msl_original_keyword_uri" => "Original keyword uri",
            "msl_lab_name" =>  "Lab name",
            "msl_lab_id" => "Lab identifier",
            "msl_has_lab" =>  "Research institute",
            "organization" =>  "Organization",
            "msl_has_organization" => "Data repository"
        ],
        "laboratories" => [
            "msl_domain_name" => "Scientific domain",
            "msl_address_country_name" => "Country",
            "msl_organization_name" => "Organization"
        ],
        "equipment" => [
            "msl_original_keyword_label" => "Original keyword label",
            "msl_original_keyword_uri" => "Original keyword uri",
            "msl_organization_name" => "Organization"
        ]
    ]
];
