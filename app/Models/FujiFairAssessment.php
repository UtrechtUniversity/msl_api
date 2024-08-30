<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FujiFairAssessment extends Model
{
    protected $fillable = [
        'group_identifier',
        'export_identifier',
        'doi',
    ];    
    
    protected $table = 'fuji_fair_assessments';
    
    public function getResponseBodyAsJson($pretty = false)
    {
        if($pretty) {
            return json_encode(json_decode($this->response_full), JSON_PRETTY_PRINT);
        } else {
            return json_encode(json_decode($this->response_full));
        }
    }
}
