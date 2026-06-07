{{-- resources/views/user/schedule/edit.blade.php --}}
@extends('user.layouts.app')

@section('title', 'Edit Jadwal — MATCHGO')
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
        justify-content: space-between;
        gap: 10px;
        padding-top: 1.25rem;
        margin-top: 0.5rem;
        border-top: 1px solid var(--border-subtle);
    }

    .mg-form-actions-right {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* ── Edit context banner ── */
    .mg-edit-banner {
        display: flex;
        align-items: center;
        gap: 14px;
        background: var(--surface-2);
        border: 1px solid var(--border-subtle);
        border-left: 3px solid var(--accent);
        border-radius: 14px;
        padding: 14px 18px;
        margin-bottom: 1.5rem;
    }

    .mg-edit-banner-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: var(--accent-dim);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--accent);
        font-size: 1rem;
        flex-shrink: 0;
    }

    .mg-edit-banner-body {
        display: flex;
        flex-direction: column;
        gap: 3px;
        min-width: 0;
    }

    .mg-edit-banner-title {
        font-family: 'Manrope', sans-serif;
        font-size: .8rem;
        font-weight: 700;
        color: var(--txt-muted);
        text-transform: uppercase;
        letter-spacing: .06em;
    }

    .mg-edit-banner-detail {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .mg-edit-banner-chip {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: .82rem;
        font-weight: 700;
        color: var(--accent);
        font-family: 'Manrope', sans-serif;
    }

    .mg-edit-banner-chip i {
        font-size: .8rem;
    }

    .mg-edit-banner-sep {
        color: var(--border-medium);
        font-size: .75rem;
    }

    /* ── Page wrapper max-width ── */
    .schedule-form-wrap {
        /* max-width: 680px; */
    }

    /* ── Danger button ── */
    .btn-danger-mg {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 9px 16px;
        border-radius: 11px;
        border: 1.5px solid rgba(239,68,68,.4);
        background: rgba(239,68,68,.06);
        color: #f87171;
        font-size: .82rem;
        font-weight: 700;
        cursor: pointer;
        transition: all .18s;
        font-family: 'Manrope', sans-serif;
        white-space: nowrap;
    }

    .btn-danger-mg:hover {
        background: rgba(239,68,68,.12);
        border-color: rgba(239,68,68,.65);
        color: #ef4444;
    }

    @media (max-width: 575px) {
        .mg-form-actions {
            flex-direction: column-reverse;
            align-items: stretch;
        }
        .mg-form-actions-right {
            flex-direction: column-reverse;
        }
        .mg-form-actions-right .btn-outline-lime,
        .mg-form-actions-right .btn-lime,
        .btn-danger-mg {
            width: 100%;
            justify-content: center;
        }
        .mg-edit-banner-detail {
            flex-direction: column;
            align-items: flex-start;
            gap: 4px;
        }
        .mg-edit-banner-sep { display: none; }
    }
</style>
@endpush

@section('content')

@php
    $days = [0=>'Minggu',1=>'Senin',2=>'Selasa',3=>'Rabu',4=>'Kamis',5=>'Jumat',6=>'Sabtu'];
@endphp

{{-- Breadcrumb --}}
<ul class="breadcrumb-matchgo">
    <li><a href="{{ route('dashboard') }}"><i class="bi bi-house me-1"></i>Dashboard</a></li>
    <li><span class="separator"><i class="bi bi-chevron-right"></i></span></li>
    <li><a href="{{ route('schedule.index') }}">Jadwal Tim</a></li>
    <li><span class="separator"><i class="bi bi-chevron-right"></i></span></li>
    <li><span class="active">Edit Jadwal</span></li>
</ul>

{{-- Page Header --}}
<div class="page-header d-flex align-items-center justify-content-between">
    <div>
        <h1>Edit Jadwal</h1>
        <p>Perbarui ketersediaan tim Anda pada jadwal yang dipilih</p>
    </div>
    <a href="{{ route('schedule.index') }}" class="btn-outline-lime btn-sm">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

{{-- Edit Context Banner --}}
<div class="schedule-form-wrap">

    <div class="mg-edit-banner">
        <div class="mg-edit-banner-icon">
            <i class="bi bi-pencil-square"></i>
        </div>
        <div class="mg-edit-banner-body">
            <span class="mg-edit-banner-title">Sedang mengedit</span>
            <div class="mg-edit-banner-detail">
                <span class="mg-edit-banner-chip">
                    <i class="bi bi-calendar3"></i>
                    {{ $days[$schedule->day_of_week] }}
                </span>
                <span class="mg-edit-banner-sep">·</span>
                <span class="mg-edit-banner-chip">
                    <i class="bi bi-clock"></i>
                    {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}
                    &ndash;
                    {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                </span>
                <span class="mg-edit-banner-sep">·</span>
                <span class="mg-edit-banner-chip" style="{{ $schedule->is_available ? 'color:var(--accent)' : 'color:#f87171' }}">
                    <i class="bi bi-{{ $schedule->is_available ? 'check-circle-fill' : 'x-circle-fill' }}"></i>
                    {{ $schedule->is_available ? 'Tersedia' : 'Tidak Tersedia' }}
                </span>
            </div>
        </div>
    </div>

    {{-- Form Card --}}
    <div class="card-matchgo">

        {{-- Card Header --}}
        <div class="mg-form-header">
            <div class="mg-form-header-icon">
                <i class="bi bi-calendar-check"></i>
            </div>
            <div class="mg-form-header-text">
                <h2>Perbarui Jadwal</h2>
                <p>Ubah hari, waktu, atau status ketersediaan tim</p>
            </div>
        </div>

        <form action="{{ route('schedule.update', $schedule) }}" method="POST" novalidate>
            @csrf
            @method('PUT')

            @include('user.schedule.form')

            <div class="mg-form-actions">
                {{-- Save / Cancel --}}
                <div class="mg-form-actions-right">
                    <a href="{{ route('schedule.index') }}" class="btn-outline-lime btn-sm">
                        <i class="bi bi-x-lg"></i> Batal
                    </a>
                    <button type="submit" class="btn-lime btn-sm">
                        <i class="bi bi-check2-circle"></i> Simpan Perubahan
                    </button>
                </div>

            </div>
        </form>

    </div>
</div>

@endsection