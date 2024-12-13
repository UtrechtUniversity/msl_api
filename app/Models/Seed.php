<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seed extends Model
{
    protected $fillable = [
        'seeder_id'        
    ];    
    
    public function seeder() 
    {
        return $this->belongsTo(Seeder::class);
    }
    
    public function creates()
    {
        if($this->seeder->type == "organization") {
            return $this->hasMany(OrganizationCreate::class);
        } elseif($this->seeder->type == "lab") {
            return $this->hasMany(LaboratoryCreate::class);
        } elseif($this->seeder->type == "equipment") {
            return $this->hasMany(EquipmentCreate::class);
        }
        
        throw new \Exception('Invalid Seeder configuration.');
    }
       
}
