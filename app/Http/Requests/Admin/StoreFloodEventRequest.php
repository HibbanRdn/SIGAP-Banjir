<?php

namespace App\Http\Requests\Admin;

use App\Models\FloodEvent;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFloodEventRequest extends FormRequest
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
            'severity_level' => ['required', Rule::in(FloodEvent::SEVERITY_LEVELS)],
            'water_depth_cm' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', Rule::in(FloodEvent::STATUSES)],
            'description' => ['nullable', 'string'],
            'source_type' => ['required', Rule::in(FloodEvent::SOURCE_TYPES)],
            'source_reference' => [
                Rule::requiredIf(fn () => $this->input('data_status') === 'nyata'),
                'nullable',
                'string',
            ],
            'occurred_at' => ['nullable', 'date'],
            'reported_at' => ['required', 'date'],
            'is_verified' => ['boolean'],
            'data_status' => ['required', Rule::in(FloodEvent::DATA_STATUSES)],
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
            'name.required' => 'Nama kejadian wajib diisi.',
            'severity_level.in' => 'Severity kejadian tidak valid.',
            'status.in' => 'Status kejadian tidak valid.',
            'source_type.in' => 'Tipe sumber data tidak valid.',
            'source_reference.required' => 'Referensi sumber wajib diisi untuk data nyata.',
            'reported_at.required' => 'Waktu laporan wajib diisi.',
            'longitude.min' => 'Longitude harus berada di sekitar Bandar Lampung.',
            'longitude.max' => 'Longitude harus berada di sekitar Bandar Lampung.',
            'latitude.min' => 'Latitude harus berada di sekitar Bandar Lampung.',
            'latitude.max' => 'Latitude harus berada di sekitar Bandar Lampung.',
        ];
    }
}
