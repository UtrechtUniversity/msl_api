<?php

namespace Tests\Feature\Models;

use App\Models\Vocabulary;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VocabularyTest extends TestCase
{
    use RefreshDatabase;

    public function test_keywords_relation(): void
    {
        $vocabulary = Vocabulary::create([
            'name' => 'Materials',
            'uri' => 'https://example.org/vocab/materials',
            'display_name' => 'Materials',
        ]);

        $keyword = $vocabulary->keywords()->create([
            'value' => 'granite',
            'uri' => 'https://example.org/vocab/materials/granite',
            'level' => 0,
            'hyperlink' => '',
            'label' => 'Granite',
        ]);

        $this->assertCount(1, $vocabulary->fresh()->keywords);
        $this->assertTrue($vocabulary->keywords->contains($keyword));
    }

    public function test_search_keywords_relation(): void
    {
        $vocabulary = Vocabulary::create([
            'name' => 'Materials',
            'uri' => 'https://example.org/vocab/materials',
            'display_name' => 'Materials',
        ]);

        $keyword = $vocabulary->keywords()->create([
            'value' => 'granite',
            'uri' => 'https://example.org/vocab/materials/granite',
            'level' => 0,
            'hyperlink' => '',
            'label' => 'Granite',
        ]);

        $search = $keyword->keywordSearch()->create([
            'search_value' => 'granite rock',
            'isSynonym' => false,
            'exclude_abstract_mapping' => false,
        ]);

        $vocabulary->load('searchKeywords');

        $this->assertTrue($vocabulary->searchKeywords->contains($search));
    }
}
