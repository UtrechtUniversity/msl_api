<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InferredDeformationBehaviorKeyword extends Model
{
    public $fillable = [
        'parent_id',
        'value',
        'searchvalue',
    ];

    public function parent()
    {
        return $this->belongsTo(InferredDeformationBehaviorKeyword::class, 'parent_id');
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

    public function getAncestorsValues()
    {
        $ancestors = $this->getAncestors();
        $values = [];

        foreach ($ancestors as $ancestor) {
            $values[] = $ancestor->value;
        }

        return $values;
    }
}
