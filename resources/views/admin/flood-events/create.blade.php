@extends('layouts.admin')

@section('title', 'Tambah Kejadian Banjir')
@section('eyebrow', 'Form Kejadian Banjir')
@section('page-title', 'Tambah Kejadian Banjir')

@section('content')
    @include('admin.flood-events._form', [
        'action' => route('admin.flood-events.store'),
        'method' => 'POST',
        'submitLabel' => 'Simpan Kejadian',
    ])
@endsection
