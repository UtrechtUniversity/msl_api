<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizationCreate extends Model
{
    protected $fillable = [
        'organization_type',
        'organization',
        'seed_id',
    ];

    protected $casts = [
        'organization' => 'array',
    ];

    public function seed()
    {
        return $this->belongsTo(Seed::class);
    }

    public function getOrganizationAsJson($pretty = false)
    {
        if ($pretty) {
            return json_encode($this->organization, JSON_PRETTY_PRINT);
        } else {
            return json_encode($this->organization);
        }
    }

    public function getResponseBodyAsJson($pretty = false)
    {
        if ($pretty) {
            return json_encode(json_decode($this->response_body), JSON_PRETTY_PRINT);
        } else {
            return json_encode(json_decode($this->response_body));
        }
    }
}
