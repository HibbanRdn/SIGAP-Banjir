<?php

namespace App\Services;

use App\Models\FloodEvent;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use JsonException;
use RuntimeException;

class DistrictFloodIntensityService
{
    private const BOUNDARY_PATH = 'geojson/bandar-lampung-districts.geojson';

    private const BOUNDARY_SOURCE = 'BNPB ArcGIS REST Services - Basemap batas_administrasi, layer Batas Kecamatan';

    private const BOUNDARY_SOURCE_URL = 'https://gis.bnpb.go.id/server/rest/services/Basemap/batas_administrasi/MapServer/3';

    private const DISTRICT_NAME_ALIASES = [
        'TANJUNGKARANGBARAT' => 'Tanjung Karang Barat',
        'TANJUNGKARANGPUSAT' => 'Tanjung Karang Pusat',
        'TANJUNGKARANGTIMUR' => 'Tanjung Karang Timur',
        'TELUKBETUNGBARAT' => 'Teluk Betung Barat',
        'TELUKBETUNGSELATAN' => 'Teluk Betung Selatan',
        'TELUKBETUNGTIMUR' => 'Teluk Betung Timur',
        'TELUKBETUNGUTARA' => 'Teluk Betung Utara',
    ];

    /**
     * @return array<string, mixed>
     *
     * @throws JsonException
     */
    public function featureCollection(): array
    {
        $boundary = $this->loadBoundary();
        $eventSummary = $this->eventSummaryByDistrict();

        $features = collect($boundary['features'] ?? [])
            ->map(function (array $feature) use ($eventSummary): array {
                $sourceProperties = $feature['properties'] ?? [];
                $sourceDistrict = (string) ($sourceProperties['NAMA_KEC'] ?? $sourceProperties['district'] ?? '');
                $district = $this->canonicalDistrictName($sourceDistrict);
                $summary = $eventSummary[$this->normalizeDistrictKey($district)] ?? [
                    'total_events' => 0,
                    'active_events' => 0,
                    'critical_active_events' => 0,
                ];
                $classification = $this->classifyIntensity((int) $summary['total_events']);

                return [
                    'type' => 'Feature',
                    'geometry' => $feature['geometry'] ?? null,
                    'properties' => [
                        'district' => $district,
                        'source_district_name' => $sourceDistrict,
                        'district_code' => $sourceProperties['NO_KEC'] ?? null,
                        'city' => $this->canonicalCityName((string) ($sourceProperties['NAMA_KAB'] ?? 'Kota Bandar Lampung')),
                        'province' => $this->canonicalProvinceName((string) ($sourceProperties['NAMA_PROP'] ?? 'Lampung')),
                        'total_events' => (int) $summary['total_events'],
                        'active_events' => (int) $summary['active_events'],
                        'critical_active_events' => (int) $summary['critical_active_events'],
                        'intensity_level' => $classification['level'],
                        'intensity_label' => $classification['label'],
                        'intensity_range' => $classification['range'],
                        'color_key' => $classification['color_key'],
                    ],
                ];
            })
            ->values()
            ->all();

        return [
            'type' => 'FeatureCollection',
            'metadata' => [
                'boundary_source' => self::BOUNDARY_SOURCE,
                'boundary_source_url' => self::BOUNDARY_SOURCE_URL,
                'classification' => [
                    ['level' => 'none', 'label' => 'Tidak ada kejadian', 'range' => '0'],
                    ['level' => 'low', 'label' => 'Rendah', 'range' => '1-4'],
                    ['level' => 'medium', 'label' => 'Sedang', 'range' => '5-7'],
                    ['level' => 'high', 'label' => 'Tinggi', 'range' => '8+'],
                ],
            ],
            'features' => $features,
        ];
    }

    /**
     * @return array<string, mixed>
     *
     * @throws JsonException
     */
    private function loadBoundary(): array
    {
        if (! Storage::disk('public')->exists(self::BOUNDARY_PATH)) {
            throw new RuntimeException('File batas kecamatan Bandar Lampung tidak ditemukan.');
        }

        $content = Storage::disk('public')->get(self::BOUNDARY_PATH);
        $boundary = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

        if (($boundary['type'] ?? null) !== 'FeatureCollection' || ! is_array($boundary['features'] ?? null)) {
            throw new RuntimeException('File batas kecamatan Bandar Lampung tidak valid.');
        }

        return $boundary;
    }

    /**
     * @return array<string, array{total_events: int, active_events: int, critical_active_events: int}>
     */
    private function eventSummaryByDistrict(): array
    {
        /** @var Collection<int, object{district: string|null, total_events: int|string, active_events: int|string, critical_active_events: int|string}> $rows */
        $rows = FloodEvent::query()
            ->whereNotNull('district')
            ->select('district')
            ->selectRaw('COUNT(*) AS total_events')
            ->selectRaw("SUM(CASE WHEN status = 'aktif' THEN 1 ELSE 0 END) AS active_events")
            ->selectRaw("SUM(CASE WHEN status = 'aktif' AND severity_level = 'kritis' THEN 1 ELSE 0 END) AS critical_active_events")
            ->groupBy('district')
            ->get();

        $summary = [];

        foreach ($rows as $row) {
            $key = $this->normalizeDistrictKey((string) $row->district);

            if ($key === '') {
                continue;
            }

            $summary[$key] ??= [
                'total_events' => 0,
                'active_events' => 0,
                'critical_active_events' => 0,
            ];

            $summary[$key]['total_events'] += (int) $row->total_events;
            $summary[$key]['active_events'] += (int) $row->active_events;
            $summary[$key]['critical_active_events'] += (int) $row->critical_active_events;
        }

        return $summary;
    }

    /**
     * @return array{level: string, label: string, range: string, color_key: string}
     */
    private function classifyIntensity(int $totalEvents): array
    {
        if ($totalEvents <= 0) {
            return ['level' => 'none', 'label' => 'Tidak ada kejadian', 'range' => '0', 'color_key' => 'none'];
        }

        if ($totalEvents < 5) {
            return ['level' => 'low', 'label' => 'Rendah', 'range' => '1-4', 'color_key' => 'low'];
        }

        if ($totalEvents < 8) {
            return ['level' => 'medium', 'label' => 'Sedang', 'range' => '5-7', 'color_key' => 'medium'];
        }

        return ['level' => 'high', 'label' => 'Tinggi', 'range' => '8+', 'color_key' => 'high'];
    }

    private function canonicalDistrictName(string $name): string
    {
        $key = $this->normalizeDistrictKey($name);

        if (isset(self::DISTRICT_NAME_ALIASES[$key])) {
            return self::DISTRICT_NAME_ALIASES[$key];
        }

        return Str::of($name)
            ->replace('_', ' ')
            ->lower()
            ->title()
            ->trim()
            ->toString();
    }

    private function canonicalCityName(string $name): string
    {
        return Str::of($name)
            ->replace('KOTA ', 'Kota ')
            ->lower()
            ->title()
            ->trim()
            ->toString();
    }

    private function canonicalProvinceName(string $name): string
    {
        return Str::of($name)->lower()->title()->trim()->toString();
    }

    private function normalizeDistrictKey(string $name): string
    {
        return preg_replace('/[^A-Z0-9]/', '', Str::upper($name)) ?? '';
    }
}
