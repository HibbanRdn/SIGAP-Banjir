<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreHeavyEquipmentUnitRequest;
use App\Http\Requests\Admin\UpdateHeavyEquipmentUnitRequest;
use App\Models\EquipmentType;
use App\Models\HeavyEquipmentPost;
use App\Models\HeavyEquipmentUnit;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class HeavyEquipmentUnitController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->only(['post_id', 'equipment_type_id', 'status', 'available_only']);

        $units = HeavyEquipmentUnit::query()
            ->with(['post', 'type'])
            ->when($filters['post_id'] ?? null, fn ($query, string $postId) => $query->where('post_id', $postId))
            ->when($filters['equipment_type_id'] ?? null, fn ($query, string $typeId) => $query->where('equipment_type_id', $typeId))
            ->byStatus($filters['status'] ?? null)
            ->when(($filters['available_only'] ?? '') === '1', fn ($query) => $query->available())
            ->latest('updated_at')
            ->paginate(10)
            ->withQueryString();

        return view('admin.heavy-equipment-units.index', [
            'units' => $units,
            'filters' => $filters,
            'posts' => $this->postOptions(),
            'types' => $this->typeOptions(),
        ]);
    }

    public function create(): View
    {
        return view('admin.heavy-equipment-units.create', [
            'unit' => new HeavyEquipmentUnit([
                'quantity' => 1,
                'available_quantity' => 1,
                'status' => 'tersedia',
            ]),
            'posts' => $this->postOptions(),
            'types' => $this->typeOptions(),
        ]);
    }

    public function store(StoreHeavyEquipmentUnitRequest $request): RedirectResponse
    {
        $unit = HeavyEquipmentUnit::query()->create($request->validated());

        return redirect()
            ->route('admin.heavy-equipment-units.show', $unit)
            ->with('success', 'Unit alat berhasil ditambahkan.');
    }

    public function show(HeavyEquipmentUnit $heavyEquipmentUnit): View
    {
        $heavyEquipmentUnit->load(['post', 'type']);

        return view('admin.heavy-equipment-units.show', [
            'unit' => $heavyEquipmentUnit,
        ]);
    }

    public function edit(HeavyEquipmentUnit $heavyEquipmentUnit): View
    {
        $heavyEquipmentUnit->load(['post', 'type']);

        return view('admin.heavy-equipment-units.edit', [
            'unit' => $heavyEquipmentUnit,
            'posts' => $this->postOptions(),
            'types' => $this->typeOptions(),
        ]);
    }

    public function update(UpdateHeavyEquipmentUnitRequest $request, HeavyEquipmentUnit $heavyEquipmentUnit): RedirectResponse
    {
        $heavyEquipmentUnit->update($request->validated());

        return redirect()
            ->route('admin.heavy-equipment-units.show', $heavyEquipmentUnit)
            ->with('success', 'Unit alat berhasil diperbarui.');
    }

    public function destroy(HeavyEquipmentUnit $heavyEquipmentUnit): RedirectResponse
    {
        $heavyEquipmentUnit->delete();

        return redirect()
            ->route('admin.heavy-equipment-units.index')
            ->with('success', 'Unit alat berhasil dihapus.');
    }

    private function postOptions()
    {
        return HeavyEquipmentPost::query()
            ->orderBy('name')
            ->get(['id', 'name', 'district']);
    }

    private function typeOptions()
    {
        return EquipmentType::query()
            ->orderBy('name')
            ->get(['id', 'name']);
    }
}
