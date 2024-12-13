<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaboratoryEquipmentAddon extends Model
{
    protected $fillable = [
        'description',
        'laboratory_equipment_id',
        'keyword_id',
        'type',
        'group'
    ];

    public function laboratory_equipment()
    {
        return $this->belongsTo(LaboratoryEquipment::class);
    }

    public function keyword()
    {
        return $this->belongsTo(Keyword::class);
    }
}