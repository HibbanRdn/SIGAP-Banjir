<?php

namespace App\Http\Requests\Admin;

use App\Models\HeavyEquipmentUnit;
use Illuminate\Validation\Rule;

class UpdateHeavyEquipmentUnitRequest extends StoreHeavyEquipmentUnitRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $unit = $this->route('heavy_equipment_unit');

        return [
            'post_id' => ['required', 'integer', 'exists:heavy_equipment_posts,id'],
            'equipment_type_id' => [
                'required',
                'integer',
                'exists:equipment_types,id',
                Rule::unique('heavy_equipment_units', 'equipment_type_id')
                    ->where(fn ($query) => $query->where('post_id', $this->input('post_id')))
                    ->ignore($unit?->id),
            ],
            'quantity' => ['required', 'integer', 'min:0'],
            'available_quantity' => ['required', 'integer', 'min:0', 'lte:quantity'],
            'status' => ['required', Rule::in(HeavyEquipmentUnit::STATUSES)],
            'notes' => ['nullable', 'string'],
        ];
    }
}
