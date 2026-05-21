@extends('layouts.admin')

@section('title', 'Edit Titik Rawan Banjir')
@section('eyebrow', 'Form Titik Rawan')
@section('page-title', 'Edit Titik Rawan Banjir')

@section('content')
    @include('admin.flood-risks._form', [
        'action' => route('admin.flood-risks.update', $floodRisk),
        'method' => 'PUT',
        'submitLabel' => 'Simpan Perubahan',
    ])
@endsection
