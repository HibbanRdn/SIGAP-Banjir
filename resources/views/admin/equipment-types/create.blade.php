@extends('layouts.admin')

@section('title', 'Tambah Jenis Alat')
@section('eyebrow', 'Form Jenis Alat')
@section('page-title', 'Tambah Jenis Alat')

@section('content')
    @include('admin.equipment-types._form', [
        'action' => route('admin.equipment-types.store'),
        'method' => 'POST',
        'submitLabel' => 'Simpan Jenis Alat',
    ])
@endsection
