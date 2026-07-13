<?php

namespace App\Models\Laboratory;

use App\GeoJson\Feature\Feature;
use App\GeoJson\Geometry\Point;
use App\Mappers\Ckan\EquipmentMapper;
use App\Models\Keyword;
use App\Scout\CkanSearchableInterface;
use App\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class LaboratoryEquipment extends Model implements CkanSearchableInterface
{
    use Searchable;

    protected $touches = ['laboratory'];

    protected $table = 'laboratory_equipment';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fast_id',
        'laboratory_id',
        'description',
        'description_html',
        'category_name',
        'type_name',
        'domain_name',
        'group_name',
        'brand',
        'website',
        'latitude',
        'longitude',
        'altitude',
        'external_identifier',
        'name',
        'keyword_id',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($equipment) {
            $equipment->ckan_id = (string) Str::uuid();
        });
    }

    protected static function booted(): void
    {
        static::deleting(function (LaboratoryEquipment $equipment) {
            foreach ($equipment->laboratoryEquipmentAddons()->get() as $addon) {
                $addon->delete();
            }
        });
    }

    public function laboratory(): BelongsTo
    {
        return $this->belongsTo(Laboratory::class);
    }

    public function keyword(): BelongsTo
    {
        return $this->belongsTo(Keyword::class);
    }

    public function laboratoryEquipmentAddons(): HasMany
    {
        return $this->hasMany(LaboratoryEquipmentAddon::class, 'laboratory_equipment_id', 'id');
    }

    public function toSearchableArray(): array
    {
        return EquipmentMapper::fromEquipment($this);
    }

    public function getScoutKey(): mixed
    {
        return $this->ckan_id;
    }

    public function getScoutKeyName(): mixed
    {
        return 'ckan_id';
    }

    public function getScoutKeyType(): string
    {
        return 'string';
    }

    public function getCkanType(): string
    {
        return 'equipment';
    }

    public function getCkanMapKeyName(): string
    {
        return 'name';
    }

    /**
     * check if equipment or laboratory has spatial data
     */
    public function hasSpatialData(): bool
    {
        if ((strlen($this->latitude) > 0) && (strlen($this->longitude) > 0)) {
            return true;
        } elseif ($this->laboratory->hasSpatialData()) {
            return true;
        }

        return false;
    }

    /**
     * Get geojson feature object string. Uses laboratory location if no equipment
     * location is set.
     */
    public function getGeoJsonFeature(): string
    {
        if ($this->hasSpatialData()) {
            if ((strlen($this->latitude) > 0) && (strlen($this->longitude) > 0)) {
                return json_encode(
                    new Feature(
                        new Point((float) $this->longitude, (float) $this->latitude),
                        [
                            'title' => $this->name,
                            'name' => (string)$this->getScoutKey(),
                            'msl_id' => $this->id,
                            'msl_lab_ckan_name' => $this->laboratory->ckan_id,
                            'msl_lab_name' => $this->laboratory->name,
                            'msl_domain_name' => $this->domain_name,
                            'msl_group_name' => $this->group_name,
                            'msl_type_name' => $this->type_name,
                        ]
                    )
                );
            } else {
                return json_encode(
                    new Feature(
                        new Point((float) $this->laboratory->longitude, (float) $this->laboratory->latitude),
                        [
                            'title' => $this->name,
                            'name' => (string)$this->getScoutKey(),
                            'msl_id' => $this->id,
                            'msl_lab_ckan_name' => $this->laboratory->ckan_id,
                            'msl_lab_name' => $this->laboratory->name,
                            'msl_domain_name' => $this->domain_name,
                            'msl_group_name' => $this->group_name,
                            'msl_type_name' => $this->type_name,
                        ]
                    )
                );
            }
        }

        return '';
    }
}
