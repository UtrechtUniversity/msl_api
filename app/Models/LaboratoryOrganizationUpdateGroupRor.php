<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaboratoryOrganizationUpdateGroupRor extends Model
{
    protected $table = 'laboratory_organization_update_group_ror';

    public function laboratoryOrganizationUpdateRors()
    {
        return $this->hasMany(LaboratoryOrganizationUpdateRor::class);
    }
}
