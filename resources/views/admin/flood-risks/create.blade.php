@extends('layouts.admin')

@section('title', 'Tambah Titik Rawan Banjir')
@section('eyebrow', 'Form Titik Rawan')
@section('page-title', 'Tambah Titik Rawan Banjir')

@section('content')
    @include('admin.flood-risks._form', [
        'action' => route('admin.flood-risks.store'),
        'method' => 'POST',
        'submitLabel' => 'Simpan Titik Rawan',
    ])
@endsection
