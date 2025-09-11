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
        'label'
    ];
    
    protected $casts = [
        'exclude_domain_mapping' => 'boolean'        
    ];
 
    public function parent()
    {
        return $this->belongsTo(Keyword::class, 'parent_id');
    }
    
    public function hasParent()
    {
        if($this->parent_id) {
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
    
    public function getChildren($sort = true)
    {
        if($sort) {
            return Keyword::where('parent_id', $this->id)->orderBy('value', 'asc')->get();
        } else {
            return Keyword::where('parent_id', $this->id)->get();
        }                
    }
    
    public function getAncestors()
    {
        $parents = collect([]);
        $parent = $this->parent;
        
        while($parent) {
            $parents->push($parent);
            $parent = $parent->parent;
        }
        
        return $parents;
    }
    
    public function getFullPath($delimiter = '>', $includeVocabName = false)
    {
        $keywords = $this->getFullHierarchy();
        $parts = [];
        
        if($includeVocabName) {
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
    
    public function getSynonymString($startCharacter = '#')
    {
        $synonyms = $this->getSynonyms();
        $string = "";
        
        if($synonyms) {
            foreach ($synonyms as $synonym) {
                $string .= $startCharacter . $synonym->search_value;
            }
        }
        
        return $string;        
    }
    
}
