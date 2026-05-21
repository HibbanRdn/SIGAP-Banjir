<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EquipmentType extends Model
{
    public const NAMES = [
        'excavator',
        'dump_truck',
        'wheel_loader',
        'pompa_air',
        'mobil_tangki',
        'pickup_operasional',
    ];

    protected $fillable = [
        'name',
        'description',
    ];

    public function units(): HasMany
    {
        return $this->hasMany(HeavyEquipmentUnit::class, 'equipment_type_id');
    }

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(HeavyEquipmentPost::class, 'heavy_equipment_units', 'equipment_type_id', 'post_id')
            ->withPivot(['quantity', 'available_quantity', 'status', 'notes'])
            ->withTimestamps();
    }
}
