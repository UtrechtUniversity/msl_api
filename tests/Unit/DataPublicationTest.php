<?php

namespace Tests\Unit;

use App\Models\Ckan\Affiliation;
use App\Models\Ckan\AlternateIdentifier;
use App\Models\Ckan\Contributor;
use App\Models\Ckan\Creator;
use App\Models\Ckan\DataPublication;
use App\Models\Ckan\Date;
use App\Models\Ckan\EnrichedKeyword;
use App\Models\Ckan\File;
use App\Models\Ckan\FundingReference;
use App\Models\Ckan\NameIdentifier;
use App\Models\Ckan\OriginalKeyword;
use App\Models\Ckan\RelatedIdentifier;
use App\Models\Ckan\Right;
use App\Models\Ckan\Tag;
use PHPUnit\Framework\TestCase;

class DataPublicationTest extends TestCase
{
    public function test_from_ckan_array(): void
    {
        $ckanResponse = file_get_contents('./tests/MockData/CkanResponses/package_search_single_full_datapublication.txt');
        $ckanResponse = json_decode($ckanResponse, true);

        $dataPublicationArray = $ckanResponse['result']['results'][0];

        $dataPublication = DataPublication::fromCkanArray($dataPublicationArray);


        $this->assertEquals("Micro Computational Tomography, Acoustic Emission and rock temperature data from frost weathering tests on Dachstein Limestone", $dataPublication->title);
        $this->assertEquals("Micro Computational Tomography, <span data-uris='[\"https://epos-msl.uu.nl/voc/rockphysics/1.3/measured_property-acoustic_emission_ae\", \"https://epos-msl.uu.nl/voc/analoguemodelling/1.3/measured_property-acoustic_emission_ae\"]'>Acoustic Emission</span> and rock temperature data from frost weathering tests on Dachstein <span data-uris='[\"https://epos-msl.uu.nl/voc/materials/1.3/sedimentary_rock-limestone\"]'>Limestone</span>", $dataPublication->msl_title_annotated);
        $this->assertEquals('data-publication', $dataPublication->type);
        $this->assertEquals('https://public.yoda.uu.nl/geo/UU01/Q5K96Z.html', $dataPublication->msl_source);
        $this->assertEquals('a6434e5f71718999519d775c8239c8a3', $dataPublication->name);
        $this->assertEquals('Research Data', $dataPublication->msl_resource_type);
        $this->assertEquals('Dataset', $dataPublication->msl_resource_type_general);
        $this->assertFalse($dataPublication->private);
        $this->assertEquals('1d22b8b4-b362-4d37-97ed-4886cdc465b1', $dataPublication->owner_org);
        $this->assertEquals("We tested the efficacy of frequent diurnal freeze-thaw cycles (FT-1) and sustained freezing cycles (FT-2) on low-porosity Dachstein limestone (0.1 % porosity). For FT-1, we exposed three rock samples with different saturation regimes (30%, 70%, 100%) to 20 freeze-thaw cycles between 10 and -10Â°C while monitoring rock temperature and acoustic emission (AE). We scanned the rock samples using micro computational tomography (CT) before the first cycle, after 5, 10, 15 and 20 cycles. For FT-2, we exposed three rock samples with different saturation regimes (30%, 70%, 100%) to one sustained freezing cycle with 66 h freezing at -10Â°C while monitoring rock temperature and AE.  We scanned the rock samples using micro CT before the first cycle and after the end of the experiment. The data set comprises all micro CT scans, rock temperature and AE data.", $dataPublication->msl_description_abstract);
        $this->assertEquals("We tested the efficacy of frequent diurnal freeze-thaw cycles (FT-1) and sustained freezing cycles (FT-2) on low-<span data-uris='[\"https://epos-msl.uu.nl/voc/rockphysics/1.3/measured_property-porosity\", \"https://epos-msl.uu.nl/voc/analoguemodelling/1.3/measured_property-porosity\"]'>porosity</span> Dachstein <span data-uris='[\"https://epos-msl.uu.nl/voc/materials/1.3/sedimentary_rock-limestone\"]'>limestone</span> (0.1 % <span data-uris='[\"https://epos-msl.uu.nl/voc/rockphysics/1.3/measured_property-porosity\", \"https://epos-msl.uu.nl/voc/analoguemodelling/1.3/measured_property-porosity\"]'>porosity</span>). For FT-1, we exposed three rock samples with different saturation regimes (30%, 70%, 100%) to 20 freeze-thaw cycles between 10 and -10Â°C while monitoring rock temperature and <span data-uris='[\"https://epos-msl.uu.nl/voc/rockphysics/1.3/measured_property-acoustic_emission_ae\", \"https://epos-msl.uu.nl/voc/analoguemodelling/1.3/measured_property-acoustic_emission_ae\"]'>acoustic emission</span> (<span data-uris='[\"https://epos-msl.uu.nl/voc/rockphysics/1.3/measured_property-acoustic_emission_ae\", \"https://epos-msl.uu.nl/voc/analoguemodelling/1.3/measured_property-acoustic_emission_ae\"]'>AE</span>). We scanned the rock samples using micro computational tomography (CT) before the first cycle, after 5, 10, 15 and 20 cycles. For FT-2, we exposed three rock samples with different saturation regimes (30%, 70%, 100%) to one sustained freezing cycle with 66 h freezing at -10Â°C while monitoring rock temperature and <span data-uris='[\"https://epos-msl.uu.nl/voc/rockphysics/1.3/measured_property-acoustic_emission_ae\", \"https://epos-msl.uu.nl/voc/analoguemodelling/1.3/measured_property-acoustic_emission_ae\"]'>AE</span>.  We scanned the rock samples using micro CT before the first cycle and after the end of the experiment. The data set comprises all micro <span data-uris='[\"https://epos-msl.uu.nl/voc/microscopy/1.3/apparatus-x-ray_tomography\"]'>CT scans</span>, rock temperature and <span data-uris='[\"https://epos-msl.uu.nl/voc/rockphysics/1.3/measured_property-acoustic_emission_ae\", \"https://epos-msl.uu.nl/voc/analoguemodelling/1.3/measured_property-acoustic_emission_ae\"]'>AE</span> data.", $dataPublication->msl_description_abstract_annotated);
        $this->assertEquals('', $dataPublication->msl_description_methods);
        $this->assertEquals('', $dataPublication->msl_description_methods_annotated);
        $this->assertEquals('', $dataPublication->msl_description_series_information);
        $this->assertEquals('', $dataPublication->msl_description_series_information_annotated);
        $this->assertEquals('', $dataPublication->msl_description_table_of_contents);
        $this->assertEquals('', $dataPublication->msl_description_table_of_contents_annotated);
        $this->assertEquals('', $dataPublication->msl_description_technical_info);
        $this->assertEquals('', $dataPublication->msl_description_technical_info_annotated);
        $this->assertEquals('', $dataPublication->msl_description_other);
        $this->assertEquals('', $dataPublication->msl_description_other_annotated);

        // msl_rights
        $this->assertCount(2, $dataPublication->msl_rights);
        $this->assertInstanceOf(Right::class, $dataPublication->msl_rights[0]);
        $this->assertEquals('Open - freely retrievable', $dataPublication->msl_rights[0]->msl_right);
        $this->assertEquals('info:eu-repo/semantics/openAccess', $dataPublication->msl_rights[0]->msl_right_uri);
        $this->assertEquals('', $dataPublication->msl_rights[0]->msl_right_identifier);
        $this->assertEquals('', $dataPublication->msl_rights[0]->msl_right_identifier_scheme);
        $this->assertEquals('', $dataPublication->msl_rights[0]->msl_right_scheme_uri);
        $this->assertInstanceOf(Right::class, $dataPublication->msl_rights[1]);
        $this->assertEquals('Creative Commons Attribution 4.0 International Public License', $dataPublication->msl_rights[1]->msl_right);
        $this->assertEquals('https://creativecommons.org/licenses/by/4.0/legalcode', $dataPublication->msl_rights[1]->msl_right_uri);
        $this->assertEquals('', $dataPublication->msl_rights[1]->msl_right_identifier);
        $this->assertEquals('', $dataPublication->msl_rights[1]->msl_right_identifier_scheme);
        $this->assertEquals('', $dataPublication->msl_rights[1]->msl_right_scheme_uri);

        $this->assertEquals('10.24416/uu01-q5k96z', $dataPublication->msl_doi);

        // msl_alternate_identifiers
        $this->assertCount(2, $dataPublication->msl_alternate_identifiers);
        $this->assertInstanceOf(AlternateIdentifier::class, $dataPublication->msl_alternate_identifiers[0]);
        $this->assertEquals("Mandziak, Anna; Prieto", $dataPublication->msl_alternate_identifiers[0]->msl_alternate_identifier);
        $this->assertEquals('citation', $dataPublication->msl_alternate_identifiers[0]->msl_alternate_identifier_type);
        $this->assertInstanceOf(AlternateIdentifier::class, $dataPublication->msl_alternate_identifiers[1]);
        $this->assertEquals("http://hdl.handle.net/10261/377278", $dataPublication->msl_alternate_identifiers[1]->msl_alternate_identifier);
        $this->assertEquals('uri', $dataPublication->msl_alternate_identifiers[1]->msl_alternate_identifier_type);

        // msl_related_identifiers
        $this->assertCount(1, $dataPublication->msl_related_identifiers);
        $this->assertInstanceOf(RelatedIdentifier::class, $dataPublication->msl_related_identifiers[0]);
        $this->assertEquals('will follow soon', $dataPublication->msl_related_identifiers[0]->msl_related_identifier);
        $this->assertEquals('DOI', $dataPublication->msl_related_identifiers[0]->msl_related_identifier_type);
        $this->assertEquals('IsSupplementTo', $dataPublication->msl_related_identifiers[0]->msl_related_identifier_relation_type);
        $this->assertEquals('', $dataPublication->msl_related_identifiers[0]->msl_related_identifier_metadata_scheme);
        $this->assertEquals('', $dataPublication->msl_related_identifiers[0]->msl_related_identifier_metadata_scheme_uri);
        $this->assertEquals('', $dataPublication->msl_related_identifiers[0]->msl_related_identifier_metadata_scheme_type);
        $this->assertEquals('', $dataPublication->msl_related_identifiers[0]->msl_related_identifier_resource_type_general);

        $this->assertEquals('2023', $dataPublication->msl_publication_year);

        // msl_funding_references
        $this->assertCount(3, $dataPublication->msl_funding_references);
        $this->assertInstanceOf(FundingReference::class, $dataPublication->msl_funding_references[0]);
        $this->assertEquals('German Research Foundation (DFG)', $dataPublication->msl_funding_references[0]->msl_funding_reference_funder_name);
        $this->assertEquals('', $dataPublication->msl_funding_references[0]->msl_funding_reference_funder_identifier);
        $this->assertEquals('', $dataPublication->msl_funding_references[0]->msl_funding_reference_funder_identifier_type);
        $this->assertEquals('', $dataPublication->msl_funding_references[0]->msl_funding_reference_scheme_uri);
        $this->assertEquals('', $dataPublication->msl_funding_references[0]->msl_funding_reference_award_number);
        $this->assertEquals('', $dataPublication->msl_funding_references[0]->msl_funding_reference_award_uri);
        $this->assertEquals('', $dataPublication->msl_funding_references[0]->msl_funding_reference_award_title);
        $this->assertInstanceOf(FundingReference::class, $dataPublication->msl_funding_references[1]);
        $this->assertEquals('European Union’s Horizon 2020 Excite grants ', $dataPublication->msl_funding_references[1]->msl_funding_reference_funder_name);
        $this->assertEquals('', $dataPublication->msl_funding_references[1]->msl_funding_reference_funder_identifier);
        $this->assertEquals('', $dataPublication->msl_funding_references[1]->msl_funding_reference_funder_identifier_type);
        $this->assertEquals('', $dataPublication->msl_funding_references[1]->msl_funding_reference_scheme_uri);
        $this->assertEquals('', $dataPublication->msl_funding_references[1]->msl_funding_reference_award_number);
        $this->assertEquals('', $dataPublication->msl_funding_references[1]->msl_funding_reference_award_uri);
        $this->assertEquals('', $dataPublication->msl_funding_references[1]->msl_funding_reference_award_title);
        $this->assertInstanceOf(FundingReference::class, $dataPublication->msl_funding_references[2]);
        $this->assertEquals('European Union’s Horizon 2020 Excite grants ', $dataPublication->msl_funding_references[2]->msl_funding_reference_funder_name);
        $this->assertEquals('', $dataPublication->msl_funding_references[2]->msl_funding_reference_funder_identifier);
        $this->assertEquals('', $dataPublication->msl_funding_references[2]->msl_funding_reference_funder_identifier_type);
        $this->assertEquals('', $dataPublication->msl_funding_references[2]->msl_funding_reference_scheme_uri);
        $this->assertEquals('', $dataPublication->msl_funding_references[2]->msl_funding_reference_award_number);
        $this->assertEquals('', $dataPublication->msl_funding_references[2]->msl_funding_reference_award_uri);
        $this->assertEquals('', $dataPublication->msl_funding_references[2]->msl_funding_reference_award_title);

        $this->assertEquals('en', $dataPublication->msl_language);

        // msl_dates
        $this->assertCount(2, $dataPublication->msl_dates);
        $this->assertInstanceOf(Date::class, $dataPublication->msl_dates[0]);
        $this->assertEquals('2023-10-30T09:01:06', $dataPublication->msl_dates[0]->msl_date_date);
        $this->assertEquals('Updated', $dataPublication->msl_dates[0]->msl_date_type);
        $this->assertEquals('', $dataPublication->msl_dates[0]->msl_date_information);
        $this->assertInstanceOf(Date::class, $dataPublication->msl_dates[1]);
        $this->assertEquals('2022-05-12/2022-12-09', $dataPublication->msl_dates[1]->msl_date_date);
        $this->assertEquals('Collected', $dataPublication->msl_dates[1]->msl_date_type);
        $this->assertEquals('', $dataPublication->msl_dates[1]->msl_date_information);

        // msl_creators
        $this->assertCount(1, $dataPublication->msl_creators);
        $this->assertInstanceOf(Creator::class, $dataPublication->msl_creators[0]);
        $this->assertEquals('', $dataPublication->msl_creators[0]->msl_creator_name);
        $this->assertEquals('Daniel', $dataPublication->msl_creators[0]->msl_creator_given_name);
        $this->assertEquals('Draebing', $dataPublication->msl_creators[0]->msl_creator_family_name);
        $this->assertEquals('Personal', $dataPublication->msl_creators[0]->msl_creator_name_type);
        $this->assertCount(1, $dataPublication->msl_creators[0]->affiliations);
        $this->assertInstanceOf(Affiliation::class, $dataPublication->msl_creators[0]->affiliations[0]);
        $this->assertEquals('Utrecht University', $dataPublication->msl_creators[0]->affiliations[0]->msl_creator_affiliation_name);
        $this->assertEquals('', $dataPublication->msl_creators[0]->affiliations[0]->msl_creator_affiliation_identifier);
        $this->assertEquals('', $dataPublication->msl_creators[0]->affiliations[0]->msl_creator_affiliation_identifier_scheme);
        $this->assertEquals('', $dataPublication->msl_creators[0]->affiliations[0]->msl_creator_affiliation_scheme_uri);
        $this->assertCount(1, $dataPublication->msl_creators[0]->nameIdentifiers);
        $this->assertInstanceOf(NameIdentifier::class, $dataPublication->msl_creators[0]->nameIdentifiers[0]);
        $this->assertEquals('0000-0001-6379-4707', $dataPublication->msl_creators[0]->nameIdentifiers[0]->msl_creator_name_identifier);
        $this->assertEquals('ORCID', $dataPublication->msl_creators[0]->nameIdentifiers[0]->msl_creator_name_identifiers_scheme);
        $this->assertEquals('', $dataPublication->msl_creators[0]->nameIdentifiers[0]->msl_creator_name_identifiers_uri);

        // msl_contributors
        $this->assertCount(5, $dataPublication->msl_contributors);
        $this->assertInstanceOf(Contributor::class, $dataPublication->msl_contributors[0]);
        $this->assertEquals('Mayer, Till', $dataPublication->msl_contributors[0]->msl_contributor_name);
        $this->assertEquals('Researcher', $dataPublication->msl_contributors[0]->msl_contributor_type);
        $this->assertEquals('', $dataPublication->msl_contributors[0]->msl_contributor_given_name);
        $this->assertEquals('', $dataPublication->msl_contributors[0]->msl_contributor_family_name);
        $this->assertEquals('Personal', $dataPublication->msl_contributors[0]->msl_contributor_name_type);
        $this->assertCount(2, $dataPublication->msl_contributors[0]->affiliations);
        $this->assertInstanceOf(Affiliation::class, $dataPublication->msl_contributors[0]->affiliations[0]);
        $this->assertEquals('University of Bayreuth', $dataPublication->msl_contributors[0]->affiliations[0]->msl_creator_affiliation_name);
        $this->assertEquals('', $dataPublication->msl_contributors[0]->affiliations[0]->msl_creator_affiliation_identifier);
        $this->assertEquals('', $dataPublication->msl_contributors[0]->affiliations[0]->msl_creator_affiliation_identifier_scheme);
        $this->assertEquals('', $dataPublication->msl_contributors[0]->affiliations[0]->msl_creator_affiliation_scheme_uri);
        $this->assertInstanceOf(Affiliation::class, $dataPublication->msl_contributors[0]->affiliations[1]);
        $this->assertEquals('Technical University of Munich', $dataPublication->msl_contributors[0]->affiliations[1]->msl_creator_affiliation_name);
        $this->assertEquals('', $dataPublication->msl_contributors[0]->affiliations[1]->msl_creator_affiliation_identifier);
        $this->assertEquals('', $dataPublication->msl_contributors[0]->affiliations[1]->msl_creator_affiliation_identifier_scheme);
        $this->assertEquals('', $dataPublication->msl_contributors[0]->affiliations[1]->msl_creator_affiliation_scheme_uri);
        $this->assertCount(1, $dataPublication->msl_contributors[0]->nameIdentifiers);
        $this->assertInstanceOf(NameIdentifier::class, $dataPublication->msl_contributors[0]->nameIdentifiers[0]);
        $this->assertEquals('0000-0001-6677-9166', $dataPublication->msl_contributors[0]->nameIdentifiers[0]->msl_creator_name_identifier);
        $this->assertEquals('ORCID', $dataPublication->msl_contributors[0]->nameIdentifiers[0]->msl_creator_name_identifiers_scheme);
        $this->assertEquals('', $dataPublication->msl_contributors[0]->nameIdentifiers[0]->msl_creator_name_identifiers_uri);

        // msl_sizes
        $this->assertCount(2, $dataPublication->msl_sizes);
        $this->assertArrayHasKey('msl_size', $dataPublication->msl_sizes[0]);
        $this->assertEquals('11 files', $dataPublication->msl_sizes[0]['msl_size']);
        $this->assertArrayHasKey('msl_size', $dataPublication->msl_sizes[1]);
        $this->assertEquals('113 kB', $dataPublication->msl_sizes[1]['msl_size']);

        // msl_formats
        $this->assertCount(1, $dataPublication->msl_formats);
        $this->assertArrayHasKey('msl_format', $dataPublication->msl_formats[0]);
        $this->assertEquals('text/csv', $dataPublication->msl_formats[0]['msl_format']);

        $this->assertEquals('1', $dataPublication->msl_datacite_version);
        $this->assertCount(0, $dataPublication->msl_laboratories);

        // msl_files
        $this->assertCount(1, $dataPublication->msl_files);
        $this->assertInstanceOf(File::class, $dataPublication->msl_files[0]);
        $this->assertEquals('Original data',$dataPublication->msl_files[0]->msl_file_name);
        $this->assertEquals('https://geo.public.data.uu.nl:443/vault-frost-shared/research-frost-shared[1697635081]/original/Original data/',$dataPublication->msl_files[0]->msl_download_link);
        $this->assertEquals('',$dataPublication->msl_files[0]->msl_extension);
        $this->assertTrue($dataPublication->msl_files[0]->msl_is_folder);
        $this->assertEquals('',$dataPublication->msl_files[0]->msl_timestamp);

        $this->assertEquals('Utrecht University',$dataPublication->msl_publisher);
        $this->assertEquals("Draebing, D. (2023). <i>Micro Computational Tomography, Acoustic Emission and rock temperature data from frost weathering tests on Dachstein Limestone</i> (Version 1) [Data set]. Utrecht University. https://doi.org/10.24416/UU01-Q5K96Z", $dataPublication->msl_citation);
        $this->assertCount(0, $dataPublication->msl_spatial_coordinates);
        $this->assertEquals('{"type":"FeatureCollection","features":[{"type":"Feature","geometry":{"type":"Polygon","coordinates":[[[-60.83408,-51.68212],[-60.83408,-52.13647],[-59.37044,-52.13647],[-59.37044,-51.68212],[-60.83408,-51.68212]]]},"properties":{"name":""}}]}',$dataPublication->msl_geojson_featurecollection);
        $this->assertEquals('', $dataPublication->msl_geojson_featurecollection_points);
        $this->assertEquals(0, $dataPublication->msl_surface_area);

        $this->assertCount(2, $dataPublication->msl_geolocations);
        $this->assertArrayHasKey('msl_geolocation', $dataPublication->msl_geolocations[0]);
        $this->assertEquals('New Haven Falkland Islands',$dataPublication->msl_geolocations[0]['msl_geolocation']);
        $this->assertArrayHasKey('msl_geolocation', $dataPublication->msl_geolocations[1]);
        $this->assertEquals('Port Stephens Falkland Islands',$dataPublication->msl_geolocations[1]['msl_geolocation']);

        $this->assertCount(1,$dataPublication->extras);
        $this->assertEquals('spatial', $dataPublication->extras[0]['key']);
        $this->assertEquals('{"type":"Polygon","coordinates":[[[-60.83408,-51.68212],[-60.83408,-52.13647],[-59.37044,-52.13647],[-59.37044,-51.68212],[-60.83408,-51.68212]]]}', $dataPublication->extras[0]['value']);

        $this->assertCount(0, $dataPublication->tag_string);

        // msl_tags
        $this->assertCount(7, $dataPublication->msl_tags);
        $this->assertInstanceOf(Tag::class, $dataPublication->msl_tags[0]);
        $this->assertEquals('Natural Sciences - Earth and related environmental sciences (1.5)', $dataPublication->msl_tags[0]->msl_tag_string);
        $this->assertEquals('', $dataPublication->msl_tags[0]->msl_tag_scheme_uri);
        $this->assertEquals('', $dataPublication->msl_tags[0]->msl_tag_value_uri);
        $this->assertEquals('OECD FOS 2007', $dataPublication->msl_tags[0]->msl_tag_subject_scheme);
        $this->assertEquals('', $dataPublication->msl_tags[0]->msl_tag_classification_code);
        $this->assertCount(0, $dataPublication->msl_tags[0]->msl_tag_msl_uris);
        $this->assertInstanceOf(Tag::class, $dataPublication->msl_tags[3]);
        $this->assertEquals('X-ray Tomography', $dataPublication->msl_tags[3]->msl_tag_string);
        $this->assertEquals('', $dataPublication->msl_tags[3]->msl_tag_scheme_uri);
        $this->assertEquals('', $dataPublication->msl_tags[3]->msl_tag_value_uri);
        $this->assertEquals('Keyword', $dataPublication->msl_tags[3]->msl_tag_subject_scheme);
        $this->assertEquals('', $dataPublication->msl_tags[3]->msl_tag_classification_code);
        $this->assertCount(2, $dataPublication->msl_tags[3]->msl_tag_msl_uris);
        $this->assertEquals('https://epos-msl.uu.nl/voc/microscopy/1.3/apparatus-x-ray_tomography', $dataPublication->msl_tags[3]->msl_tag_msl_uris[0]);
        $this->assertEquals('https://epos-msl.uu.nl/voc/microscopy/1.3/technique-imaging_3d-computed_tomography_ct', $dataPublication->msl_tags[3]->msl_tag_msl_uris[1]);

        // msl_subdomains
        $this->assertCount(3,$dataPublication->msl_subdomains);
        $this->assertEquals('microscopy and tomography',$dataPublication->msl_subdomains[0]['msl_subdomain']);
        $this->assertEquals('rock and melt physics',$dataPublication->msl_subdomains[1]['msl_subdomain']);
        $this->assertEquals('analogue modelling of geologic processes',$dataPublication->msl_subdomains[2]['msl_subdomain']);

        $this->assertCount(0,$dataPublication->msl_subdomains_original);

        // msl_subdomains_interpreted
        $this->assertCount(3,$dataPublication->msl_subdomains_interpreted);
        $this->assertEquals('microscopy and tomography',$dataPublication->msl_subdomains_interpreted[0]['msl_subdomain_interpreted']);
        $this->assertEquals('rock and melt physics',$dataPublication->msl_subdomains_interpreted[1]['msl_subdomain_interpreted']);
        $this->assertEquals('analogue modelling of geologic processes',$dataPublication->msl_subdomains_interpreted[2]['msl_subdomain_interpreted']);

        // msl_enriched_keywords
        $this->assertCount(13,$dataPublication->msl_enriched_keywords);
        $this->assertInstanceOf(EnrichedKeyword::class, $dataPublication->msl_enriched_keywords[0]);
        $this->assertEquals('Apparatus', $dataPublication->msl_enriched_keywords[0]->msl_enriched_keyword_label);
        $this->assertEquals('https://epos-msl.uu.nl/voc/microscopy/1.3/apparatus', $dataPublication->msl_enriched_keywords[0]->msl_enriched_keyword_uri);
        $this->assertEquals('https://epos-msl.uu.nl/voc/microscopy/1.3/', $dataPublication->msl_enriched_keywords[0]->msl_enriched_keyword_vocab_uri);
        $this->assertCount(0,$dataPublication->msl_enriched_keywords[0]->msl_enriched_keyword_associated_subdomains);
        $this->assertCount(1,$dataPublication->msl_enriched_keywords[0]->msl_enriched_keyword_match_locations);
        $this->assertEquals('parent', $dataPublication->msl_enriched_keywords[0]->msl_enriched_keyword_match_locations[0]);
        $this->assertCount(1,$dataPublication->msl_enriched_keywords[0]->msl_enriched_keyword_match_child_uris);
        $this->assertEquals('https://epos-msl.uu.nl/voc/microscopy/1.3/apparatus-x-ray_tomography', $dataPublication->msl_enriched_keywords[0]->msl_enriched_keyword_match_child_uris[0]);

        // msl_original_keywords
        $this->assertCount(4,$dataPublication->msl_original_keywords);
        $this->assertInstanceOf(OriginalKeyword::class, $dataPublication->msl_original_keywords[0]);
        $this->assertEquals('X-ray tomography', $dataPublication->msl_original_keywords[0]->msl_original_keyword_label);
        $this->assertEquals('https://epos-msl.uu.nl/voc/microscopy/1.3/apparatus-x-ray_tomography', $dataPublication->msl_original_keywords[0]->msl_original_keyword_uri);
        $this->assertEquals('https://epos-msl.uu.nl/voc/microscopy/1.3/', $dataPublication->msl_original_keywords[0]->msl_original_keyword_vocab_uri);

        $this->assertTrue($dataPublication->msl_has_material);
        $this->assertFalse($dataPublication->msl_has_material_original);
        $this->assertFalse($dataPublication->msl_has_porefluid);
        $this->assertFalse($dataPublication->msl_has_porefluid_original);
        $this->assertTrue($dataPublication->msl_has_rockphysic);
        $this->assertTrue($dataPublication->msl_has_rockphysic_original);
        $this->assertTrue($dataPublication->msl_has_analogue);
        $this->assertTrue($dataPublication->msl_has_analogue_original);
        $this->assertFalse($dataPublication->msl_has_geologicalage);
        $this->assertFalse($dataPublication->msl_has_geologicalage_original);
        $this->assertFalse($dataPublication->msl_has_geologicalsetting);
        $this->assertFalse($dataPublication->msl_has_geologicalsetting_original);
        $this->assertFalse($dataPublication->msl_has_paleomagnetism);
        $this->assertFalse($dataPublication->msl_has_paleomagnetism_original);
        $this->assertFalse($dataPublication->msl_has_geochemistry);
        $this->assertFalse($dataPublication->msl_has_geochemistry_original);
        $this->assertTrue($dataPublication->msl_has_microscopy);
        $this->assertTrue($dataPublication->msl_has_microscopy_original);
        $this->assertFalse($dataPublication->msl_has_subsurface);
        $this->assertFalse($dataPublication->msl_has_subsurface_original);
        $this->assertFalse($dataPublication->msl_has_geoenergy);
        $this->assertFalse($dataPublication->msl_has_geoenergy_original);
        $this->assertFalse($dataPublication->msl_has_lab);
        $this->assertTrue($dataPublication->msl_has_organization);
    }

