{{-- resources/views/user/team/members/create.blade.php --}}

@extends('user.layouts.app')
@section('title', 'Tambah Anggota Tim')
@section('page-title', 'Tim Saya')

@section('content')

{{-- Breadcrumb --}}
<ul class="breadcrumb-matchgo">
    <li><a href="{{ route('team.index') }}">Tim Saya</a></li>
    <li><span class="separator"><i class="bi bi-chevron-right"></i></span></li>
    <li class="active">Tambah Anggota</li>
</ul>

{{-- Page header --}}
<div class="page-header d-flex align-items-center justify-content-between">
    <div>
        <h2>Tambah Anggota Tim</h2>
        <p>Tambahkan pemain baru ke dalam tim <strong>{{ $team->name }}</strong>.</p>
    </div>
    <a href="{{ route('team.index') }}" class="btn-matchgo-outline">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

@include('user.team.members.form', [
    'action'  => route('team.members.store'),
    'method'  => 'POST',
    'member'  => null,
    'btnText' => 'Tambah Anggota',
])

@endsection