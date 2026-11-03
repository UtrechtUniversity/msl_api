<?php

namespace App\Models\DataPublication;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FundingReference extends Model
{
    use HasFactory;

    protected $fillable = [
        'funder_name',
        'funder_identifier',
        'funder_identifier_type',
        'scheme_uri',
        'award_number',
        'award_uri',
        'award_title',
    ];

    public function dataPublication(): BelongsTo
    {
        return $this->belongsTo(DataPublication::class);
    }
}
