<?php

namespace App\Models;

use App\Models\Concerns\HasPostgisPoint;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HeavyEquipmentPost extends Model
{
    use HasPostgisPoint;

    public const STATUSES = ['aktif', 'tidak_aktif'];

    public const DATA_STATUSES = ['nyata', 'dummy', 'simulasi'];

    public const SOURCE_TYPES = ['pemerintah', 'berita', 'jurnal', 'observasi', 'admin_input', 'dummy'];

    protected $fillable = [
        'name',
        'address',
        'district',
        'subdistrict',
        'contact_person',
        'contact_phone',
        'status',
        'description',
        'source_type',
        'source_reference',
        'is_verified',
        'data_status',
    ];

    protected function casts(): array
    {
        return [
            'is_verified' => 'boolean',
        ];
    }

    public function units(): HasMany
    {
        return $this->hasMany(HeavyEquipmentUnit::class, 'post_id');
    }

    public function availableUnits(): HasMany
    {
        return $this->hasMany(HeavyEquipmentUnit::class, 'post_id')->available();
    }

    public function equipmentTypes(): BelongsToMany
    {
        return $this->belongsToMany(EquipmentType::class, 'heavy_equipment_units', 'post_id', 'equipment_type_id')
            ->withPivot(['quantity', 'available_quantity', 'status', 'notes'])
            ->withTimestamps();
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'aktif');
    }

    public function scopeByDistrict(Builder $query, ?string $district): Builder
    {
        return $district ? $query->where('district', $district) : $query;
    }

    public function scopeWithAvailableUnits(Builder $query): Builder
    {
        return $query->whereHas('units', fn (Builder $query): Builder => $query->available());
    }
}
