<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaboratoryOrganization extends Model
{
    protected $table = 'laboratory_organizations';

    protected $fillable = [
        'fast_id',
        'name',
        'external_identifier',
    ];

    public function laboratories()
    {
        return $this->hasMany(Laboratory::class);
    }
}
