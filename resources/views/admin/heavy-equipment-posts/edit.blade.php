@extends('layouts.admin')

@section('title', 'Edit Pos Alat Berat')
@section('eyebrow', 'Form Pos Alat Berat')
@section('page-title', 'Edit Pos Alat Berat')

@section('content')
    @include('admin.heavy-equipment-posts._form', [
        'action' => route('admin.heavy-equipment-posts.update', $post),
        'method' => 'PUT',
        'submitLabel' => 'Simpan Perubahan',
    ])
@endsection
