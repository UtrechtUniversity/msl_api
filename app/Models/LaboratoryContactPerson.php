<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class LaboratoryContactPerson extends Model
{
    protected $table = 'laboratory_contact_persons';
    
    protected $fillable = [
        'fast_id',
        'email',
        'first_name',
        'last_name',
        'orcid',
        'nationality_code',
        'address_street_1',
        'address_street_2',
        'address_postalcode',
        'address_city',
        'address_country_code',
        'address_country_name',
        'affiliation_fast_id',
        'nationality_code',
        'nationality_name'        
    ];
    
    public function laboratories() {
        return $this->hasMany(Laboratory::class);
    }

    /**
     * Determine if the data fails the validation rules.
     *
     * @return bool
     */
    public function hasValidEmail()
    {        
        $validator = Validator::make($this->toArray(), ['email' => ['required', 'email']]);        
        return !$validator->fails();
    }
}
