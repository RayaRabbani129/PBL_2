@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Tim Saya</h1>
                <a href="{{ route('team.create') }}" class="btn btn-primary">
                    + Tambah Tim
                </a>
            </div>

            @if($teams->count())
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Nama Tim</th>
                                <th>Jumlah Anggota</th>
                                <th>Dibuat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($teams as $team)
                            <tr>
                                <td>{{ $team->name }}</td>
                                <td>{{ $team->members_count }}</td>
                                <td>{{ $team->created_at->format('d M Y') }}</td>
                                <td>
                                    <a href="{{ route('team.show', $team->id) }}" class="btn btn-sm btn-info">Lihat</a>
                                    <a href="{{ route('team.edit', $team->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('team.destroy', $team->id) }}" method="POST" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">Tim Anda belum ada. <a href="{{ route('team.create') }}">Buat tim baru</a></div>
            @endif
        </div>
    </div>
</div>
@endsection