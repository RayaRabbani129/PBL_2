{{-- resources/views/user/team/members/edit.blade.php --}}

@extends('user.layouts.app')
@section('title', 'Edit Anggota Tim')
@section('page-title', 'Tim Saya')

@section('content')

{{-- Breadcrumb --}}
<ul class="breadcrumb-matchgo">
    <li><a href="{{ route('dashboard') }}"><i class="bi bi-house me-1"></i>Dashboard</a></li>
    <li class="separator"><i class="bi bi-chevron-right"></i></li>
    <li><a href="{{ route('team.index') }}">Tim Saya</a></li>
    <li class="separator"><i class="bi bi-chevron-right"></i></li>
    <li class="active">Edit Anggota</li>
</ul>

{{-- Page header --}}
<div class="page-header d-flex align-items-center justify-content-between">
    <div>
        <h2>Edit Anggota Tim</h2>
        <p>Perbarui data anggota <strong>{{ $member->name }}</strong>.</p>
    </div>
    <a href="{{ route('team.index') }}" class="btn-matchgo-outline">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

@include('user.team.members.form', [
    'action'  => route('team.members.update', $member),
    'method'  => 'PUT',
    'member'  => $member,
    'btnText' => 'Simpan Perubahan',
])

@endsection