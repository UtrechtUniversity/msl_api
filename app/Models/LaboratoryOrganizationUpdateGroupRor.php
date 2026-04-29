<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LaboratoryOrganizationUpdateGroupRor extends Model
{
    protected $table = 'laboratory_organization_update_group_ror';

    public function laboratoryOrganizationUpdateRors(): HasMany
    {
        return $this->hasMany(LaboratoryOrganizationUpdateRor::class);
    }
}
