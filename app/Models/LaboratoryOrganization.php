<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LaboratoryOrganization extends Model
{
    protected $table = 'laboratory_organizations';

    protected $fillable = [
        'fast_id',
        'name',
        'external_identifier',
    ];

    public function laboratories(): HasMany
    {
        return $this->hasMany(Laboratory::class);
    }

    public function laboratoryOrganizationUpdateRors(): HasMany
    {
        return $this->hasMany(LaboratoryOrganizationUpdateRor::class);
    }
}
