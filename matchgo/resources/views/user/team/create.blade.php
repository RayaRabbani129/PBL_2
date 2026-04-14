{{-- resources/views/user/team/create.blade.php --}}

@extends('user.layouts.app')
@section('title', 'Buat Tim')
@section('page-title', 'Tim Saya')

@section('content')

<ul class="breadcrumb-matchgo">
    <li><a href="{{ route('team.index') }}">Tim Saya</a></li>
    <li><span class="separator"><i class="bi bi-chevron-right"></i></span></li>
    <li class="active">Buat Tim</li>
</ul>

<div class="page-header d-flex align-items-center justify-content-between">
    <div>
        <h2>Buat Tim</h2>
        <p>Isi informasi tim Anda untuk mulai bermain bersama.</p>
    </div>
    <a href="{{ route('team.index') }}" class="btn-matchgo-outline">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

@include('user.team.form', [
    'action'  => route('team.store'),
    'method'  => 'POST',
    'team'    => null,
    'btnText' => 'Buat Tim',
])

@endsection