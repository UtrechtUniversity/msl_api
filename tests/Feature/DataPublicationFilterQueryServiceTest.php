<?php

namespace Tests\Feature;

use App\Models\Vocabulary;
use App\Services\DataPublicationFilterQueryService;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class DataPublicationFilterQueryServiceTest extends TestCase
{
    public function testQueryTerms()
    {
        Config::set('vocabularies.vocabularies_current_version', '1.0');

        $vocabulary = Vocabulary::create([
            'name' => 'testVocab',
            'uri' => 'test',
            'version' => '1.0',
        ]);

        $keyword1 = $vocabulary->keywords()->create([
            'value' => 'testKeyword1',
            'hyperlink' => 'test',
            'uri' => 'test',
            'level' => 1,
            'exclude_abstract_mapping' => false,
            'selection_group_1' => true,
            'selection_group_2' => false,
            'selection_group_3' => false,
        ]);

        $keyword1->keyword_search()->create([
            'search_value' => 'search1',
            'isSynonym' => true,
            'exclude_abstract_mapping' => false,
        ]);

        $keyword1->keyword_search()->create([
            'search_value' => 'search2',
            'isSynonym' => true,
            'exclude_abstract_mapping' => false,
        ]);

        $keyword2 = $vocabulary->keywords()->create([
            'value' => 'testKeyword2',
            'hyperlink' => 'test',
            'uri' => 'test',
            'level' => 1,
            'exclude_abstract_mapping' => false,
            'selection_group_1' => true,
            'selection_group_2' => false,
            'selection_group_3' => false,
        ]);

        $keyword2->keyword_search()->create([
            'search_value' => 'search3',
            'isSynonym' => true,
            'exclude_abstract_mapping' => false,
        ]);

        $queryService = new DataPublicationFilterQueryService();
        $queryTerms = $queryService->getQueryTerms('selection_group_1');

        $this->assertCount(3, $queryTerms);
        $this->assertContains("\"\\\\bsearch1\\\\b\"", $queryTerms);
    }
}