    /**
     * test adding a tag
     */
    public function test_add_tag(): void
    {
        $dataPublication = new DataPublication;

        $tag = new Tag('test tag', 'epos-msl', 'epos-msl/testtag', 'epos-msl scheme', '1234', ['epos-msl/1.1/test-tag']);

        $dataPublication->addTag($tag);

        $this->assertEquals($dataPublication->msl_tags[0]->msl_tag_string, 'test tag');
        $this->assertEquals($dataPublication->msl_tags[0]->msl_tag_scheme_uri, 'epos-msl');
        $this->assertEquals($dataPublication->msl_tags[0]->msl_tag_value_uri, 'epos-msl/testtag');
        $this->assertEquals($dataPublication->msl_tags[0]->msl_tag_subject_scheme, 'epos-msl scheme');
        $this->assertEquals($dataPublication->msl_tags[0]->msl_tag_classification_code, '1234');
        $this->assertContains('epos-msl/1.1/test-tag', $dataPublication->msl_tags[0]->msl_tag_msl_uris);
    }

    /**
     * test adding a tag that allready exists
     */
    public function test_add_existing_tag(): void
    {
        $dataPublication = new DataPublication;

        $tag1 = new Tag('test tag', 'epos-msl', 'epos-msl/testtag', 'epos-msl scheme', '1234', ['epos-msl/1.1/test-tag']);
        $tag2 = new Tag('test tag', 'epos-msl', 'epos-msl/testtag', 'epos-msl scheme', '1234', ['epos-msl/1.1/test-tag']);
        $tag3 = new Tag('test tag3', 'epos-msl', 'epos-msl/testtag', 'epos-msl scheme', '1234', ['epos-msl/1.1/test-tag']);

        $dataPublication->addTag($tag1);
        $dataPublication->addTag($tag2);

        // only 1 tag should exists as the tag strings are the same
        $this->assertEquals(count($dataPublication->msl_tags), 1);

        // add third tag with not existing tag string
        $dataPublication->addTag($tag3);

        // 2 tags should now be present within the data publication
        $this->assertEquals(count($dataPublication->msl_tags), 2);
    }

