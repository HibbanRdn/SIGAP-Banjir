<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HeavyEquipmentUnit extends Model
{
    public const STATUSES = ['tersedia', 'digunakan', 'perawatan', 'tidak_aktif'];

    protected $fillable = [
        'post_id',
        'equipment_type_id',
        'quantity',
        'available_quantity',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'available_quantity' => 'integer',
        ];
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(HeavyEquipmentPost::class, 'post_id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(EquipmentType::class, 'equipment_type_id');
    }

    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('available_quantity', '>', 0)
            ->where('status', 'tersedia');
    }

    public function scopeByStatus(Builder $query, ?string $status): Builder
    {
        return $status ? $query->where('status', $status) : $query;
    }
}
