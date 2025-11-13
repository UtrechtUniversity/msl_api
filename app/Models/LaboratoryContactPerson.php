<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class LaboratoryContactPerson extends Model
{
    protected $table = 'laboratory_contact_persons';

    protected $fillable = [
        'email',
        'laboratory_id',
    ];

    public function laboratory()
    {
        return $this->belongsTo(Laboratory::class);
    }

    /**
     * Determine if the data fails the validation rules.
     *
     * @return bool
     */
    public function hasValidEmail()
    {
        $validator = Validator::make($this->toArray(), ['email' => ['required', 'email']]);

        return ! $validator->fails();
    }
}