    /**
     * test adding a tag to an existing tag
     */
    public function test_add_uri_to_tag(): void
    {
        $dataPublication = new DataPublication;

        $tag = new Tag('test tag1', 'epos-msl', 'epos-msl/testtag', 'epos-msl scheme', '1234');

        $dataPublication->addTag($tag);

        $dataPublication->addUriToTag('test tag1', 'test/uri');

        // check if tag contains added uri
        $this->assertContains('test/uri', $dataPublication->msl_tags[0]->msl_tag_msl_uris);

        // add the same uri again and check that is not added
        $dataPublication->addUriToTag('test tag1', 'test/uri');
        $this->assertEquals(count($dataPublication->msl_tags[0]->msl_tag_msl_uris), 1);

        // add a unique one and check if it is added
        $dataPublication->addUriToTag('test tag1', 'test/uri2');
        $this->assertContains('test/uri', $dataPublication->msl_tags[0]->msl_tag_msl_uris);
        $this->assertContains('test/uri2', $dataPublication->msl_tags[0]->msl_tag_msl_uris);
        $this->assertEquals(count($dataPublication->msl_tags[0]->msl_tag_msl_uris), 2);
    }

    /**
     * test adding an original keyword
     */
    public function test_add_orginal_keyword(): void
    {
        $dataPublication = new DataPublication;

        $originalKeyword = new OriginalKeyword('label', 'uri', 'https://epos-msl.uu.nl/voc/materials');

        $dataPublication->addOriginalKeyword($originalKeyword);

        $this->assertEquals(count($dataPublication->msl_original_keywords), 1);
        $this->assertEquals($dataPublication->msl_original_keywords[0]->msl_original_keyword_label, 'label');
        $this->assertEquals($dataPublication->msl_original_keywords[0]->msl_original_keyword_uri, 'uri');
        $this->assertEquals($dataPublication->msl_original_keywords[0]->msl_original_keyword_vocab_uri, 'https://epos-msl.uu.nl/voc/materials');
    }

