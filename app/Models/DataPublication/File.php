<?php

namespace App\Models\DataPublication;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_name',
        'download_url',
        'extension',
        'type',
        'origin_timestamp_created',
        'origin_timestamp_updated',
        'origin_full_path',
        'checksum',
        'size',
    ];

    protected $casts = [
        'origin_timestamp_created' => 'datetime',
        'origin_timestamp_updated' => 'datetime',
    ];

    public function dataPublication(): BelongsTo
    {
        return $this->belongsTo(DataPublication::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(File::class, 'parent_id');
    }
}
