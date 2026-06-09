{{-- resources/views/user/team/edit.blade.php --}}

@extends('user.layouts.app')
@section('title', 'Edit Tim')
@section('page-title', 'Tim Saya')

@section('content')

<ul class="breadcrumb-matchgo">
    <li><a href="{{ route('dashboard') }}"><i class="bi bi-house me-1"></i>Dashboard</a></li>
    <li class="separator"><i class="bi bi-chevron-right"></i></li>
    <li><a href="{{ route('team.index') }}">Tim Saya</a></li>
    <li class="separator"><i class="bi bi-chevron-right"></i></li>
    <li class="active">Edit Tim</li>
</ul>

<div class="page-header d-flex align-items-center justify-content-between">
    <div>
        <h2>Edit Tim</h2>
        <p>Perbarui informasi tim <strong>{{ $team->name }}</strong>.</p>
    </div>
    <a href="{{ route('team.index') }}" class="btn-matchgo-outline">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

@include('user.team.form', [
    'action'  => route('team.update', $team),
    'method'  => 'PUT',
    'team'    => $team,
    'btnText' => 'Simpan Perubahan',
])

@endsection