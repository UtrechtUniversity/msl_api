<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keyword extends Model
{
    public $fillable = [
        'parent_id',
        'value',
        'uri',
        'vocabulary_id',
        'level',
        'hyperlink',
        'exclude_domain_mapping',
        'label',
        'extracted_definition',
        'extracted_definition_link',
        'external_vocab_scheme',
        'external_description',
        'selection_group_1',
        'selection_group_2',
    ];

    protected $casts = [
        'exclude_domain_mapping' => 'boolean',
        'selection_group_1' => 'boolean',
        'selection_group_2' => 'boolean',
    ];

    public function parent()
    {
        return $this->belongsTo(Keyword::class, 'parent_id');
    }

    public function hasParent()
    {
        if ($this->parent_id) {
            return true;
        }

        return false;
    }

    public function vocabulary()
    {
        return $this->belongsTo(Vocabulary::class, 'vocabulary_id');
    }

    public function keyword_search()
    {
        return $this->hasMany(KeywordSearch::class, 'keyword_id');
    }

    public function getSynonyms()
    {
        return $this->hasMany(KeywordSearch::class, 'keyword_id')->where('isSynonym', '=', 1)->get();
    }

    public function getSynonymsExcludedAbstractMapping()
    {
        return $this->hasMany(KeywordSearch::class, 'keyword_id')->where(
            [
                'isSynonym' => 1,
                'exclude_abstract_mapping' => 1,
            ]
        )->get();
    }

    public function getChildren($sort = true)
    {
        if ($sort) {
            return Keyword::where('parent_id', $this->id)->orderBy('value', 'asc')->get();
        } else {
            return Keyword::where('parent_id', $this->id)->get();
        }
    }

    public function getAncestors()
    {
        $parents = collect([]);
        $parent = $this->parent;

        while ($parent) {
            $parents->push($parent);
            $parent = $parent->parent;
        }

        return $parents;
    }

    public function getFullPath($delimiter = '>', $includeVocabName = false)
    {
        $keywords = $this->getFullHierarchy();
        $parts = [];

        if ($includeVocabName) {
            $parts[] = $this->vocabulary->name;
        }

        foreach ($keywords as $keyword) {
            $parts[] = $keyword->value;
        }

        return implode($delimiter, $parts);
    }

    public function getFullHierarchy()
    {
        $ancestors = $this->getAncestors();
        $ancestors->prepend($this);
        $ancestors = $ancestors->reverse();

        return $ancestors;
    }

    public function getAncestorsValues()
    {
        $ancestors = $this->getAncestors();
        $values = [];

        foreach ($ancestors as $ancestor) {
            $values[] = $ancestor->value;
        }

        return $values;
    }

    /**
     * @param  bool  $getSynonymsExcludedAbstractMapping
     *boolean to locate the type of synonyms from table keyword_search
     * @return string
     */
    public function getSynonymString(bool $excludedAbstractMappingSynonyms = false, $startCharacter = '#')
    {
        $synonyms = $excludedAbstractMappingSynonyms ? $this->getSynonymsExcludedAbstractMapping() : $this->getSynonyms();
        $string = '';

        if ($synonyms) {
            foreach ($synonyms as $synonym) {
                $string .= $startCharacter.$synonym->search_value;
            }
        }

        return $string;
    }

    public function getExcludedSearchKeywordsString($groupNumber, $startCharacter = '#')
    {
        if (! ($groupNumber == 1 || $groupNumber == 2)) {
            throw new \Exception('Group number not within bounds: 1 or 2');
        }

        $groupColumn = 'exclude_selection_group_'.$groupNumber;
        $string = '';

        $synonyms = $this->hasMany(KeywordSearch::class, 'keyword_id')->where($groupColumn, '=', 1)->get();
        if ($synonyms) {
            foreach ($synonyms as $synonym) {
                $string .= $startCharacter.$synonym->search_value;
            }
        }

        return $string;
    }
}
