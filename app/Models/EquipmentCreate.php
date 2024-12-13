<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EquipmentCreate extends Model
{
    
    protected $fillable = [
        'equipment_type',
        'equipment',
        'seed_id'
    ];
    
    protected $casts = [
        'equipment' => 'array'
    ];

    protected $table = 'equipment_creates';

    public function seed()
    {
        return $this->belongsTo(Seed::class);
    }
    
    public function getEquipmentAsJson($pretty = false)
    {
        if($pretty) {
            return json_encode($this->equipment, JSON_PRETTY_PRINT);
        } else {
            return json_encode($this->equipment);
        }
    }
    
    public function getResponseBodyAsJson($pretty = false)
    {
        if($pretty) {
            return json_encode(json_decode($this->response_body), JSON_PRETTY_PRINT);
        } else {
            return json_encode(json_decode($this->response_body));
        }
    }
    
}
