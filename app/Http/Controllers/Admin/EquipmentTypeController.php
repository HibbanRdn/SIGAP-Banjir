<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreEquipmentTypeRequest;
use App\Http\Requests\Admin\UpdateEquipmentTypeRequest;
use App\Models\EquipmentType;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EquipmentTypeController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->only(['search']);

        $types = EquipmentType::query()
            ->withCount('units')
            ->withSum('units', 'quantity')
            ->withSum('units', 'available_quantity')
            ->when($filters['search'] ?? null, function ($query, string $search): void {
                $query->where(function ($query) use ($search): void {
                    $keyword = '%'.$search.'%';

                    $query
                        ->where('name', 'ilike', $keyword)
                        ->orWhere('description', 'ilike', $keyword);
                });
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.equipment-types.index', [
            'types' => $types,
            'filters' => $filters,
        ]);
    }

    public function create(): View
    {
        return view('admin.equipment-types.create', [
            'equipmentType' => new EquipmentType(),
        ]);
    }

    public function store(StoreEquipmentTypeRequest $request): RedirectResponse
    {
        $equipmentType = EquipmentType::query()->create($request->validated());

        return redirect()
            ->route('admin.equipment-types.show', $equipmentType)
            ->with('success', 'Jenis alat berhasil ditambahkan.');
    }

    public function show(EquipmentType $equipmentType): View
    {
        $equipmentType->load(['units.post']);

        return view('admin.equipment-types.show', [
            'equipmentType' => $equipmentType,
        ]);
    }

    public function edit(EquipmentType $equipmentType): View
    {
        return view('admin.equipment-types.edit', [
            'equipmentType' => $equipmentType,
        ]);
    }

    public function update(UpdateEquipmentTypeRequest $request, EquipmentType $equipmentType): RedirectResponse
    {
        $equipmentType->update($request->validated());

        return redirect()
            ->route('admin.equipment-types.show', $equipmentType)
            ->with('success', 'Jenis alat berhasil diperbarui.');
    }

    public function destroy(EquipmentType $equipmentType): RedirectResponse
    {
        if ($equipmentType->units()->exists()) {
            return redirect()
                ->route('admin.equipment-types.index')
                ->with('warning', 'Jenis alat tidak dapat dihapus karena masih digunakan oleh unit alat.');
        }

        $equipmentType->delete();

        return redirect()
            ->route('admin.equipment-types.index')
            ->with('success', 'Jenis alat berhasil dihapus.');
    }
}
