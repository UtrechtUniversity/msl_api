<?php

namespace Tests\Unit;

use App\Models\Ckan\DataPublication;
use App\Models\Ckan\EnrichedKeyword;
use App\Models\Ckan\OriginalKeyword;
use App\Models\Ckan\Tag;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertContains;
use function PHPUnit\Framework\assertEquals;

class DataPublicationTest extends TestCase
{
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
        assertContains('test/uri', $dataPublication->msl_tags[0]->msl_tag_msl_uris);

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
}
