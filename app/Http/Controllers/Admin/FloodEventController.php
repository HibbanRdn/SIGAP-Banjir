<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreFloodEventRequest;
use App\Http\Requests\Admin\UpdateFloodEventRequest;
use App\Models\FloodEvent;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FloodEventController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'status', 'severity_level', 'data_status', 'district']);

        $events = FloodEvent::query()
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
            ->byStatus($filters['status'] ?? null)
            ->bySeverity($filters['severity_level'] ?? null)
            ->byDataStatus($filters['data_status'] ?? null)
            ->byDistrict($filters['district'] ?? null)
            ->latestReported()
            ->paginate(10)
            ->withQueryString();

        $districts = FloodEvent::query()
            ->whereNotNull('district')
            ->where('district', '!=', '')
            ->distinct()
            ->orderBy('district')
            ->pluck('district');

        $statusCounts = FloodEvent::query()
            ->selectRaw('status, COUNT(*) AS total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('admin.flood-events.index', [
            'events' => $events,
            'filters' => $filters,
            'districts' => $districts,
            'statusCounts' => $statusCounts,
        ]);
    }

    public function create(): View
    {
        return view('admin.flood-events.create', [
            'floodEvent' => new FloodEvent([
                'severity_level' => 'sedang',
                'status' => 'aktif',
                'source_type' => 'admin_input',
                'data_status' => 'simulasi',
                'reported_at' => now(),
            ]),
            'districts' => $this->districtOptions(),
        ]);
    }

    public function store(StoreFloodEventRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $longitude = (float) $validated['longitude'];
        $latitude = (float) $validated['latitude'];
        $attributes = $this->attributesFromValidated($validated);
        $attributes['created_by'] = $request->user()?->id;

        $floodEvent = DB::transaction(function () use ($attributes, $longitude, $latitude): FloodEvent {
            return $this->insertFloodEventWithGeometry($attributes, $longitude, $latitude);
        });

        return redirect()
            ->route('admin.flood-events.show', $floodEvent)
            ->with('success', 'Data kejadian banjir berhasil ditambahkan.');
    }

    public function show(FloodEvent $floodEvent): View
    {
        return view('admin.flood-events.show', [
            'floodEvent' => $this->loadFloodEventWithCoordinates($floodEvent),
        ]);
    }

    public function edit(FloodEvent $floodEvent): View
    {
        return view('admin.flood-events.edit', [
            'floodEvent' => $this->loadFloodEventWithCoordinates($floodEvent),
            'districts' => $this->districtOptions(),
        ]);
    }

    public function update(UpdateFloodEventRequest $request, FloodEvent $floodEvent): RedirectResponse
    {
        $validated = $request->validated();
        $longitude = (float) $validated['longitude'];
        $latitude = (float) $validated['latitude'];
        $attributes = $this->attributesFromValidated($validated);

        if ($floodEvent->created_by === null) {
            $attributes['created_by'] = $request->user()?->id;
        }

        DB::transaction(function () use ($floodEvent, $attributes, $longitude, $latitude): void {
            $floodEvent->update($attributes);
            $this->setFloodEventGeometry($floodEvent, $longitude, $latitude);
        });

        return redirect()
            ->route('admin.flood-events.show', $floodEvent)
            ->with('success', 'Data kejadian banjir berhasil diperbarui.');
    }

    public function destroy(FloodEvent $floodEvent): RedirectResponse
    {
        $floodEvent->delete();

        return redirect()
            ->route('admin.flood-events.index')
            ->with('success', 'Data kejadian banjir berhasil dihapus.');
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
    private function insertFloodEventWithGeometry(array $attributes, float $longitude, float $latitude): FloodEvent
    {
        $now = now();

        $row = DB::selectOne(
            <<<'SQL'
                INSERT INTO flood_events (
                    name,
                    address,
                    district,
                    subdistrict,
                    severity_level,
                    water_depth_cm,
                    status,
                    description,
                    source_type,
                    source_reference,
                    occurred_at,
                    reported_at,
                    is_verified,
                    data_status,
                    created_by,
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
                $attributes['address'] ?? null,
                $attributes['district'] ?? null,
                $attributes['subdistrict'] ?? null,
                $attributes['severity_level'],
                $attributes['water_depth_cm'] ?? null,
                $attributes['status'],
                $attributes['description'] ?? null,
                $attributes['source_type'],
                $attributes['source_reference'] ?? null,
                $attributes['occurred_at'] ?? null,
                $attributes['reported_at'],
                $attributes['is_verified'] ?? false,
                $attributes['data_status'],
                $attributes['created_by'] ?? null,
                $now,
                $now,
                $longitude,
                $latitude,
            ],
        );

        return FloodEvent::query()->findOrFail($row->id);
    }

    private function setFloodEventGeometry(FloodEvent $floodEvent, float $longitude, float $latitude): void
    {
        DB::update(
            'UPDATE flood_events SET geom = ST_SetSRID(ST_MakePoint(?, ?), 4326), updated_at = NOW() WHERE id = ?',
            [$longitude, $latitude, $floodEvent->id],
        );
    }

    private function loadFloodEventWithCoordinates(FloodEvent $floodEvent): FloodEvent
    {
        return FloodEvent::query()
            ->with('creator')
            ->withCoordinates()
            ->findOrFail($floodEvent->id);
    }

    private function districtOptions()
    {
        return FloodEvent::query()
            ->whereNotNull('district')
            ->where('district', '!=', '')
            ->distinct()
            ->orderBy('district')
            ->pluck('district');
    }
}
