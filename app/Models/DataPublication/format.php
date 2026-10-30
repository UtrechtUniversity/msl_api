<?php

namespace App\Models\DataPublication;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class format extends Model
{
    use HasFactory;

    protected $fillable = [
        'format',
    ];

    public function dataPublication()
    {
        return $this->belongsTo(DataPublication::class);
    }
}
