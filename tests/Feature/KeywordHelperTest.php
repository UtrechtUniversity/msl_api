<?php

namespace Tests\Feature;

use App\Mappers\Helpers\KeywordHelper;
use App\Models\Ckan\DataPublication;
use App\Models\Ckan\Tag;
use Tests\TestCase;
use Illuminate\Support\Facades\Config;

class KeywordHelperTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_map_tags_to_keywords_add_no_domain_tag(): void
    {
        //set the current vocabulary version to 1.3 to make sure we make the right assumptions
        Config::set('vocabularies.vocabularies_current_version', 1.3);
        
        $dataPublication = new DataPublication;
        $dataPublication->addTag(new Tag('Sandstone'));

        $keywordHelper = new KeywordHelper;

        $dataPublication = $keywordHelper->mapTagsToKeywords($dataPublication);

        // a tag should be available with tag_string Sandstone and uri reference to the uri of sandstone
        $this->assertEquals('Sandstone', $dataPublication->msl_tags[0]->msl_tag_string);
        $this->assertContains('https://epos-msl.uu.nl/voc/materials/1.3/sedimentary_rock-sandstone', $dataPublication->msl_tags[0]->msl_tag_msl_uris);

        // check that a correct corresponding original keyword has been added
        $this->assertEquals('sandstone', $dataPublication->msl_original_keywords[0]->msl_original_keyword_label);
        $this->assertEquals('https://epos-msl.uu.nl/voc/materials/1.3/sedimentary_rock-sandstone', $dataPublication->msl_original_keywords[0]->msl_original_keyword_uri);
        $this->assertEquals('https://epos-msl.uu.nl/voc/materials/1.3/', $dataPublication->msl_original_keywords[0]->msl_original_keyword_vocab_uri);

        // check that the correct enriched keywords are added
        $this->assertEquals('sedimentary rock', $dataPublication->msl_enriched_keywords[0]->msl_enriched_keyword_label);
        $this->assertEquals('https://epos-msl.uu.nl/voc/materials/1.3/sedimentary_rock', $dataPublication->msl_enriched_keywords[0]->msl_enriched_keyword_uri);
        $this->assertEquals('https://epos-msl.uu.nl/voc/materials/1.3/', $dataPublication->msl_enriched_keywords[0]->msl_enriched_keyword_vocab_uri);
        $this->assertEmpty($dataPublication->msl_enriched_keywords[0]->msl_enriched_keyword_associated_subdomains);
        $this->assertContains('parent', $dataPublication->msl_enriched_keywords[0]->msl_enriched_keyword_match_locations);
        $this->assertContains('https://epos-msl.uu.nl/voc/materials/1.3/sedimentary_rock-sandstone', $dataPublication->msl_enriched_keywords[0]->msl_enriched_keyword_match_child_uris);

        $this->assertEquals('sandstone', $dataPublication->msl_enriched_keywords[1]->msl_enriched_keyword_label);
        $this->assertEquals('https://epos-msl.uu.nl/voc/materials/1.3/sedimentary_rock-sandstone', $dataPublication->msl_enriched_keywords[1]->msl_enriched_keyword_uri);
        $this->assertEquals('https://epos-msl.uu.nl/voc/materials/1.3/', $dataPublication->msl_enriched_keywords[1]->msl_enriched_keyword_vocab_uri);
        $this->assertEmpty($dataPublication->msl_enriched_keywords[1]->msl_enriched_keyword_associated_subdomains);
        $this->assertContains('keyword', $dataPublication->msl_enriched_keywords[1]->msl_enriched_keyword_match_locations);
        $this->assertEmpty($dataPublication->msl_enriched_keywords[1]->msl_enriched_keyword_match_child_uris);
    }

    public function test_map_tags_to_keywords_add_domain_tag(): void
    {
        //set the current vocabulary version to 1.3 to make sure we make the right assumptions
        Config::set('vocabularies.vocabularies_current_version', 1.3);
        
        $dataPublication = new DataPublication;
        $dataPublication->addTag(new Tag('Grain failure'));

        $keywordHelper = new KeywordHelper;

        $dataPublication = $keywordHelper->mapTagsToKeywords($dataPublication);

        // a tag should be available with tag_string Sandstone and uri references
        $this->assertEquals('Grain failure', $dataPublication->msl_tags[0]->msl_tag_string);
        $this->assertContains('https://epos-msl.uu.nl/voc/rockphysics/1.3/inferred_deformation_behavior-microphysical_deformation_mechanism-intragranular_cracking', $dataPublication->msl_tags[0]->msl_tag_msl_uris);
        $this->assertContains('https://epos-msl.uu.nl/voc/microscopy/1.3/analyzed_feature-deformation_microstructure-brittle_microstructure-intragranular_crack', $dataPublication->msl_tags[0]->msl_tag_msl_uris);

        // check that the correct subdomains have been added to the data publication
        $this->assertTrue($dataPublication->hasSubDomain('rock and melt physics'));
        $this->assertTrue($dataPublication->hasSubDomain('microscopy and tomography'));
        
        $this->assertTrue($dataPublication->hasInterpretedSubDomain('rock and melt physics'));
        $this->assertTrue($dataPublication->hasInterpretedSubDomain('microscopy and tomography'));

        // check the original keywords
        $this->assertEquals('intragranular cracking', $dataPublication->msl_original_keywords[0]->msl_original_keyword_label);
        $this->assertEquals('https://epos-msl.uu.nl/voc/rockphysics/1.3/inferred_deformation_behavior-microphysical_deformation_mechanism-intragranular_cracking', $dataPublication->msl_original_keywords[0]->msl_original_keyword_uri);
        $this->assertEquals('https://epos-msl.uu.nl/voc/rockphysics/1.3/', $dataPublication->msl_original_keywords[0]->msl_original_keyword_vocab_uri);

        $this->assertEquals('intragranular crack', $dataPublication->msl_original_keywords[1]->msl_original_keyword_label);
        $this->assertEquals('https://epos-msl.uu.nl/voc/microscopy/1.3/analyzed_feature-deformation_microstructure-brittle_microstructure-intragranular_crack', $dataPublication->msl_original_keywords[1]->msl_original_keyword_uri);
        $this->assertEquals('https://epos-msl.uu.nl/voc/microscopy/1.3/', $dataPublication->msl_original_keywords[1]->msl_original_keyword_vocab_uri);

        // check the enriched keywords
        $this->assertEquals('Inferred deformation behavior', $dataPublication->msl_enriched_keywords[0]->msl_enriched_keyword_label);
        $this->assertEquals('https://epos-msl.uu.nl/voc/rockphysics/1.3/inferred_deformation_behavior', $dataPublication->msl_enriched_keywords[0]->msl_enriched_keyword_uri);
        $this->assertEquals('https://epos-msl.uu.nl/voc/rockphysics/1.3/', $dataPublication->msl_enriched_keywords[0]->msl_enriched_keyword_vocab_uri);
        $this->assertEmpty($dataPublication->msl_enriched_keywords[0]->msl_enriched_keyword_associated_subdomains);
        $this->assertContains('parent', $dataPublication->msl_enriched_keywords[0]->msl_enriched_keyword_match_locations);
        $this->assertContains('https://epos-msl.uu.nl/voc/rockphysics/1.3/inferred_deformation_behavior-microphysical_deformation_mechanism-intragranular_cracking', $dataPublication->msl_enriched_keywords[0]->msl_enriched_keyword_match_child_uris);

        $this->assertEquals('microphysical deformation mechanism', $dataPublication->msl_enriched_keywords[1]->msl_enriched_keyword_label);
        $this->assertEquals('https://epos-msl.uu.nl/voc/rockphysics/1.3/inferred_deformation_behavior-microphysical_deformation_mechanism', $dataPublication->msl_enriched_keywords[1]->msl_enriched_keyword_uri);
        $this->assertEquals('https://epos-msl.uu.nl/voc/rockphysics/1.3/', $dataPublication->msl_enriched_keywords[1]->msl_enriched_keyword_vocab_uri);
        $this->assertContains('rock and melt physics', $dataPublication->msl_enriched_keywords[1]->msl_enriched_keyword_associated_subdomains);
        $this->assertContains('parent', $dataPublication->msl_enriched_keywords[1]->msl_enriched_keyword_match_locations);
        $this->assertContains('https://epos-msl.uu.nl/voc/rockphysics/1.3/inferred_deformation_behavior-microphysical_deformation_mechanism-intragranular_cracking', $dataPublication->msl_enriched_keywords[1]->msl_enriched_keyword_match_child_uris);

        $this->assertEquals('intragranular cracking', $dataPublication->msl_enriched_keywords[2]->msl_enriched_keyword_label);
        $this->assertEquals('https://epos-msl.uu.nl/voc/rockphysics/1.3/inferred_deformation_behavior-microphysical_deformation_mechanism-intragranular_cracking', $dataPublication->msl_enriched_keywords[2]->msl_enriched_keyword_uri);
        $this->assertEquals('https://epos-msl.uu.nl/voc/rockphysics/1.3/', $dataPublication->msl_enriched_keywords[2]->msl_enriched_keyword_vocab_uri);
        $this->assertContains('rock and melt physics', $dataPublication->msl_enriched_keywords[2]->msl_enriched_keyword_associated_subdomains);
        $this->assertContains('keyword', $dataPublication->msl_enriched_keywords[2]->msl_enriched_keyword_match_locations);
        $this->assertEmpty($dataPublication->msl_enriched_keywords[2]->msl_enriched_keyword_match_child_uris);

        $this->assertEquals('Analyzed feature', $dataPublication->msl_enriched_keywords[3]->msl_enriched_keyword_label);
        $this->assertEquals('https://epos-msl.uu.nl/voc/microscopy/1.3/analyzed_feature', $dataPublication->msl_enriched_keywords[3]->msl_enriched_keyword_uri);
        $this->assertEquals('https://epos-msl.uu.nl/voc/microscopy/1.3/', $dataPublication->msl_enriched_keywords[3]->msl_enriched_keyword_vocab_uri);
        $this->assertEmpty($dataPublication->msl_enriched_keywords[3]->msl_enriched_keyword_associated_subdomains);
        $this->assertContains('parent', $dataPublication->msl_enriched_keywords[3]->msl_enriched_keyword_match_locations);
        $this->assertContains('https://epos-msl.uu.nl/voc/microscopy/1.3/analyzed_feature-deformation_microstructure-brittle_microstructure-intragranular_crack', $dataPublication->msl_enriched_keywords[3]->msl_enriched_keyword_match_child_uris);

        $this->assertEquals('deformation microstructure', $dataPublication->msl_enriched_keywords[4]->msl_enriched_keyword_label);
        $this->assertEquals('https://epos-msl.uu.nl/voc/microscopy/1.3/analyzed_feature-deformation_microstructure', $dataPublication->msl_enriched_keywords[4]->msl_enriched_keyword_uri);
        $this->assertEquals('https://epos-msl.uu.nl/voc/microscopy/1.3/', $dataPublication->msl_enriched_keywords[4]->msl_enriched_keyword_vocab_uri);
        $this->assertContains('microscopy and tomography', $dataPublication->msl_enriched_keywords[4]->msl_enriched_keyword_associated_subdomains);
        $this->assertContains('parent', $dataPublication->msl_enriched_keywords[4]->msl_enriched_keyword_match_locations);
        $this->assertContains('https://epos-msl.uu.nl/voc/microscopy/1.3/analyzed_feature-deformation_microstructure-brittle_microstructure-intragranular_crack', $dataPublication->msl_enriched_keywords[4]->msl_enriched_keyword_match_child_uris);
        
        $this->assertEquals('brittle microstructure', $dataPublication->msl_enriched_keywords[5]->msl_enriched_keyword_label);
        $this->assertEquals('https://epos-msl.uu.nl/voc/microscopy/1.3/analyzed_feature-deformation_microstructure-brittle_microstructure', $dataPublication->msl_enriched_keywords[5]->msl_enriched_keyword_uri);
        $this->assertEquals('https://epos-msl.uu.nl/voc/microscopy/1.3/', $dataPublication->msl_enriched_keywords[5]->msl_enriched_keyword_vocab_uri);
        $this->assertContains('microscopy and tomography', $dataPublication->msl_enriched_keywords[5]->msl_enriched_keyword_associated_subdomains);
        $this->assertContains('parent', $dataPublication->msl_enriched_keywords[5]->msl_enriched_keyword_match_locations);
        $this->assertContains('https://epos-msl.uu.nl/voc/microscopy/1.3/analyzed_feature-deformation_microstructure-brittle_microstructure-intragranular_crack', $dataPublication->msl_enriched_keywords[5]->msl_enriched_keyword_match_child_uris);

        $this->assertEquals('intragranular crack', $dataPublication->msl_enriched_keywords[6]->msl_enriched_keyword_label);
        $this->assertEquals('https://epos-msl.uu.nl/voc/microscopy/1.3/analyzed_feature-deformation_microstructure-brittle_microstructure-intragranular_crack', $dataPublication->msl_enriched_keywords[6]->msl_enriched_keyword_uri);
        $this->assertEquals('https://epos-msl.uu.nl/voc/microscopy/1.3/', $dataPublication->msl_enriched_keywords[6]->msl_enriched_keyword_vocab_uri);
        $this->assertContains('microscopy and tomography', $dataPublication->msl_enriched_keywords[6]->msl_enriched_keyword_associated_subdomains);
        $this->assertContains('keyword', $dataPublication->msl_enriched_keywords[6]->msl_enriched_keyword_match_locations);
        $this->assertEmpty($dataPublication->msl_enriched_keywords[6]->msl_enriched_keyword_match_child_uris);        
    }

    public function test_map_text_to_keywords_annotated_single_keyword(): void
    {
        //set the current vocabulary version to 1.3 to make sure we make the right assumptions
        Config::set('vocabularies.vocabularies_current_version', 1.3);
        
        $dataPublication = new DataPublication;
        $dataPublication->msl_description_abstract = "This is about sandstone";

        $keywordHelper = new KeywordHelper;
        $dataPublication = $keywordHelper->mapTextToKeywordsAnnotated($dataPublication, 'msl_description_abstract', 'msl_description_abstract_annotated', 'description abstract');

        // check that the correct enriched keywords are added
        $this->assertEquals('sedimentary rock', $dataPublication->msl_enriched_keywords[0]->msl_enriched_keyword_label);
        $this->assertEquals('https://epos-msl.uu.nl/voc/materials/1.3/sedimentary_rock', $dataPublication->msl_enriched_keywords[0]->msl_enriched_keyword_uri);
        $this->assertEquals('https://epos-msl.uu.nl/voc/materials/1.3/', $dataPublication->msl_enriched_keywords[0]->msl_enriched_keyword_vocab_uri);
        $this->assertEmpty($dataPublication->msl_enriched_keywords[0]->msl_enriched_keyword_associated_subdomains);
        $this->assertContains('parent', $dataPublication->msl_enriched_keywords[0]->msl_enriched_keyword_match_locations);
        $this->assertContains('https://epos-msl.uu.nl/voc/materials/1.3/sedimentary_rock-sandstone', $dataPublication->msl_enriched_keywords[0]->msl_enriched_keyword_match_child_uris);

        $this->assertEquals('sandstone', $dataPublication->msl_enriched_keywords[1]->msl_enriched_keyword_label);
        $this->assertEquals('https://epos-msl.uu.nl/voc/materials/1.3/sedimentary_rock-sandstone', $dataPublication->msl_enriched_keywords[1]->msl_enriched_keyword_uri);
        $this->assertEquals('https://epos-msl.uu.nl/voc/materials/1.3/', $dataPublication->msl_enriched_keywords[1]->msl_enriched_keyword_vocab_uri);
        $this->assertEmpty($dataPublication->msl_enriched_keywords[1]->msl_enriched_keyword_associated_subdomains);
        $this->assertContains('description abstract', $dataPublication->msl_enriched_keywords[1]->msl_enriched_keyword_match_locations);
        $this->assertEmpty($dataPublication->msl_enriched_keywords[1]->msl_enriched_keyword_match_child_uris);

        // check that the abstract annotation is set correctly
        $this->assertEquals('This is about <span data-uris=\'["https://epos-msl.uu.nl/voc/materials/1.3/sedimentary_rock-sandstone"]\'>sandstone</span>', $dataPublication->msl_description_abstract_annotated);
    }

    public function test_map_text_to_keywords_annotated_overlapping_keyword(): void
    {
        //set the current vocabulary version to 1.3 to make sure we make the right assumptions
        Config::set('vocabularies.vocabularies_current_version', 1.3);
        
        $dataPublication = new DataPublication;
        $dataPublication->msl_description_abstract = "This is about anstrude limestone";

        $keywordHelper = new KeywordHelper;
        $dataPublication = $keywordHelper->mapTextToKeywordsAnnotated($dataPublication, 'msl_description_abstract', 'msl_description_abstract_annotated', 'description abstract');

        // check that the correct enriched keywords are added
        $this->assertEquals('sedimentary rock', $dataPublication->msl_enriched_keywords[0]->msl_enriched_keyword_label);
        $this->assertEquals('https://epos-msl.uu.nl/voc/materials/1.3/sedimentary_rock', $dataPublication->msl_enriched_keywords[0]->msl_enriched_keyword_uri);
        $this->assertEquals('https://epos-msl.uu.nl/voc/materials/1.3/', $dataPublication->msl_enriched_keywords[0]->msl_enriched_keyword_vocab_uri);
        $this->assertEmpty($dataPublication->msl_enriched_keywords[0]->msl_enriched_keyword_associated_subdomains);
        $this->assertContains('parent', $dataPublication->msl_enriched_keywords[0]->msl_enriched_keyword_match_locations);
        $this->assertContains('https://epos-msl.uu.nl/voc/materials/1.3/sedimentary_rock-limestone', $dataPublication->msl_enriched_keywords[0]->msl_enriched_keyword_match_child_uris);
        $this->assertContains('https://epos-msl.uu.nl/voc/materials/1.3/sedimentary_rock-limestone-anstrude_limestone', $dataPublication->msl_enriched_keywords[0]->msl_enriched_keyword_match_child_uris);

        $this->assertEquals('limestone', $dataPublication->msl_enriched_keywords[1]->msl_enriched_keyword_label);
        $this->assertEquals('https://epos-msl.uu.nl/voc/materials/1.3/sedimentary_rock-limestone', $dataPublication->msl_enriched_keywords[1]->msl_enriched_keyword_uri);
        $this->assertEquals('https://epos-msl.uu.nl/voc/materials/1.3/', $dataPublication->msl_enriched_keywords[1]->msl_enriched_keyword_vocab_uri);
        $this->assertEmpty($dataPublication->msl_enriched_keywords[1]->msl_enriched_keyword_associated_subdomains);
        $this->assertContains('parent', $dataPublication->msl_enriched_keywords[1]->msl_enriched_keyword_match_locations);
        $this->assertContains('description abstract', $dataPublication->msl_enriched_keywords[1]->msl_enriched_keyword_match_locations);
        $this->assertContains('https://epos-msl.uu.nl/voc/materials/1.3/sedimentary_rock-limestone-anstrude_limestone', $dataPublication->msl_enriched_keywords[1]->msl_enriched_keyword_match_child_uris);

        $this->assertEquals('Anstrude limestone', $dataPublication->msl_enriched_keywords[2]->msl_enriched_keyword_label);
        $this->assertEquals('https://epos-msl.uu.nl/voc/materials/1.3/sedimentary_rock-limestone-anstrude_limestone', $dataPublication->msl_enriched_keywords[2]->msl_enriched_keyword_uri);
        $this->assertEquals('https://epos-msl.uu.nl/voc/materials/1.3/', $dataPublication->msl_enriched_keywords[2]->msl_enriched_keyword_vocab_uri);
        $this->assertEmpty($dataPublication->msl_enriched_keywords[2]->msl_enriched_keyword_associated_subdomains);
        $this->assertContains('description abstract', $dataPublication->msl_enriched_keywords[2]->msl_enriched_keyword_match_locations);
        $this->assertEmpty($dataPublication->msl_enriched_keywords[2]->msl_enriched_keyword_match_child_uris);

        // check that the abstract annotation is set correctly
        $this->assertEquals('This is about <span data-uris=\'["https://epos-msl.uu.nl/voc/materials/1.3/sedimentary_rock-limestone-anstrude_limestone"]\'>anstrude limestone</span>', $dataPublication->msl_description_abstract_annotated);
    }
}
