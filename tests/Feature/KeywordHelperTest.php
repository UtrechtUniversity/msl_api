<?php

namespace Tests\Feature;

use App\Mappers\Helpers\KeywordHelper;
use App\Models\Ckan\DataPublication;
use App\Models\Ckan\Tag;
use App\Models\Keyword;
use App\Models\KeywordSearch;
use App\Models\Vocabulary;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class KeywordHelperTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // set the current vocabulary version to 1.3 to make sure we make the right assumptions
        Config::set('vocabularies.vocabularies_current_version', 1.3);

        // setup required data
        $materialsVocabulary = Vocabulary::create([
            'name' => 'Material',
            'display_name' => 'materials',
            'version' => 1.3,
            'uri' => 'https://epos-msl.uu.nl/voc/materials/1.3/',
        ]);

        // add sedimentary rock keyword and search keywords
        $sedimentaryRockKeyword = Keyword::create([
            'parent_id' => null,
            'value' => 'sedimentary rock',
            'uri' => 'https://epos-msl.uu.nl/voc/materials/1.3/sedimentary_rock',
            'level' => 1,
            'vocabulary_id' => $materialsVocabulary->id,
            'exclude_domain_mapping' => false,
            'hyperlink' => '',
        ]);

        KeywordSearch::create([
            'keyword_id' => $sedimentaryRockKeyword->id,
            'search_value' => 'sedimentary rock',
            'isSynonym' => false,
            'exclude_abstract_mapping' => true,
            'version' => '1.3',
        ]);

        // add sandstone keyword and search keywords
        $sandstoneKeyword = Keyword::create([
            'parent_id' => $sedimentaryRockKeyword->id,
            'value' => 'sandstone',
            'uri' => 'https://epos-msl.uu.nl/voc/materials/1.3/sedimentary_rock-sandstone',
            'level' => 2,
            'vocabulary_id' => $materialsVocabulary->id,
            'exclude_domain_mapping' => false,
            'hyperlink' => '',
        ]);

        KeywordSearch::create([
            'keyword_id' => $sandstoneKeyword->id,
            'search_value' => 'sandstone',
            'isSynonym' => false,
            'exclude_abstract_mapping' => false,
            'version' => '1.3',
        ]);

        KeywordSearch::create([
            'keyword_id' => $sandstoneKeyword->id,
            'search_value' => 'sandstones',
            'isSynonym' => true,
            'exclude_abstract_mapping' => false,
            'version' => '1.3',
        ]);

        // add limestone keyword and search keywords
        $limestoneKeyword = Keyword::create([
            'parent_id' => $sedimentaryRockKeyword->id,
            'value' => 'limestone',
            'uri' => 'https://epos-msl.uu.nl/voc/materials/1.3/sedimentary_rock-limestone',
            'level' => 2,
            'vocabulary_id' => $materialsVocabulary->id,
            'exclude_domain_mapping' => false,
            'hyperlink' => '',
        ]);

        KeywordSearch::create([
            'keyword_id' => $limestoneKeyword->id,
            'search_value' => 'limestone',
            'isSynonym' => false,
            'exclude_abstract_mapping' => false,
            'version' => '1.3',
        ]);

        KeywordSearch::create([
            'keyword_id' => $limestoneKeyword->id,
            'search_value' => 'limestones',
            'isSynonym' => true,
            'exclude_abstract_mapping' => false,
            'version' => '1.3',
        ]);

        // add Anstrude limestone keyword and search keywords
        $anstrudelimestoneKeyword = Keyword::create([
            'parent_id' => $limestoneKeyword->id,
            'value' => 'Anstrude limestone',
            'uri' => 'https://epos-msl.uu.nl/voc/materials/1.3/sedimentary_rock-limestone-anstrude_limestone',
            'level' => 3,
            'vocabulary_id' => $materialsVocabulary->id,
            'exclude_domain_mapping' => false,
            'hyperlink' => '',
        ]);

        KeywordSearch::create([
            'keyword_id' => $anstrudelimestoneKeyword->id,
            'search_value' => 'anstrude limestone',
            'isSynonym' => false,
            'exclude_abstract_mapping' => false,
            'version' => '1.3',
        ]);

        $rockphysicsVocabulary = Vocabulary::create([
            'name' => 'rockphysics',
            'display_name' => 'Rock and melt physics',
            'version' => 1.3,
            'uri' => 'https://epos-msl.uu.nl/voc/rockphysics/1.3/',
        ]);

        // add inferreddeformationbehavior keyword and search keywords
        $inferreddeformationbehaviorKeyword = Keyword::create([
            'parent_id' => null,
            'value' => 'Inferred deformation behavior',
            'uri' => 'https://epos-msl.uu.nl/voc/rockphysics/1.3/inferred_deformation_behavior',
            'level' => 1,
            'vocabulary_id' => $rockphysicsVocabulary->id,
            'exclude_domain_mapping' => true,
            'hyperlink' => '',
        ]);

        KeywordSearch::create([
            'keyword_id' => $inferreddeformationbehaviorKeyword->id,
            'search_value' => 'inferred deformation behavior',
            'isSynonym' => false,
            'exclude_abstract_mapping' => true,
            'version' => '1.3',
        ]);

        // add microphysical deformation mechanism keyword and search keywords
        $microphysicaldeformationmechanismKeyword = Keyword::create([
            'parent_id' => $inferreddeformationbehaviorKeyword->id,
            'value' => 'microphysical deformation mechanism',
            'uri' => 'https://epos-msl.uu.nl/voc/rockphysics/1.3/inferred_deformation_behavior-microphysical_deformation_mechanism',
            'level' => 2,
            'vocabulary_id' => $rockphysicsVocabulary->id,
            'exclude_domain_mapping' => false,
            'hyperlink' => '',
        ]);

        KeywordSearch::create([
            'keyword_id' => $microphysicaldeformationmechanismKeyword->id,
            'search_value' => 'microphysical deformation mechanism',
            'isSynonym' => false,
            'exclude_abstract_mapping' => false,
            'version' => '1.3',
        ]);

        KeywordSearch::create([
            'keyword_id' => $microphysicaldeformationmechanismKeyword->id,
            'search_value' => 'microphysical process',
            'isSynonym' => true,
            'exclude_abstract_mapping' => false,
            'version' => '1.3',
        ]);

        KeywordSearch::create([
            'keyword_id' => $microphysicaldeformationmechanismKeyword->id,
            'search_value' => 'micro-physical',
            'isSynonym' => true,
            'exclude_abstract_mapping' => false,
            'version' => '1.3',
        ]);

        KeywordSearch::create([
            'keyword_id' => $microphysicaldeformationmechanismKeyword->id,
            'search_value' => 'microphysics',
            'isSynonym' => true,
            'exclude_abstract_mapping' => false,
            'version' => '1.3',
        ]);

        KeywordSearch::create([
            'keyword_id' => $microphysicaldeformationmechanismKeyword->id,
            'search_value' => 'deformation mechanism',
            'isSynonym' => true,
            'exclude_abstract_mapping' => false,
            'version' => '1.3',
        ]);

        KeywordSearch::create([
            'keyword_id' => $microphysicaldeformationmechanismKeyword->id,
            'search_value' => 'deformation mechanisms',
            'isSynonym' => true,
            'exclude_abstract_mapping' => false,
            'version' => '1.3',
        ]);

        // add intragranular cracking keyword and search keywords
        $intragranularcrackingKeyword = Keyword::create([
            'parent_id' => $microphysicaldeformationmechanismKeyword->id,
            'value' => 'intragranular cracking',
            'uri' => 'https://epos-msl.uu.nl/voc/rockphysics/1.3/inferred_deformation_behavior-microphysical_deformation_mechanism-intragranular_cracking',
            'level' => 3,
            'vocabulary_id' => $rockphysicsVocabulary->id,
            'exclude_domain_mapping' => false,
            'hyperlink' => '',
        ]);

        KeywordSearch::create([
            'keyword_id' => $intragranularcrackingKeyword->id,
            'search_value' => 'intragranular cracking',
            'isSynonym' => false,
            'exclude_abstract_mapping' => false,
            'version' => '1.3',
        ]);

        KeywordSearch::create([
            'keyword_id' => $intragranularcrackingKeyword->id,
            'search_value' => 'grain failure',
            'isSynonym' => true,
            'exclude_abstract_mapping' => false,
            'version' => '1.3',
        ]);

        KeywordSearch::create([
            'keyword_id' => $intragranularcrackingKeyword->id,
            'search_value' => 'intragranular failure',
            'isSynonym' => true,
            'exclude_abstract_mapping' => false,
            'version' => '1.3',
        ]);

        KeywordSearch::create([
            'keyword_id' => $intragranularcrackingKeyword->id,
            'search_value' => 'microcracking',
            'isSynonym' => true,
            'exclude_abstract_mapping' => false,
            'version' => '1.3',
        ]);

        KeywordSearch::create([
            'keyword_id' => $intragranularcrackingKeyword->id,
            'search_value' => 'micro-cracking',
            'isSynonym' => true,
            'exclude_abstract_mapping' => false,
            'version' => '1.3',
        ]);

        KeywordSearch::create([
            'keyword_id' => $intragranularcrackingKeyword->id,
            'search_value' => 'intragranular crack',
            'isSynonym' => true,
            'exclude_abstract_mapping' => false,
            'version' => '1.3',
        ]);

        KeywordSearch::create([
            'keyword_id' => $intragranularcrackingKeyword->id,
            'search_value' => 'crack',
            'isSynonym' => true,
            'exclude_abstract_mapping' => false,
            'version' => '1.3',
        ]);

        KeywordSearch::create([
            'keyword_id' => $intragranularcrackingKeyword->id,
            'search_value' => 'cracking',
            'isSynonym' => true,
            'exclude_abstract_mapping' => false,
            'version' => '1.3',
        ]);

        $microscopyVocabulary = Vocabulary::create([
            'name' => 'microscopy',
            'display_name' => 'Microscopy and tomography',
            'version' => 1.3,
            'uri' => 'https://epos-msl.uu.nl/voc/microscopy/1.3/',
        ]);

        // add Analyzed feature keyword and search keywords
        $analyzedfeatureKeyword = Keyword::create([
            'parent_id' => null,
            'value' => 'Analyzed feature',
            'uri' => 'https://epos-msl.uu.nl/voc/microscopy/1.3/analyzed_feature',
            'level' => 1,
            'vocabulary_id' => $microscopyVocabulary->id,
            'exclude_domain_mapping' => true,
            'hyperlink' => '',
        ]);

        KeywordSearch::create([
            'keyword_id' => $analyzedfeatureKeyword->id,
            'search_value' => 'analyzed feature',
            'isSynonym' => false,
            'exclude_abstract_mapping' => true,
            'version' => '1.3',
        ]);

        // add deformation microstructure keyword and search keywords
        $deformationmicrostructureKeyword = Keyword::create([
            'parent_id' => $analyzedfeatureKeyword->id,
            'value' => 'deformation microstructure',
            'uri' => 'https://epos-msl.uu.nl/voc/microscopy/1.3/analyzed_feature-deformation_microstructure',
            'level' => 2,
            'vocabulary_id' => $microscopyVocabulary->id,
            'exclude_domain_mapping' => false,
            'hyperlink' => '',
        ]);

        KeywordSearch::create([
            'keyword_id' => $deformationmicrostructureKeyword->id,
            'search_value' => 'deformation microstructure',
            'isSynonym' => false,
            'exclude_abstract_mapping' => false,
            'version' => '1.3',
        ]);

        // add brittle microstructure keyword and search keywords
        $brittlemicrostructureKeyword = Keyword::create([
            'parent_id' => $deformationmicrostructureKeyword->id,
            'value' => 'brittle microstructure',
            'uri' => 'https://epos-msl.uu.nl/voc/microscopy/1.3/analyzed_feature-deformation_microstructure-brittle_microstructure',
            'level' => 3,
            'vocabulary_id' => $microscopyVocabulary->id,
            'exclude_domain_mapping' => false,
            'hyperlink' => '',
        ]);

        KeywordSearch::create([
            'keyword_id' => $brittlemicrostructureKeyword->id,
            'search_value' => 'brittle microstructure',
            'isSynonym' => false,
            'exclude_abstract_mapping' => false,
            'version' => '1.3',
        ]);

        // add intragranular crack keyword and search keywords
        $intragranularcrackKeyword = Keyword::create([
            'parent_id' => $brittlemicrostructureKeyword->id,
            'value' => 'intragranular crack',
            'uri' => 'https://epos-msl.uu.nl/voc/microscopy/1.3/analyzed_feature-deformation_microstructure-brittle_microstructure-intragranular_crack',
            'level' => 4,
            'vocabulary_id' => $microscopyVocabulary->id,
            'exclude_domain_mapping' => false,
            'hyperlink' => '',
        ]);

        KeywordSearch::create([
            'keyword_id' => $intragranularcrackKeyword->id,
            'search_value' => 'intragranular cracking',
            'isSynonym' => false,
            'exclude_abstract_mapping' => false,
            'version' => '1.3',
        ]);

        KeywordSearch::create([
            'keyword_id' => $intragranularcrackKeyword->id,
            'search_value' => 'grain failure',
            'isSynonym' => true,
            'exclude_abstract_mapping' => false,
            'version' => '1.3',
        ]);

        KeywordSearch::create([
            'keyword_id' => $intragranularcrackKeyword->id,
            'search_value' => 'intragranular failure',
            'isSynonym' => true,
            'exclude_abstract_mapping' => false,
            'version' => '1.3',
        ]);

        KeywordSearch::create([
            'keyword_id' => $intragranularcrackKeyword->id,
            'search_value' => 'microcracking',
            'isSynonym' => true,
            'exclude_abstract_mapping' => false,
            'version' => '1.3',
        ]);

        KeywordSearch::create([
            'keyword_id' => $intragranularcrackKeyword->id,
            'search_value' => 'micro-cracking',
            'isSynonym' => true,
            'exclude_abstract_mapping' => false,
            'version' => '1.3',
        ]);

        KeywordSearch::create([
            'keyword_id' => $intragranularcrackKeyword->id,
            'search_value' => 'intragranular crack',
            'isSynonym' => true,
            'exclude_abstract_mapping' => false,
            'version' => '1.3',
        ]);

        KeywordSearch::create([
            'keyword_id' => $intragranularcrackKeyword->id,
            'search_value' => 'crack',
            'isSynonym' => true,
            'exclude_abstract_mapping' => false,
            'version' => '1.3',
        ]);

        KeywordSearch::create([
            'keyword_id' => $intragranularcrackKeyword->id,
            'search_value' => 'intragranular cracking',
            'isSynonym' => true,
            'exclude_abstract_mapping' => false,
            'version' => '1.3',
        ]);
    }

    /**
     * A basic unit test example.
     */
    public function test_map_tags_to_keywords_add_no_domain_tag(): void
    {
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
        $dataPublication = new DataPublication;
        $dataPublication->msl_description_abstract = 'This is about sandstone';

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
        $dataPublication = new DataPublication;
        $dataPublication->msl_description_abstract = 'This is about anstrude limestone';

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
