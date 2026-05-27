<?php

namespace Tests\Feature\Models;

use App\Models\Keyword;
use App\Models\Vocabulary;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KeywordTest extends TestCase
{
    use RefreshDatabase;

    public function test_parent_relation(): void
    {
        $vocabulary = Vocabulary::create([
            'name' => 'Materials',
            'uri' => 'https://example.org/vocab/materials',
            'display_name' => 'Materials',
        ]);

        $parent = $vocabulary->keywords()->create([
            'value' => 'rock',
            'uri' => 'https://example.org/vocab/materials/rock',
            'level' => 0,
            'hyperlink' => '',
            'label' => 'Rock',
        ]);

        $child = $vocabulary->keywords()->create([
            'parent_id' => $parent->id,
            'value' => 'granite',
            'uri' => 'https://example.org/vocab/materials/granite',
            'level' => 1,
            'hyperlink' => '',
            'label' => 'Granite',
        ]);

        $this->assertInstanceOf(Keyword::class, $child->parent);
        $this->assertSame($parent->id, $child->parent->id);
    }

    public function test_vocabulary_relation(): void
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

        $this->assertInstanceOf(Vocabulary::class, $keyword->vocabulary);
        $this->assertSame($vocabulary->id, $keyword->vocabulary->id);
    }

    public function test_keyword_search_relation(): void
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
            'isSynonym' => true,
            'exclude_abstract_mapping' => false,
        ]);

        $this->assertCount(1, $keyword->fresh()->keywordSearch);
        $this->assertTrue($keyword->keywordSearch->contains($search));
        $this->assertInstanceOf(Keyword::class, $search->keyword);
        $this->assertSame($keyword->id, $search->keyword->id);
    }
}
