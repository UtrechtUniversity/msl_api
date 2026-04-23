<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaboratoryUpdateGroupFast extends Model
{
    protected $table = 'laboratory_update_group_fast';

    public function laboratoryUpdatesFast()
    {
        return $this->hasMany(LaboratoryUpdateFast::class);
    }
}
