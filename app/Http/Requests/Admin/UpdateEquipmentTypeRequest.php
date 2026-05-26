<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;

class UpdateEquipmentTypeRequest extends StoreEquipmentTypeRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $equipmentType = $this->route('equipment_type');

        return [
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('equipment_types', 'name')->ignore($equipmentType?->id),
            ],
            'description' => ['nullable', 'string'],
        ];
    }
}