    /**
     * test adding an existing original keyword
     */
    public function test_add_existing_orginal_keyword(): void
    {
        $dataPublication = new DataPublication;

        $originalKeyword1 = new OriginalKeyword('label', 'uri1', 'https://epos-msl.uu.nl/voc/materials');
        $originalKeyword2 = new OriginalKeyword('label', 'uri1', 'https://epos-msl.uu.nl/voc/materials');
        $originalKeyword3 = new OriginalKeyword('label', 'uri2', 'https://epos-msl.uu.nl/voc/materials');

        $dataPublication->addOriginalKeyword($originalKeyword1);
        $dataPublication->addOriginalKeyword($originalKeyword2);

        // check that only one original keyword is now present within the data publication as both added keywords have the same uri
        $this->assertEquals(count($dataPublication->msl_original_keywords), 1);

        // add another original keyword with a unique uri and check that is added
        $dataPublication->addOriginalKeyword($originalKeyword3);
        $this->assertEquals(count($dataPublication->msl_original_keywords), 2);
    }

    /**
     * test adding an enriched keyword
     */
    public function test_add_enriched_keyword(): void
    {
        $dataPublication = new DataPublication;

        $enrichedKeyword = new EnrichedKeyword('label', 'uri', 'https://epos-msl.uu.nl/voc/materials', ['sudomain1'], ['location1'], ['childUri1']);

        $dataPublication->addEnrichedKeyword($enrichedKeyword);

        // check if the enriched keyword is properly added to the data publication
        $this->assertEquals($dataPublication->msl_enriched_keywords[0]->msl_enriched_keyword_label, 'label');
        $this->assertEquals($dataPublication->msl_enriched_keywords[0]->msl_enriched_keyword_uri, 'uri');
        $this->assertEquals($dataPublication->msl_enriched_keywords[0]->msl_enriched_keyword_vocab_uri, 'https://epos-msl.uu.nl/voc/materials');
        $this->assertEquals($dataPublication->msl_enriched_keywords[0]->msl_enriched_keyword_associated_subdomains, ['sudomain1']);
        $this->assertEquals($dataPublication->msl_enriched_keywords[0]->msl_enriched_keyword_match_locations, ['location1']);
        $this->assertEquals($dataPublication->msl_enriched_keywords[0]->msl_enriched_keyword_match_child_uris, ['childUri1']);
    }

