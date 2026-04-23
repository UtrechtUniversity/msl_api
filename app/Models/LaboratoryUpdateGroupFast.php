<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LaboratoryUpdateGroupFast extends Model
{
    protected $table = 'laboratory_update_group_fast';

    public function laboratoryUpdatesFast(): HasMany
    {
        return $this->hasMany(LaboratoryUpdateFast::class);
    }
}
