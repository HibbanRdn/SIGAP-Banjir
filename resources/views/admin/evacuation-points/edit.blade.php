@extends('layouts.admin')

@section('title', 'Edit Titik Evakuasi')
@section('eyebrow', 'Form Titik Evakuasi')
@section('page-title', 'Edit Titik Evakuasi')

@section('content')
    @include('admin.evacuation-points._form', [
        'action' => route('admin.evacuation-points.update', $evacuationPoint),
        'method' => 'PUT',
        'submitLabel' => 'Simpan Perubahan',
    ])
@endsection
