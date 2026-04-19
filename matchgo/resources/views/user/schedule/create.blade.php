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

    /* ── Toggle Switch ── */
    .mg-toggle-label {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        user-select: none;
    }

    .mg-toggle-input {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }

    .mg-toggle-track {
        position: relative;
        width: 42px;
        height: 24px;
        border-radius: 99px;
        background: var(--surface-5);
        border: 1px solid var(--border-medium);
        transition: background 0.2s, border-color 0.2s;
        flex-shrink: 0;
    }

    .mg-toggle-thumb {
        position: absolute;
        top: 3px;
        left: 3px;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: var(--txt-faint);
        transition: transform 0.2s, background 0.2s;
    }

    .mg-toggle-input:checked + .mg-toggle-track {
        background: var(--accent-dim);
        border-color: var(--accent);
    }

    .mg-toggle-input:checked + .mg-toggle-track .mg-toggle-thumb {
        transform: translateX(18px);
        background: var(--accent);
    }

    .mg-toggle-text {
        font-size: 0.85rem;
        color: var(--txt-secondary);
        transition: color 0.2s;
    }

    /* ── Form card header ── */
    .mg-form-header {
        display: flex;
        align-items: center;
        gap: 12px;
        padding-bottom: 1.25rem;
        margin-bottom: 1.5rem;
        border-bottom: 1px solid var(--border-subtle);
    }

    .mg-form-header-icon {
        width: 40px;
        height: 40px;
        border-radius: 11px;
        background: var(--accent-dim);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--accent);
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .mg-form-header h2 {
        font-family: 'Manrope', sans-serif;
        font-size: 1rem;
        font-weight: 700;
        color: var(--txt-primary);
        margin-bottom: 2px;
    }

    .mg-form-header p {
        font-size: 0.8rem;
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
        margin-top: 1.5rem;
        border-top: 1px solid var(--border-subtle);
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
<div style="max-width: 640px;">
    <div class="card-matchgo">

        {{-- Card Header --}}
        <div class="mg-form-header">
            <div class="mg-form-header-icon">
                <i class="bi bi-calendar-plus"></i>
            </div>
            <div>
                <h2>Jadwal Baru</h2>
                <p>Isi detail hari dan waktu yang tersedia</p>
            </div>
        </div>

        <form action="{{ route('schedule.store') }}" method="POST" novalidate>
            @csrf

            @include('user.schedule.form')

            <div class="mg-form-actions">
                <a href="{{ route('schedule.index') }}" class="btn-outline-lime btn-sm">
                    Batal
                </a>
                <button type="submit" class="btn-lime btn-sm">
                    <i class="bi bi-plus-circle"></i> Simpan Jadwal
                </button>
            </div>
        </form>

    </div>
</div>

@endsection