<?php

namespace App\Models;

use App\Models\Concerns\HasPostgisPoint;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class EvacuationPoint extends Model
{
    use HasPostgisPoint;

    public const STATUSES = ['aktif', 'penuh', 'tidak_aktif'];

    public const TYPES = ['sekolah', 'masjid', 'gedung_pemerintah', 'aula', 'lapangan', 'puskesmas'];

    public const DATA_STATUSES = ['nyata', 'dummy', 'simulasi'];

    public const SOURCE_TYPES = ['pemerintah', 'berita', 'jurnal', 'observasi', 'admin_input', 'dummy'];

    protected $fillable = [
        'name',
        'type',
        'address',
        'district',
        'subdistrict',
        'capacity',
        'facilities',
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
            'capacity' => 'integer',
            'is_verified' => 'boolean',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'aktif');
    }

    public function scopeByStatus(Builder $query, ?string $status): Builder
    {
        return $status ? $query->where('status', $status) : $query;
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
