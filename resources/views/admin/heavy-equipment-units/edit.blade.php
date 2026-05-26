@extends('layouts.admin')

@section('title', 'Edit Unit Alat')
@section('eyebrow', 'Form Unit Alat')
@section('page-title', 'Edit Unit Alat')

@section('content')
    @include('admin.heavy-equipment-units._form', [
        'action' => route('admin.heavy-equipment-units.update', $unit),
        'method' => 'PUT',
        'submitLabel' => 'Simpan Perubahan',
    ])
@endsection
