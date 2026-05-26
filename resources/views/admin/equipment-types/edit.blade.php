@extends('layouts.admin')

@section('title', 'Edit Jenis Alat')
@section('eyebrow', 'Form Jenis Alat')
@section('page-title', 'Edit Jenis Alat')

@section('content')
    @include('admin.equipment-types._form', [
        'action' => route('admin.equipment-types.update', $equipmentType),
        'method' => 'PUT',
        'submitLabel' => 'Simpan Perubahan',
    ])
@endsection
