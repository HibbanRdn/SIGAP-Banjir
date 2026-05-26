@extends('layouts.admin')

@section('title', 'Tambah Unit Alat')
@section('eyebrow', 'Form Unit Alat')
@section('page-title', 'Tambah Unit Alat')

@section('content')
    @include('admin.heavy-equipment-units._form', [
        'action' => route('admin.heavy-equipment-units.store'),
        'method' => 'POST',
        'submitLabel' => 'Simpan Unit Alat',
    ])
@endsection
