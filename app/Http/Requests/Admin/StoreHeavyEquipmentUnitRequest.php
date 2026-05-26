<?php

namespace App\Http\Requests\Admin;

use App\Models\HeavyEquipmentUnit;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Illuminate\Validation\Rule;

class StoreHeavyEquipmentUnitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'post_id' => ['required', 'integer', 'exists:heavy_equipment_posts,id'],
            'equipment_type_id' => [
                'required',
                'integer',
                'exists:equipment_types,id',
                Rule::unique('heavy_equipment_units', 'equipment_type_id')
                    ->where(fn ($query) => $query->where('post_id', $this->input('post_id'))),
            ],
            'quantity' => ['required', 'integer', 'min:0'],
            'available_quantity' => ['required', 'integer', 'min:0', 'lte:quantity'],
            'status' => ['required', Rule::in(HeavyEquipmentUnit::STATUSES)],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if ($this->input('status') === 'tidak_aktif' && (int) $this->input('available_quantity', 0) > 0) {
                    $validator->errors()->add('available_quantity', 'Unit tidak aktif harus memiliki jumlah tersedia 0.');
                }
            },
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'post_id.required' => 'Pos alat berat wajib dipilih.',
            'post_id.exists' => 'Pos alat berat tidak valid.',
            'equipment_type_id.required' => 'Jenis alat wajib dipilih.',
            'equipment_type_id.exists' => 'Jenis alat tidak valid.',
            'equipment_type_id.unique' => 'Jenis alat ini sudah tercatat pada pos yang dipilih.',
            'quantity.required' => 'Jumlah total unit wajib diisi.',
            'quantity.min' => 'Jumlah total unit tidak boleh negatif.',
            'available_quantity.required' => 'Jumlah tersedia wajib diisi.',
            'available_quantity.min' => 'Jumlah tersedia tidak boleh negatif.',
            'available_quantity.lte' => 'Jumlah tersedia tidak boleh melebihi jumlah total.',
            'status.in' => 'Status unit alat tidak valid.',
        ];
    }
}
