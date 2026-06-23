<?php

namespace App\Models\Laboratory;

use App\Mappers\Ckan\LaboratoryMapper;
use App\Models\LaboratoryUpdateFast;
use App\Scout\CkanSearchableInterface;
use App\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Laboratory extends Model implements CkanSearchableInterface
{
    use Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fast_id',
        'msl_identifier',
        'lab_portal_name',
        'lab_editor_name',
        'msl_identifier_inputstring',
        'original_domain',
        'name',
        'description',
        'description_html',
        'website',
        'address_street_1',
        'address_street_2',
        'address_postalcode',
        'address_city',
        'address_country_code',
        'address_country_name',
        'latitude',
        'longitude',
        'altitude',
        'external_identifier',
        'fast_domain_id',
        'fast_domain_name',
        'laboratory_organization_id',
        'laboratory_manager_id',
    ];

    protected static function booted(): void
    {
        static::deleting(function (Laboratory $laboratory) {
            foreach ($laboratory->laboratoryContactPersons()->get() as $contactPerson) {
                $contactPerson->delete();
            }

            foreach ($laboratory->laboratoryEquipment()->get() as $equipment) {
                $equipment->delete();
            }

            foreach ($laboratory->laboratoryKeywords()->get() as $keyword) {
                $keyword->delete();
            }
        });
    }

    public function laboratoryOrganization(): BelongsTo
    {
        return $this->belongsTo(LaboratoryOrganization::class, 'laboratory_organization_id');
    }

    public function laboratoryContactPersons(): HasMany
    {
        return $this->hasMany(LaboratoryContactPerson::class, 'laboratory_id');
    }

    public function laboratoryManager(): BelongsTo
    {
        return $this->belongsTo(LaboratoryManager::class, 'laboratory_manager_id');
    }

    public function laboratoryEquipment(): HasMany
    {
        return $this->hasMany(LaboratoryEquipment::class, 'laboratory_id');
    }

    public function laboratoryKeywords(): HasMany
    {
        return $this->hasMany(LaboratoryKeyword::class, 'laboratory_id');
    }

    public function laboratoryUpdatesFast(): HasMany
    {
        return $this->hasMany(LaboratoryUpdateFast::class, 'laboratory_id');
    }

    public function toSearchableArray(): array
    {
        return $this->toCkanArray();
    }

    public function getScoutKey(): mixed
    {
        return $this->id;
    }

    public function getScoutKeyName(): mixed
    {
        return 'id';
    }

    public function getCkanType(): string
    {
        return 'lab';
    }

    public function getCkanMapKeyName(): string
    {
        return 'name';
    }

    /**
     * Convert object to CKAN representation
     */
    public function toCkanArray(): array
    {
        return LaboratoryMapper::fromLaboratory($this);
    }


}
