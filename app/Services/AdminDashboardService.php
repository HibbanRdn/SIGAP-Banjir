<?php

namespace App\Services;

use App\Models\EquipmentType;
use App\Models\EvacuationPoint;
use App\Models\FloodEvent;
use App\Models\FloodRiskPoint;
use App\Models\HeavyEquipmentPost;
use App\Models\HeavyEquipmentUnit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminDashboardService
{
    /**
     * @return array<string, mixed>
     */
    public function summary(): array
    {
        $activeFloodEvents = FloodEvent::query()->active()->count();
        $criticalActiveFloodEvents = FloodEvent::query()
            ->active()
            ->where('severity_level', 'kritis')
            ->count();

        $totalFloodRisks = FloodRiskPoint::query()->count();
        $highFloodRisks = FloodRiskPoint::query()
            ->where('risk_level', 'tinggi')
            ->count();

        $activeEvacuationPoints = EvacuationPoint::query()->active()->count();
        $activeEvacuationCapacity = (int) EvacuationPoint::query()
            ->active()
            ->sum('capacity');

        $activeEquipmentPosts = HeavyEquipmentPost::query()->active()->count();
        $availableEquipmentUnits = (int) HeavyEquipmentUnit::query()
            ->available()
            ->whereHas('post', fn ($query) => $query->active())
            ->sum('available_quantity');

        $dataStatusSummary = $this->dataStatusSummary();
        $unverifiedTotal = $dataStatusSummary['unverified_total'];
        $reviewCandidateTotal = ($dataStatusSummary['totals']['dummy'] ?? 0)
            + ($dataStatusSummary['totals']['simulasi'] ?? 0);

        return [
            'stats' => [
                [
                    'label' => 'Banjir Aktif',
                    'value' => $activeFloodEvents,
                    'hint' => $criticalActiveFloodEvents > 0
                        ? $criticalActiveFloodEvents.' kejadian kritis aktif'
                        : 'Tidak ada kejadian kritis aktif',
                    'tone' => 'border-red-100 bg-red-50 text-red-700',
                    'icon' => 'M4 14.25c2-2 4-2 6 0s4 2 6 0 3-1.5 4-1.5M4 18.25c2-2 4-2 6 0s4 2 6 0 3-1.5 4-1.5M5.75 10.25a6.25 6.25 0 1 1 12.5 0',
                ],
                [
                    'label' => 'Titik Rawan',
                    'value' => $totalFloodRisks,
                    'hint' => $highFloodRisks.' titik risiko tinggi',
                    'tone' => 'border-amber-100 bg-amber-50 text-amber-700',
                    'icon' => 'M12 3.75 21.25 20H2.75L12 3.75Zm0 5.75v4.5m0 3.25h.01',
                ],
                [
                    'label' => 'Titik Evakuasi Aktif',
                    'value' => $activeEvacuationPoints,
                    'hint' => number_format($activeEvacuationCapacity, 0, ',', '.').' kapasitas aktif',
                    'tone' => 'border-teal-100 bg-teal-50 text-teal-700',
                    'icon' => 'M12 3.75 20.25 7.5v5.75c0 4.5-3.25 7-8.25 8-5-1-8.25-3.5-8.25-8V7.5L12 3.75Zm-3 8.75 2 2 4-4',
                ],
                [
                    'label' => 'Unit Alat Tersedia',
                    'value' => $availableEquipmentUnits,
                    'hint' => $activeEquipmentPosts.' pos alat berat aktif',
                    'tone' => 'border-yellow-100 bg-yellow-50 text-yellow-700',
                    'icon' => 'M3.75 15.25h10.5V7.75H3.75v7.5Zm10.5 0h2.5l2-3h1.5v3h-6Zm-8.5 2.5h.01m10.5 0h.01',
                ],
                [
                    'label' => 'Data Perlu Validasi',
                    'value' => $unverifiedTotal,
                    'hint' => $reviewCandidateTotal.' data dummy/simulasi perlu ditinjau',
                    'tone' => 'border-blue-100 bg-blue-50 text-blue-700',
                    'icon' => 'M5.25 5.75c0-1.1 3.02-2 6.75-2s6.75.9 6.75 2-3.02 2-6.75 2-6.75-.9-6.75-2Zm0 0v12.5c0 1.1 3.02 2 6.75 2s6.75-.9 6.75-2V5.75m-13.5 6.25c0 1.1 3.02 2 6.75 2s6.75-.9 6.75-2',
                ],
            ],
            'recentEvents' => $this->recentFloodEvents(),
            'equipmentAvailability' => $this->equipmentAvailability(),
            'dataStatusSummary' => $dataStatusSummary,
            'layerSummary' => $this->layerSummary(),
        ];
    }

    /**
     * @return Collection<int, FloodEvent>
     */
    private function recentFloodEvents(): Collection
    {
        return FloodEvent::query()
            ->orderByRaw('COALESCE(reported_at, updated_at) DESC')
            ->limit(5)
            ->get();
    }

    /**
     * @return Collection<int, object>
     */
    private function equipmentAvailability(): Collection
    {
        return EquipmentType::query()
            ->leftJoin('heavy_equipment_units', 'equipment_types.id', '=', 'heavy_equipment_units.equipment_type_id')
            ->leftJoin('heavy_equipment_posts', 'heavy_equipment_units.post_id', '=', 'heavy_equipment_posts.id')
            ->select([
                'equipment_types.id',
                'equipment_types.name',
                DB::raw("COALESCE(SUM(CASE WHEN heavy_equipment_posts.status = 'aktif' THEN heavy_equipment_units.quantity ELSE 0 END), 0)::integer AS total_quantity"),
                DB::raw("COALESCE(SUM(CASE WHEN heavy_equipment_posts.status = 'aktif' AND heavy_equipment_units.status = 'tersedia' THEN heavy_equipment_units.available_quantity ELSE 0 END), 0)::integer AS available_quantity"),
            ])
            ->groupBy('equipment_types.id', 'equipment_types.name')
            ->orderBy('equipment_types.name')
            ->limit(6)
            ->get()
            ->map(function ($type) {
                $total = (int) $type->total_quantity;
                $available = (int) $type->available_quantity;

                $type->label = $this->readableLabel($type->name);
                $type->total_quantity = $total;
                $type->available_quantity = $available;
                $type->percentage = $total > 0 ? min(100, round(($available / $total) * 100)) : 0;

                return $type;
            });
    }

    /**
     * @return array<string, mixed>
     */
    private function dataStatusSummary(): array
    {
        $models = [
            'Kejadian Banjir' => FloodEvent::class,
            'Titik Rawan' => FloodRiskPoint::class,
            'Titik Evakuasi' => EvacuationPoint::class,
            'Pos Alat Berat' => HeavyEquipmentPost::class,
        ];

        $totals = [
            'nyata' => 0,
            'dummy' => 0,
            'simulasi' => 0,
        ];
        $datasetRows = [];
        $unverifiedTotal = 0;
        $grandTotal = 0;

        foreach ($models as $label => $modelClass) {
            /** @var class-string<Model> $modelClass */
            $counts = $modelClass::query()
                ->selectRaw('data_status, COUNT(*) AS total')
                ->groupBy('data_status')
                ->pluck('total', 'data_status');

            $datasetTotal = (int) $modelClass::query()->count();
            $datasetUnverified = (int) $modelClass::query()
                ->where('is_verified', false)
                ->count();

            foreach (array_keys($totals) as $status) {
                $totals[$status] += (int) ($counts[$status] ?? 0);
            }

            $datasetRows[] = [
                'label' => $label,
                'total' => $datasetTotal,
                'unverified' => $datasetUnverified,
            ];

            $grandTotal += $datasetTotal;
            $unverifiedTotal += $datasetUnverified;
        }

        return [
            'totals' => $totals,
            'unverified_total' => $unverifiedTotal,
            'grand_total' => $grandTotal,
            'datasets' => $datasetRows,
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function layerSummary(): array
    {
        return [
            [
                'label' => 'Kejadian Banjir',
                'value' => FloodEvent::query()->count(),
                'tone' => 'border-red-100 bg-red-50 text-red-700',
            ],
            [
                'label' => 'Titik Rawan',
                'value' => FloodRiskPoint::query()->count(),
                'tone' => 'border-amber-100 bg-amber-50 text-amber-700',
            ],
            [
                'label' => 'Titik Evakuasi',
                'value' => EvacuationPoint::query()->count(),
                'tone' => 'border-teal-100 bg-teal-50 text-teal-700',
            ],
            [
                'label' => 'Pos Alat Berat',
                'value' => HeavyEquipmentPost::query()->count(),
                'tone' => 'border-yellow-100 bg-yellow-50 text-yellow-700',
            ],
        ];
    }

    private function readableLabel(string $value): string
    {
        return Str::of($value)
            ->replace('_', ' ')
            ->title()
            ->toString();
    }
}
