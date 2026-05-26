<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EquipmentType;
use App\Models\HeavyEquipmentPost;
use App\Models\HeavyEquipmentUnit;
use Illuminate\Contracts\View\View;

class EquipmentInventoryController extends Controller
{
    public function __invoke(): View
    {
        $types = EquipmentType::query()
            ->withCount('units')
            ->withSum('units', 'quantity')
            ->withSum('units', 'available_quantity')
            ->orderBy('name')
            ->get();

        $units = HeavyEquipmentUnit::query()
            ->with(['post', 'type'])
            ->latest('updated_at')
            ->limit(8)
            ->get();

        $postSummaries = HeavyEquipmentPost::query()
            ->with(['units.type'])
            ->orderBy('name')
            ->get();

        $totalTypes = EquipmentType::query()->count();
        $totalQuantity = HeavyEquipmentUnit::query()->sum('quantity');
        $availableQuantity = HeavyEquipmentUnit::query()->sum('available_quantity');
        $inactiveOrMaintenance = HeavyEquipmentUnit::query()
            ->whereIn('status', ['perawatan', 'tidak_aktif'])
            ->sum('quantity');

        return view('admin.equipment.index', [
            'types' => $types,
            'units' => $units,
            'postSummaries' => $postSummaries,
            'totalTypes' => $totalTypes,
            'totalQuantity' => $totalQuantity,
            'availableQuantity' => $availableQuantity,
            'inactiveOrMaintenance' => $inactiveOrMaintenance,
        ]);
    }
}
