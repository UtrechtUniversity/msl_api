<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaboratoryEquipmentAddon extends Model
{
    protected $fillable = [
        'description',
        'laboratory_equipment_id',
        'keyword_id',
        'type',
        'group',
    ];

    public function laboratoryEquipment(): BelongsTo
    {
        return $this->belongsTo(LaboratoryEquipment::class);
    }

    public function keyword(): BelongsTo
    {
        return $this->belongsTo(Keyword::class);
    }
}
