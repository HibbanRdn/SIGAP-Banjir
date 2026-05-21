<?php

namespace App\Models;

use App\Models\Concerns\HasPostgisPoint;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FloodEvent extends Model
{
    use HasPostgisPoint;

    public const STATUSES = ['aktif', 'surut', 'ditangani', 'arsip'];

    public const SEVERITY_LEVELS = ['rendah', 'sedang', 'tinggi', 'kritis'];

    public const DATA_STATUSES = ['nyata', 'dummy', 'simulasi'];

    public const SOURCE_TYPES = ['pemerintah', 'berita', 'jurnal', 'observasi', 'admin_input', 'dummy'];

    protected $fillable = [
        'name',
        'address',
        'district',
        'subdistrict',
        'severity_level',
        'water_depth_cm',
        'status',
        'description',
        'source_type',
        'source_reference',
        'occurred_at',
        'reported_at',
        'is_verified',
        'data_status',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'water_depth_cm' => 'integer',
            'occurred_at' => 'datetime',
            'reported_at' => 'datetime',
            'is_verified' => 'boolean',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'aktif');
    }

    public function scopeByStatus(Builder $query, ?string $status): Builder
    {
        return $status ? $query->where('status', $status) : $query;
    }

    public function scopeBySeverity(Builder $query, ?string $severity): Builder
    {
        return $severity ? $query->where('severity_level', $severity) : $query;
    }

    public function scopeByDistrict(Builder $query, ?string $district): Builder
    {
        return $district ? $query->where('district', $district) : $query;
    }

    public function scopeByDataStatus(Builder $query, ?string $dataStatus): Builder
    {
        return $dataStatus ? $query->where('data_status', $dataStatus) : $query;
    }

    public function scopeLatestReported(Builder $query): Builder
    {
        return $query->orderByDesc('reported_at');
    }
}
