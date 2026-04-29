<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Seed extends Model
{
    protected $fillable = [
        'seeder_id',
    ];

    public function seeder(): BelongsTo
    {
        return $this->belongsTo(Seeder::class);
    }

    public function creates(): HasMany
    {
        if ($this->seeder->type == 'organization') {
            return $this->hasMany(OrganizationCreate::class);
        } elseif ($this->seeder->type == 'lab') {
            return $this->hasMany(LaboratoryCreate::class);
        } elseif ($this->seeder->type == 'equipment') {
            return $this->hasMany(EquipmentCreate::class);
        }

        throw new \Exception('Invalid Seeder configuration.');
    }
}
