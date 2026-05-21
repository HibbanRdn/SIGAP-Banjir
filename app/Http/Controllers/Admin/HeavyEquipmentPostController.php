<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreHeavyEquipmentPostRequest;
use App\Http\Requests\Admin\UpdateHeavyEquipmentPostRequest;
use App\Models\HeavyEquipmentPost;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HeavyEquipmentPostController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'status', 'data_status', 'district', 'source_type', 'is_verified']);

        $posts = HeavyEquipmentPost::query()
            ->withCoordinates()
            ->with(['units.type'])
            ->when($filters['search'] ?? null, function ($query, string $search): void {
                $query->where(function ($query) use ($search): void {
                    $keyword = '%'.$search.'%';

                    $query
                        ->where('name', 'ilike', $keyword)
                        ->orWhere('address', 'ilike', $keyword)
                        ->orWhere('district', 'ilike', $keyword)
                        ->orWhere('subdistrict', 'ilike', $keyword)
                        ->orWhere('contact_person', 'ilike', $keyword);
                });
            })
            ->when($filters['status'] ?? null, fn ($query, string $status) => $query->where('status', $status))
            ->when($filters['data_status'] ?? null, fn ($query, string $dataStatus) => $query->where('data_status', $dataStatus))
            ->byDistrict($filters['district'] ?? null)
            ->when($filters['source_type'] ?? null, fn ($query, string $sourceType) => $query->where('source_type', $sourceType))
            ->when(($filters['is_verified'] ?? '') !== '', fn ($query) => $query->where('is_verified', (bool) (int) $filters['is_verified']))
            ->orderByDesc('updated_at')
            ->paginate(10)
            ->withQueryString();

        $districts = $this->districtOptions();

        $statusCounts = HeavyEquipmentPost::query()
            ->selectRaw('status, COUNT(*) AS total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('admin.heavy-equipment-posts.index', [
            'posts' => $posts,
            'filters' => $filters,
            'districts' => $districts,
            'statusCounts' => $statusCounts,
        ]);
    }

    public function create(): View
    {
        return view('admin.heavy-equipment-posts.create', [
            'post' => new HeavyEquipmentPost([
                'status' => 'aktif',
                'source_type' => 'dummy',
                'data_status' => 'dummy',
            ]),
            'districts' => $this->districtOptions(),
        ]);
    }

    public function store(StoreHeavyEquipmentPostRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $longitude = (float) $validated['longitude'];
        $latitude = (float) $validated['latitude'];
        $attributes = $this->attributesFromValidated($validated);

        $post = DB::transaction(function () use ($attributes, $longitude, $latitude): HeavyEquipmentPost {
            return $this->insertHeavyEquipmentPostWithGeometry($attributes, $longitude, $latitude);
        });

        return redirect()
            ->route('admin.heavy-equipment-posts.show', $post)
            ->with('success', 'Data pos alat berat berhasil ditambahkan.');
    }

    public function show(HeavyEquipmentPost $heavyEquipmentPost): View
    {
        return view('admin.heavy-equipment-posts.show', [
            'post' => $this->loadHeavyEquipmentPostWithCoordinates($heavyEquipmentPost),
        ]);
    }

    public function edit(HeavyEquipmentPost $heavyEquipmentPost): View
    {
        return view('admin.heavy-equipment-posts.edit', [
            'post' => $this->loadHeavyEquipmentPostWithCoordinates($heavyEquipmentPost),
            'districts' => $this->districtOptions(),
        ]);
    }

    public function update(UpdateHeavyEquipmentPostRequest $request, HeavyEquipmentPost $heavyEquipmentPost): RedirectResponse
    {
        $validated = $request->validated();
        $longitude = (float) $validated['longitude'];
        $latitude = (float) $validated['latitude'];
        $attributes = $this->attributesFromValidated($validated);

        DB::transaction(function () use ($heavyEquipmentPost, $attributes, $longitude, $latitude): void {
            $heavyEquipmentPost->update($attributes);
            $this->setHeavyEquipmentPostGeometry($heavyEquipmentPost, $longitude, $latitude);
        });

        return redirect()
            ->route('admin.heavy-equipment-posts.show', $heavyEquipmentPost)
            ->with('success', 'Data pos alat berat berhasil diperbarui.');
    }

    public function destroy(HeavyEquipmentPost $heavyEquipmentPost): RedirectResponse
    {
        if ($heavyEquipmentPost->units()->exists()) {
            return redirect()
                ->route('admin.heavy-equipment-posts.index')
                ->with('warning', 'Pos alat berat tidak dapat dihapus karena masih memiliki unit alat.');
        }

        $heavyEquipmentPost->delete();

        return redirect()
            ->route('admin.heavy-equipment-posts.index')
            ->with('success', 'Data pos alat berat berhasil dihapus.');
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
    private function insertHeavyEquipmentPostWithGeometry(array $attributes, float $longitude, float $latitude): HeavyEquipmentPost
    {
        $now = now();

        $row = DB::selectOne(
            <<<'SQL'
                INSERT INTO heavy_equipment_posts (
                    name,
                    address,
                    district,
                    subdistrict,
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
                    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
                    ST_SetSRID(ST_MakePoint(?, ?), 4326)
                )
                RETURNING id
            SQL,
            [
                $attributes['name'],
                $attributes['address'] ?? null,
                $attributes['district'] ?? null,
                $attributes['subdistrict'] ?? null,
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

        return HeavyEquipmentPost::query()->findOrFail($row->id);
    }

    private function setHeavyEquipmentPostGeometry(HeavyEquipmentPost $post, float $longitude, float $latitude): void
    {
        DB::update(
            'UPDATE heavy_equipment_posts SET geom = ST_SetSRID(ST_MakePoint(?, ?), 4326), updated_at = NOW() WHERE id = ?',
            [$longitude, $latitude, $post->id],
        );
    }

    private function loadHeavyEquipmentPostWithCoordinates(HeavyEquipmentPost $post): HeavyEquipmentPost
    {
        return HeavyEquipmentPost::query()
            ->withCoordinates()
            ->with(['units.type'])
            ->findOrFail($post->id);
    }

    private function districtOptions()
    {
        return HeavyEquipmentPost::query()
            ->whereNotNull('district')
            ->where('district', '!=', '')
            ->distinct()
            ->orderBy('district')
            ->pluck('district');
    }
}
