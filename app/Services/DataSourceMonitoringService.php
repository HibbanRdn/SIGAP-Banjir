<?php

namespace App\Services;

use App\Models\EvacuationPoint;
use App\Models\FloodEvent;
use App\Models\FloodRiskPoint;
use App\Models\HeavyEquipmentPost;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class DataSourceMonitoringService
{
    private const PER_PAGE = 15;

    /**
     * @return array<string, mixed>
     */
    public function summary(array $filters = []): array
    {
        $filters = $this->normalizeFilters($filters);
        $records = $this->filteredRecords($filters);

        return [
            'stats' => $this->stats(),
            'moduleCoverage' => $this->moduleCoverage(),
            'records' => $this->paginate($records),
            'filters' => $filters,
            'options' => $this->filterOptions(),
            'hasFilters' => $this->hasFilters($filters),
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    private function datasets(): array
    {
        return [
            'flood_events' => [
                'model' => FloodEvent::class,
                'label' => 'Kejadian Banjir',
                'short_label' => 'Kejadian',
                'routes' => [
                    'show' => 'admin.flood-events.show',
                    'edit' => 'admin.flood-events.edit',
                ],
            ],
            'flood_risk_points' => [
                'model' => FloodRiskPoint::class,
                'label' => 'Titik Rawan Banjir',
                'short_label' => 'Rawan',
                'routes' => [
                    'show' => 'admin.flood-risks.show',
                    'edit' => 'admin.flood-risks.edit',
                ],
            ],
            'evacuation_points' => [
                'model' => EvacuationPoint::class,
                'label' => 'Titik Evakuasi',
                'short_label' => 'Evakuasi',
                'routes' => [
                    'show' => 'admin.evacuation-points.show',
                    'edit' => 'admin.evacuation-points.edit',
                ],
            ],
            'heavy_equipment_posts' => [
                'model' => HeavyEquipmentPost::class,
                'label' => 'Pos Alat Berat',
                'short_label' => 'Alat Berat',
                'routes' => [
                    'show' => 'admin.heavy-equipment-posts.show',
                    'edit' => 'admin.heavy-equipment-posts.edit',
                ],
            ],
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function stats(): array
    {
        $dataStatusTotals = [
            'nyata' => 0,
            'simulasi' => 0,
            'dummy' => 0,
        ];
        $total = 0;
        $unverified = 0;

        foreach ($this->datasets() as $config) {
            /** @var class-string<Model> $model */
            $model = $config['model'];
            $total += (int) $model::query()->count();
            $unverified += (int) $model::query()->where('is_verified', false)->count();

            $counts = $model::query()
                ->selectRaw('data_status, COUNT(*) AS total')
                ->groupBy('data_status')
                ->pluck('total', 'data_status');

            foreach (array_keys($dataStatusTotals) as $status) {
                $dataStatusTotals[$status] += (int) ($counts[$status] ?? 0);
            }
        }

        return [
            [
                'label' => 'Total Data Spasial',
                'value' => $total,
                'hint' => 'Gabungan empat tabel spasial utama',
                'tone' => 'border-slate-200 bg-white text-slate-700',
            ],
            [
                'label' => 'Data Simulasi',
                'value' => $dataStatusTotals['simulasi'],
                'hint' => 'Skenario demo akademik',
                'tone' => 'border-blue-100 bg-blue-50 text-blue-700',
            ],
            [
                'label' => 'Data Dummy',
                'value' => $dataStatusTotals['dummy'],
                'hint' => 'Data realistis non-resmi',
                'tone' => 'border-yellow-100 bg-yellow-50 text-yellow-700',
            ],
            [
                'label' => 'Data Nyata',
                'value' => $dataStatusTotals['nyata'],
                'hint' => 'Data dengan sumber nyata',
                'tone' => 'border-emerald-100 bg-emerald-50 text-emerald-700',
            ],
            [
                'label' => 'Perlu Validasi',
                'value' => $unverified,
                'hint' => 'Record dengan is_verified = false',
                'tone' => 'border-amber-100 bg-amber-50 text-amber-700',
            ],
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function moduleCoverage(): array
    {
        return collect($this->datasets())
            ->map(function (array $config, string $module): array {
                /** @var class-string<Model> $model */
                $model = $config['model'];
                $total = (int) $model::query()->count();
                $unverified = (int) $model::query()->where('is_verified', false)->count();
                $verified = max(0, $total - $unverified);

                return [
                    'module' => $module,
                    'label' => $config['label'],
                    'total' => $total,
                    'unverified' => $unverified,
                    'verified' => $verified,
                    'verified_percentage' => $total > 0 ? (int) round(($verified / $total) * 100) : 0,
                ];
            })
            ->values()
            ->all();
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    private function filteredRecords(array $filters): Collection
    {
        $records = collect();

        foreach ($this->datasets() as $module => $config) {
            if ($filters['module'] !== '' && $filters['module'] !== $module) {
                continue;
            }

            /** @var class-string<Model> $model */
            $model = $config['model'];
            $query = $model::query()
                ->select([
                    'id',
                    'name',
                    'district',
                    'subdistrict',
                    'source_type',
                    'source_reference',
                    'data_status',
                    'is_verified',
                    'updated_at',
                ]);

            $this->applyFilters($query, $filters);

            $modelRecords = $query
                ->orderByDesc('updated_at')
                ->get()
                ->map(fn (Model $record): array => $this->normalizeRecord($record, $module, $config));

            $records = $records->merge($modelRecords);
        }

        return $records
            ->sort(function (array $left, array $right): int {
                if ($left['is_verified'] !== $right['is_verified']) {
                    return $left['is_verified'] <=> $right['is_verified'];
                }

                return $right['updated_at_timestamp'] <=> $left['updated_at_timestamp'];
            })
            ->values();
    }

    private function applyFilters(Builder $query, array $filters): void
    {
        if ($filters['search'] !== '') {
            $keyword = '%'.$filters['search'].'%';

            $query->where(function (Builder $query) use ($keyword): void {
                $query
                    ->where('name', 'ilike', $keyword)
                    ->orWhere('district', 'ilike', $keyword)
                    ->orWhere('subdistrict', 'ilike', $keyword)
                    ->orWhere('source_reference', 'ilike', $keyword);
            });
        }

        if ($filters['data_status'] !== '') {
            $query->where('data_status', $filters['data_status']);
        }

        if ($filters['source_type'] !== '') {
            $query->where('source_type', $filters['source_type']);
        }

        if ($filters['district'] !== '') {
            $query->where('district', $filters['district']);
        }

        if ($filters['verification'] === 'verified') {
            $query->where('is_verified', true);
        }

        if ($filters['verification'] === 'unverified') {
            $query->where('is_verified', false);
        }
    }

    /**
     * @param array<string, mixed> $config
     * @return array<string, mixed>
     */
    private function normalizeRecord(Model $record, string $module, array $config): array
    {
        $sourceReference = (string) ($record->source_reference ?? '');

        return [
            'module' => $module,
            'module_label' => $config['label'],
            'module_short_label' => $config['short_label'],
            'id' => $record->getKey(),
            'name' => $record->name,
            'district' => $record->district,
            'subdistrict' => $record->subdistrict,
            'source_type' => $record->source_type,
            'source_label' => $this->readableSourceType((string) $record->source_type),
            'source_reference' => $sourceReference,
            'source_url' => filter_var($sourceReference, FILTER_VALIDATE_URL) ? $sourceReference : null,
            'data_status' => $record->data_status,
            'data_status_label' => $this->readableLabel((string) $record->data_status),
            'is_verified' => (bool) $record->is_verified,
            'updated_at' => $record->updated_at,
            'updated_at_timestamp' => $record->updated_at?->getTimestamp() ?? 0,
            'detail_url' => route($config['routes']['show'], $record),
            'edit_url' => route($config['routes']['edit'], $record),
        ];
    }

    /**
     * @param Collection<int, array<string, mixed>> $records
     */
    private function paginate(Collection $records): LengthAwarePaginator
    {
        $page = max(1, (int) request('page', 1));

        return new LengthAwarePaginator(
            $records->forPage($page, self::PER_PAGE)->values(),
            $records->count(),
            self::PER_PAGE,
            $page,
            [
                'path' => LengthAwarePaginator::resolveCurrentPath(),
                'query' => request()->query(),
            ],
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function filterOptions(): array
    {
        return [
            'modules' => collect($this->datasets())
                ->map(fn (array $config, string $module): array => [
                    'value' => $module,
                    'label' => $config['label'],
                ])
                ->values()
                ->all(),
            'data_statuses' => [
                ['value' => 'nyata', 'label' => 'Nyata'],
                ['value' => 'simulasi', 'label' => 'Simulasi'],
                ['value' => 'dummy', 'label' => 'Dummy'],
            ],
            'source_types' => [
                ['value' => 'pemerintah', 'label' => 'Pemerintah'],
                ['value' => 'berita', 'label' => 'Berita'],
                ['value' => 'jurnal', 'label' => 'Jurnal'],
                ['value' => 'observasi', 'label' => 'Observasi'],
                ['value' => 'admin_input', 'label' => 'Input Admin'],
                ['value' => 'dummy', 'label' => 'Dummy'],
            ],
            'verifications' => [
                ['value' => 'verified', 'label' => 'Sudah Diverifikasi'],
                ['value' => 'unverified', 'label' => 'Perlu Validasi'],
            ],
            'districts' => $this->districtOptions(),
        ];
    }

    /**
     * @return Collection<int, string>
     */
    private function districtOptions(): Collection
    {
        return collect($this->datasets())
            ->flatMap(function (array $config): Collection {
                /** @var class-string<Model> $model */
                $model = $config['model'];

                return $model::query()
                    ->whereNotNull('district')
                    ->where('district', '!=', '')
                    ->distinct()
                    ->orderBy('district')
                    ->pluck('district');
            })
            ->filter()
            ->unique()
            ->sort()
            ->values();
    }

    /**
     * @return array<string, string>
     */
    private function normalizeFilters(array $filters): array
    {
        $datasets = array_keys($this->datasets());
        $dataStatuses = ['nyata', 'simulasi', 'dummy'];
        $sourceTypes = ['pemerintah', 'berita', 'jurnal', 'observasi', 'admin_input', 'dummy'];
        $verifications = ['verified', 'unverified'];

        $normalized = [
            'search' => trim((string) ($filters['search'] ?? '')),
            'module' => trim((string) ($filters['module'] ?? '')),
            'data_status' => trim((string) ($filters['data_status'] ?? '')),
            'source_type' => trim((string) ($filters['source_type'] ?? '')),
            'verification' => trim((string) ($filters['verification'] ?? '')),
            'district' => trim((string) ($filters['district'] ?? '')),
        ];

        if (! in_array($normalized['module'], $datasets, true)) {
            $normalized['module'] = '';
        }

        if (! in_array($normalized['data_status'], $dataStatuses, true)) {
            $normalized['data_status'] = '';
        }

        if (! in_array($normalized['source_type'], $sourceTypes, true)) {
            $normalized['source_type'] = '';
        }

        if (! in_array($normalized['verification'], $verifications, true)) {
            $normalized['verification'] = '';
        }

        return $normalized;
    }

    private function hasFilters(array $filters): bool
    {
        return collect($filters)
            ->filter(fn (string $value): bool => $value !== '')
            ->isNotEmpty();
    }

    private function readableSourceType(string $value): string
    {
        return match ($value) {
            'admin_input' => 'Input Admin',
            'pemerintah' => 'Pemerintah',
            'berita' => 'Berita',
            'jurnal' => 'Jurnal',
            'observasi' => 'Observasi',
            'dummy' => 'Dummy',
            default => $this->readableLabel($value),
        };
    }

    private function readableLabel(string $value): string
    {
        return Str::of($value)
            ->replace('_', ' ')
            ->title()
            ->toString();
    }
}
