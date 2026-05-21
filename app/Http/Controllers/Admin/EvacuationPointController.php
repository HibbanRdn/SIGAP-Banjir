<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreEvacuationPointRequest;
use App\Http\Requests\Admin\UpdateEvacuationPointRequest;
use App\Models\EvacuationPoint;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EvacuationPointController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'type', 'status', 'data_status', 'district', 'source_type', 'is_verified']);

        $evacuationPoints = EvacuationPoint::query()
            ->withCoordinates()
            ->when($filters['search'] ?? null, function ($query, string $search): void {
                $query->where(function ($query) use ($search): void {
                    $keyword = '%'.$search.'%';

                    $query
                        ->where('name', 'ilike', $keyword)
                        ->orWhere('address', 'ilike', $keyword)
                        ->orWhere('district', 'ilike', $keyword)
                        ->orWhere('subdistrict', 'ilike', $keyword)
                        ->orWhere('facilities', 'ilike', $keyword);
                });
            })
            ->when($filters['type'] ?? null, fn ($query, string $type) => $query->where('type', $type))
            ->byStatus($filters['status'] ?? null)
            ->byDataStatus($filters['data_status'] ?? null)
            ->byDistrict($filters['district'] ?? null)
            ->when($filters['source_type'] ?? null, fn ($query, string $sourceType) => $query->where('source_type', $sourceType))
            ->when(($filters['is_verified'] ?? '') !== '', fn ($query) => $query->where('is_verified', (bool) (int) $filters['is_verified']))
            ->orderByDesc('updated_at')
            ->paginate(10)
            ->withQueryString();

        $districts = $this->districtOptions();

        $statusCounts = EvacuationPoint::query()
            ->selectRaw('status, COUNT(*) AS total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('admin.evacuation-points.index', [
            'evacuationPoints' => $evacuationPoints,
            'filters' => $filters,
            'districts' => $districts,
            'statusCounts' => $statusCounts,
        ]);
    }

    public function create(): View
    {
        return view('admin.evacuation-points.create', [
            'evacuationPoint' => new EvacuationPoint([
                'type' => 'masjid',
                'status' => 'aktif',
                'source_type' => 'admin_input',
                'data_status' => 'simulasi',
            ]),
            'districts' => $this->districtOptions(),
        ]);
    }

    public function store(StoreEvacuationPointRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $longitude = (float) $validated['longitude'];
        $latitude = (float) $validated['latitude'];
        $attributes = $this->attributesFromValidated($validated);

        $evacuationPoint = DB::transaction(function () use ($attributes, $longitude, $latitude): EvacuationPoint {
            return $this->insertEvacuationPointWithGeometry($attributes, $longitude, $latitude);
        });

        return redirect()
            ->route('admin.evacuation-points.show', $evacuationPoint)
            ->with('success', 'Data titik evakuasi berhasil ditambahkan.');
    }

    public function show(EvacuationPoint $evacuationPoint): View
    {
        return view('admin.evacuation-points.show', [
            'evacuationPoint' => $this->loadEvacuationPointWithCoordinates($evacuationPoint),
        ]);
    }

    public function edit(EvacuationPoint $evacuationPoint): View
    {
        return view('admin.evacuation-points.edit', [
            'evacuationPoint' => $this->loadEvacuationPointWithCoordinates($evacuationPoint),
            'districts' => $this->districtOptions(),
        ]);
    }

    public function update(UpdateEvacuationPointRequest $request, EvacuationPoint $evacuationPoint): RedirectResponse
    {
        $validated = $request->validated();
        $longitude = (float) $validated['longitude'];
        $latitude = (float) $validated['latitude'];
        $attributes = $this->attributesFromValidated($validated);

        DB::transaction(function () use ($evacuationPoint, $attributes, $longitude, $latitude): void {
            $evacuationPoint->update($attributes);
            $this->setEvacuationPointGeometry($evacuationPoint, $longitude, $latitude);
        });

        return redirect()
            ->route('admin.evacuation-points.show', $evacuationPoint)
            ->with('success', 'Data titik evakuasi berhasil diperbarui.');
    }

    public function destroy(EvacuationPoint $evacuationPoint): RedirectResponse
    {
        $evacuationPoint->delete();

        return redirect()
            ->route('admin.evacuation-points.index')
            ->with('success', 'Data titik evakuasi berhasil dihapus.');
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
    private function insertEvacuationPointWithGeometry(array $attributes, float $longitude, float $latitude): EvacuationPoint
    {
        $now = now();

        $row = DB::selectOne(
            <<<'SQL'
                INSERT INTO evacuation_points (
                    name,
                    type,
                    address,
                    district,
                    subdistrict,
                    capacity,
                    facilities,
                    contact_person,
                    contact_phone,
                    status,
                    description,
                    source_type,
                    source_reference,
                    is_verified,
                    data_status,
                    created_at,
                    updated_at,
                    geom
                ) VALUES (
                    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
                    ST_SetSRID(ST_MakePoint(?, ?), 4326)
                )
                RETURNING id
            SQL,
            [
                $attributes['name'],
                $attributes['type'],
                $attributes['address'] ?? null,
                $attributes['district'] ?? null,
                $attributes['subdistrict'] ?? null,
                $attributes['capacity'] ?? null,
                $attributes['facilities'] ?? null,
                $attributes['contact_person'] ?? null,
                $attributes['contact_phone'] ?? null,
                $attributes['status'],
                $attributes['description'] ?? null,
                $attributes['source_type'],
                $attributes['source_reference'] ?? null,
                $attributes['is_verified'] ?? false,
                $attributes['data_status'],
                $now,
                $now,
                $longitude,
                $latitude,
            ],
        );

        return EvacuationPoint::query()->findOrFail($row->id);
    }

    private function setEvacuationPointGeometry(EvacuationPoint $evacuationPoint, float $longitude, float $latitude): void
    {
        DB::update(
            'UPDATE evacuation_points SET geom = ST_SetSRID(ST_MakePoint(?, ?), 4326), updated_at = NOW() WHERE id = ?',
            [$longitude, $latitude, $evacuationPoint->id],
        );
    }

    private function loadEvacuationPointWithCoordinates(EvacuationPoint $evacuationPoint): EvacuationPoint
    {
        return EvacuationPoint::query()
            ->withCoordinates()
            ->findOrFail($evacuationPoint->id);
    }

    private function districtOptions()
    {
        return EvacuationPoint::query()
            ->whereNotNull('district')
            ->where('district', '!=', '')
            ->distinct()
            ->orderBy('district')
            ->pluck('district');
    }
}