    /**
     * test adding an existing enriched keyword
     */
    public function test_add_existing_enriched_keyword(): void
    {
        $dataPublication = new DataPublication;

        $enrichedKeyword1 = new EnrichedKeyword('label', 'uri1', 'https://epos-msl.uu.nl/voc/materials', ['sudomain1'], ['location1'], ['childUri1']);
        $enrichedKeyword2 = new EnrichedKeyword('label', 'uri1', 'https://epos-msl.uu.nl/voc/materials', ['sudomain2'], ['location1'], ['childUri2']);

        $dataPublication->addEnrichedKeyword($enrichedKeyword1);
        $dataPublication->addEnrichedKeyword($enrichedKeyword2);

        // a single enriched keyword should be added due to matching uris, associatated subdomains and match child uris should contain new unique values
        $this->assertEquals(count($dataPublication->msl_enriched_keywords), 1);

        $this->assertEquals(count($dataPublication->msl_enriched_keywords[0]->msl_enriched_keyword_associated_subdomains), 2);
        $this->assertContains('sudomain1', $dataPublication->msl_enriched_keywords[0]->msl_enriched_keyword_associated_subdomains);
        $this->assertContains('sudomain2', $dataPublication->msl_enriched_keywords[0]->msl_enriched_keyword_associated_subdomains);

        $this->assertEquals(count($dataPublication->msl_enriched_keywords[0]->msl_enriched_keyword_match_locations), 1);
        $this->assertContains('location1', $dataPublication->msl_enriched_keywords[0]->msl_enriched_keyword_match_locations);

        $this->assertEquals(count($dataPublication->msl_enriched_keywords[0]->msl_enriched_keyword_match_child_uris), 2);
        $this->assertContains('childUri1', $dataPublication->msl_enriched_keywords[0]->msl_enriched_keyword_match_child_uris);
        $this->assertContains('childUri2', $dataPublication->msl_enriched_keywords[0]->msl_enriched_keyword_match_child_uris);
    }

