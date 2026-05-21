@extends('layouts.admin')

@section('title', 'Edit Kejadian Banjir')
@section('eyebrow', 'Form Kejadian Banjir')
@section('page-title', 'Edit Kejadian Banjir')

@section('content')
    @include('admin.flood-events._form', [
        'action' => route('admin.flood-events.update', $floodEvent),
        'method' => 'PUT',
        'submitLabel' => 'Simpan Perubahan',
    ])
@endsection
