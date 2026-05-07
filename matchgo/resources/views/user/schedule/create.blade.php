{{-- resources/views/user/schedule/create.blade.php --}}
@extends('user.layouts.app')

@section('title', 'Tambah Jadwal — MATCHGO')
@section('page-title', 'Jadwal Tim')

@push('styles')
<style>
    /* ── Time input color fix ── */
    input[type="time"].form-control-mg {
        color-scheme: dark;
    }
    [data-theme="light"] input[type="time"].form-control-mg {
        color-scheme: light;
    }

    /* ── Form card header ── */
    .mg-form-header {
        display: flex;
        align-items: center;
        gap: 14px;
        padding-bottom: 1.25rem;
        margin-bottom: 1.75rem;
        border-bottom: 1px solid var(--border-subtle);
    }

    .mg-form-header-icon {
        width: 44px;
        height: 44px;
        border-radius: 13px;
        background: var(--accent-dim);
        border: 1px solid rgba(163,177,75,.25);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--accent);
        font-size: 1.15rem;
        flex-shrink: 0;
    }

    .mg-form-header-text h2 {
        font-family: 'Manrope', sans-serif;
        font-size: 1rem;
        font-weight: 800;
        color: var(--txt-primary);
        margin-bottom: 2px;
    }

    .mg-form-header-text p {
        font-size: .78rem;
        color: var(--txt-muted);
        margin: 0;
    }

    /* ── Form footer actions ── */
    .mg-form-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
        padding-top: 1.25rem;
        margin-top: 0.5rem;
        border-top: 1px solid var(--border-subtle);
    }

    /* ── Page wrapper max-width ── */
    .schedule-form-wrap {
        /* max-width: 680px; */
    }
</style>
@endpush

@section('content')

{{-- Breadcrumb --}}
<ul class="breadcrumb-matchgo">
    <li><a href="{{ route('dashboard') }}"><i class="bi bi-house me-1"></i>Dashboard</a></li>
    <li><span class="separator"><i class="bi bi-chevron-right"></i></span></li>
    <li><a href="{{ route('schedule.index') }}">Jadwal Tim</a></li>
    <li><span class="separator"><i class="bi bi-chevron-right"></i></span></li>
    <li><span class="active">Tambah Jadwal</span></li>
</ul>

{{-- Page Header --}}
<div class="page-header d-flex align-items-center justify-content-between">
    <div>
        <h1>Tambah Jadwal</h1>
        <p>Atur ketersediaan tim Anda pada hari dan waktu tertentu</p>
    </div>
    <a href="{{ route('schedule.index') }}" class="btn-outline-lime btn-sm">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

{{-- Form Card --}}
<div class="schedule-form-wrap">
    <div class="card-matchgo">

        {{-- Card Header --}}
        <div class="mg-form-header">
            <div class="mg-form-header-icon">
                <i class="bi bi-calendar-plus"></i>
            </div>
            <div class="mg-form-header-text">
                <h2>Jadwal Baru</h2>
                <p>Lengkapi tiga bagian di bawah untuk mendaftarkan jadwal tim</p>
            </div>
        </div>

        <form action="{{ route('schedule.store') }}" method="POST" novalidate>
            @csrf

            @include('user.schedule.form')

            <div class="mg-form-actions">
                <a href="{{ route('schedule.index') }}" class="btn-outline-lime btn-sm">
                    <i class="bi bi-x-lg"></i> Batal
                </a>
                <button type="submit" class="btn-lime btn-sm">
                    <i class="bi bi-plus-circle"></i> Simpan Jadwal
                </button>
            </div>
        </form>

    </div>
</div>

@endsection