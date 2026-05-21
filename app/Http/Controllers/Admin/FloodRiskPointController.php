<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreFloodRiskPointRequest;
use App\Http\Requests\Admin\UpdateFloodRiskPointRequest;
use App\Models\FloodRiskPoint;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FloodRiskPointController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'risk_level', 'data_status', 'district', 'source_type', 'is_verified']);

        $riskPoints = FloodRiskPoint::query()
            ->with('creator')
            ->withCoordinates()
            ->when($filters['search'] ?? null, function ($query, string $search): void {
                $query->where(function ($query) use ($search): void {
                    $keyword = '%'.$search.'%';

                    $query
                        ->where('name', 'ilike', $keyword)
                        ->orWhere('address', 'ilike', $keyword)
                        ->orWhere('district', 'ilike', $keyword)
                        ->orWhere('subdistrict', 'ilike', $keyword);
                });
            })
            ->byRiskLevel($filters['risk_level'] ?? null)
            ->byDataStatus($filters['data_status'] ?? null)
            ->byDistrict($filters['district'] ?? null)
            ->when($filters['source_type'] ?? null, fn ($query, string $sourceType) => $query->where('source_type', $sourceType))
            ->when(($filters['is_verified'] ?? '') !== '', fn ($query) => $query->where('is_verified', (bool) (int) $filters['is_verified']))
            ->orderByDesc('updated_at')
            ->paginate(10)
            ->withQueryString();

        $districts = $this->districtOptions();

        $riskCounts = FloodRiskPoint::query()
            ->selectRaw('risk_level, COUNT(*) AS total')
            ->groupBy('risk_level')
            ->pluck('total', 'risk_level');

        return view('admin.flood-risks.index', [
            'riskPoints' => $riskPoints,
            'filters' => $filters,
            'districts' => $districts,
            'riskCounts' => $riskCounts,
        ]);
    }

    public function create(): View
    {
        return view('admin.flood-risks.create', [
            'floodRisk' => new FloodRiskPoint([
                'risk_level' => 'sedang',
                'source_type' => 'admin_input',
                'data_status' => 'simulasi',
            ]),
            'districts' => $this->districtOptions(),
        ]);
    }

    public function store(StoreFloodRiskPointRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $longitude = (float) $validated['longitude'];
        $latitude = (float) $validated['latitude'];
        $attributes = $this->attributesFromValidated($validated);
        $attributes['created_by'] = $request->user()?->id;

        $floodRisk = DB::transaction(function () use ($attributes, $longitude, $latitude): FloodRiskPoint {
            return $this->insertFloodRiskWithGeometry($attributes, $longitude, $latitude);
        });

        return redirect()
            ->route('admin.flood-risks.show', $floodRisk)
            ->with('success', 'Data titik rawan banjir berhasil ditambahkan.');
    }

    public function show(FloodRiskPoint $floodRisk): View
    {
        return view('admin.flood-risks.show', [
            'floodRisk' => $this->loadFloodRiskWithCoordinates($floodRisk),
        ]);
    }

    public function edit(FloodRiskPoint $floodRisk): View
    {
        return view('admin.flood-risks.edit', [
            'floodRisk' => $this->loadFloodRiskWithCoordinates($floodRisk),
            'districts' => $this->districtOptions(),
        ]);
    }

    public function update(UpdateFloodRiskPointRequest $request, FloodRiskPoint $floodRisk): RedirectResponse
    {
        $validated = $request->validated();
        $longitude = (float) $validated['longitude'];
        $latitude = (float) $validated['latitude'];
        $attributes = $this->attributesFromValidated($validated);

        if ($floodRisk->created_by === null) {
            $attributes['created_by'] = $request->user()?->id;
        }

        DB::transaction(function () use ($floodRisk, $attributes, $longitude, $latitude): void {
            $floodRisk->update($attributes);
            $this->setFloodRiskGeometry($floodRisk, $longitude, $latitude);
        });

        return redirect()
            ->route('admin.flood-risks.show', $floodRisk)
            ->with('success', 'Data titik rawan banjir berhasil diperbarui.');
    }

    public function destroy(FloodRiskPoint $floodRisk): RedirectResponse
    {
        $floodRisk->delete();

        return redirect()
            ->route('admin.flood-risks.index')
            ->with('success', 'Data titik rawan banjir berhasil dihapus.');
    }

    /**
     * @param array<string, mixed> $validated
     * @return array<string, mixed>
     */
    private function attributesFromValidated(array $validated): array
    {
        unset($validated['longitude'], $validated['latitude']);

        return $validated;
    }

    /**
     * Kolom geom bersifat NOT NULL, jadi insert dibuat sekaligus dengan geom
     * memakai binding. Urutan PostGIS tetap longitude, latitude.
     *
     * @param array<string, mixed> $attributes
     */
    private function insertFloodRiskWithGeometry(array $attributes, float $longitude, float $latitude): FloodRiskPoint
    {
        $now = now();

        $row = DB::selectOne(
            <<<'SQL'
                INSERT INTO flood_risk_points (
                    name,
                    address,
                    district,
                    subdistrict,
                    risk_level,
                    description,
                    source_type,
                    source_reference,
                    is_verified,
                    data_status,
                    created_by,
                    created_at,
                    updated_at,
                    geom
                ) VALUES (
                    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
                    ST_SetSRID(ST_MakePoint(?, ?), 4326)
                )
                RETURNING id
            SQL,
            [
                $attributes['name'],
                $attributes['address'] ?? null,
                $attributes['district'] ?? null,
                $attributes['subdistrict'] ?? null,
                $attributes['risk_level'],
                $attributes['description'] ?? null,
                $attributes['source_type'],
                $attributes['source_reference'] ?? null,
                $attributes['is_verified'] ?? false,
                $attributes['data_status'],
                $attributes['created_by'] ?? null,
                $now,
                $now,
                $longitude,
                $latitude,
            ],
        );

        return FloodRiskPoint::query()->findOrFail($row->id);
    }

    private function setFloodRiskGeometry(FloodRiskPoint $floodRisk, float $longitude, float $latitude): void
    {
        DB::update(
            'UPDATE flood_risk_points SET geom = ST_SetSRID(ST_MakePoint(?, ?), 4326), updated_at = NOW() WHERE id = ?',
            [$longitude, $latitude, $floodRisk->id],
        );
    }

    private function loadFloodRiskWithCoordinates(FloodRiskPoint $floodRisk): FloodRiskPoint
    {
        return FloodRiskPoint::query()
            ->with('creator')
            ->withCoordinates()
            ->findOrFail($floodRisk->id);
    }

    private function districtOptions()
    {
        return FloodRiskPoint::query()
            ->whereNotNull('district')
            ->where('district', '!=', '')
            ->distinct()
            ->orderBy('district')
            ->pluck('district');
    }
}
