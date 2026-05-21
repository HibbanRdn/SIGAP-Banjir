<?php

namespace App\Models;

use App\Models\Concerns\HasPostgisPoint;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FloodRiskPoint extends Model
{
    use HasPostgisPoint;

    public const RISK_LEVELS = ['rendah', 'sedang', 'tinggi'];

    public const DATA_STATUSES = ['nyata', 'dummy', 'simulasi'];

    public const SOURCE_TYPES = ['pemerintah', 'berita', 'jurnal', 'observasi', 'admin_input', 'dummy'];

    protected $fillable = [
        'name',
        'address',
        'district',
        'subdistrict',
        'risk_level',
        'description',
        'source_type',
        'source_reference',
        'is_verified',
        'data_status',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'is_verified' => 'boolean',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeByRiskLevel(Builder $query, ?string $riskLevel): Builder
    {
        return $riskLevel ? $query->where('risk_level', $riskLevel) : $query;
    }

    public function scopeByDistrict(Builder $query, ?string $district): Builder
    {
        return $district ? $query->where('district', $district) : $query;
    }

    public function scopeByDataStatus(Builder $query, ?string $dataStatus): Builder
    {
        return $dataStatus ? $query->where('data_status', $dataStatus) : $query;
    }
}