    /**
     * test adding files to data publication
     */
    public function test_add_files(): void
    {
        $dataPublication = new DataPublication;

        $file1 = new File('test.html', 'test.nl/download/test.html', 'html', false);
        $file2 = new File('test.pdf', 'test.nl/download/test.pdf', 'pdf', false, '123456');
        $file3 = new File('folder', 'test.nl/download/', '', true, '123456');

        $dataPublication->addFile($file1);
        $dataPublication->addFile($file2);
        $dataPublication->addFile($file3);

        $this->assertEquals('test.html', $dataPublication->msl_files[0]->msl_file_name);
        $this->assertEquals('test.nl/download/test.html', $dataPublication->msl_files[0]->msl_download_link);
        $this->assertEquals('html', $dataPublication->msl_files[0]->msl_extension);
        $this->assertEquals(false, $dataPublication->msl_files[0]->msl_is_folder);
        $this->assertEmpty($dataPublication->msl_files[0]->msl_timestamp);

        $this->assertEquals('test.pdf', $dataPublication->msl_files[1]->msl_file_name);
        $this->assertEquals('test.nl/download/test.pdf', $dataPublication->msl_files[1]->msl_download_link);
        $this->assertEquals('pdf', $dataPublication->msl_files[1]->msl_extension);
        $this->assertEquals(false, $dataPublication->msl_files[1]->msl_is_folder);
        $this->assertEquals('123456', $dataPublication->msl_files[1]->msl_timestamp);

        $this->assertEquals('folder', $dataPublication->msl_files[2]->msl_file_name);
        $this->assertEquals('test.nl/download/', $dataPublication->msl_files[2]->msl_download_link);
        $this->assertEquals('', $dataPublication->msl_files[2]->msl_extension);
        $this->assertEquals(true, $dataPublication->msl_files[2]->msl_is_folder);
        $this->assertEquals('123456', $dataPublication->msl_files[2]->msl_timestamp);
    }
}
