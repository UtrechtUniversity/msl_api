<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaboratoryUpdateFast extends Model
{
 
    protected $fillable = [
        'laboratory_update_group_fast_id',
        'laboratory_id',
        'response_code',
        'source_laboratory_data'
    ];
    
    protected $table = 'laboratory_update_fast';
    
    public function laboratoryUpdateGroupFast()
    {
        return $this->belongsTo(LaboratoryUpdateGroupFast::class);
    }
}
