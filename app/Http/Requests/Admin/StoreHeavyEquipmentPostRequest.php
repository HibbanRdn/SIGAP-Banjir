<?php

namespace App\Http\Requests\Admin;

use App\Models\HeavyEquipmentPost;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreHeavyEquipmentPostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_verified' => $this->boolean('is_verified'),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'district' => ['nullable', 'string', 'max:255'],
            'subdistrict' => ['nullable', 'string', 'max:255'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'status' => ['required', Rule::in(HeavyEquipmentPost::STATUSES)],
            'description' => ['nullable', 'string'],
            'source_type' => ['required', Rule::in(HeavyEquipmentPost::SOURCE_TYPES)],
            'source_reference' => [
                Rule::requiredIf(fn () => $this->input('data_status') === 'nyata'),
                'nullable',
                'string',
            ],
            'is_verified' => ['boolean'],
            'data_status' => ['required', Rule::in(HeavyEquipmentPost::DATA_STATUSES)],
            'longitude' => ['required', 'numeric', 'min:105.0', 'max:105.5'],
            'latitude' => ['required', 'numeric', 'min:-5.6', 'max:-5.2'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama pos alat berat wajib diisi.',
            'status.in' => 'Status pos alat berat tidak valid.',
            'source_type.in' => 'Tipe sumber data tidak valid.',
            'source_reference.required' => 'Referensi sumber wajib diisi untuk data nyata.',
            'data_status.in' => 'Status data tidak valid.',
            'longitude.min' => 'Longitude harus berada di sekitar Bandar Lampung.',
            'longitude.max' => 'Longitude harus berada di sekitar Bandar Lampung.',
            'latitude.min' => 'Latitude harus berada di sekitar Bandar Lampung.',
            'latitude.max' => 'Latitude harus berada di sekitar Bandar Lampung.',
        ];
    }
}
