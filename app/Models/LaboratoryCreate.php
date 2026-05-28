<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaboratoryCreate extends Model
{
    protected $fillable = [
        'laboratory_type',
        'laboratory',
        'seed_id',
    ];

    protected $casts = [
        'laboratory' => 'array',
    ];

    public function seed(): BelongsTo
    {
        return $this->belongsTo(Seed::class);
    }

    public function getLaboratoryAsJson($pretty = false)
    {
        if ($pretty) {
            return json_encode($this->laboratory, JSON_PRETTY_PRINT);
        } else {
            return json_encode($this->laboratory);
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
