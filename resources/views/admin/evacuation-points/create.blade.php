@extends('layouts.admin')

@section('title', 'Tambah Titik Evakuasi')
@section('eyebrow', 'Form Titik Evakuasi')
@section('page-title', 'Tambah Titik Evakuasi')

@section('content')
    @include('admin.evacuation-points._form', [
        'action' => route('admin.evacuation-points.store'),
        'method' => 'POST',
        'submitLabel' => 'Simpan Titik Evakuasi',
    ])
@endsection
