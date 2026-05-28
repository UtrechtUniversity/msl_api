<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaboratoryOrganizationUpdateRor extends Model
{
    protected $fillable = [
        'laboratory_organization_update_group_ror_id',
        'laboratory_organization_id',
        'response_code',
        'source_organization_data',
    ];

    protected $table = 'laboratory_organization_update_ror';

    public function laboratoryOrganizationUpdateGroupRor(): BelongsTo
    {
        return $this->belongsTo(LaboratoryOrganizationUpdateGroupRor::class);
    }

    public function laboratoryOrganization(): BelongsTo
    {
        return $this->belongsTo(LaboratoryOrganization::class);
    }
}
