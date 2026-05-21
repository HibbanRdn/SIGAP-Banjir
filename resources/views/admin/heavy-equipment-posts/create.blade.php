@extends('layouts.admin')

@section('title', 'Tambah Pos Alat Berat')
@section('eyebrow', 'Form Pos Alat Berat')
@section('page-title', 'Tambah Pos Alat Berat')

@section('content')
    @include('admin.heavy-equipment-posts._form', [
        'action' => route('admin.heavy-equipment-posts.store'),
        'method' => 'POST',
        'submitLabel' => 'Simpan Pos Alat Berat',
    ])
@endsection
