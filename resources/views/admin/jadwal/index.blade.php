@extends('layouts.admin')

@section('title', 'Data Jadwal Operator')

@section('content')
<main>
    <div class="head-title">
        <div class="left">
            <h1>Data Jadwal Operator</h1>
        </div>
        <a href="{{ route('admin.jadwal.create') }}" class="btn-download">
            <i class="bx bx-plus"></i>
            <span class="text">Tambah Jadwal</span>
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success" style="margin:10px 0; padding:10px; border-radius:6px;">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>List Jadwal</h3>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>ID Jadwal</th>
                        <th>Judul Kegiatan</th>
                        <th>Tanggal</th>
                        <th>Waktu</th>
                        <th>Platform</th>
                        <th>Operator</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jadwal as $j)
                        <tr>
                            <td>{{ $j->id_penjadwalan }}</td>
                            <td>{{ $j->judul_kegiatan }}</td>
                            <td>{{ \Carbon\Carbon::parse($j->tanggal)->translatedFormat('l, d F Y') }}</td>
                            <td>{{ $j->waktu_mulai }} - {{ $j->waktu_selesai }}</td>
                            <td>{{ $j->platform }}</td>
                            <td>{{ $j->user->nama_user ?? '-' }}</td>
                            <td>
                                <div class="action-buttons">
                                    {{-- Tombol Edit --}}
                                    <a href="{{ route('admin.jadwal.edit', $j->id_penjadwalan) }}" 
                                       class="btn-action edit">
                                        <i class="bx bx-edit"></i>
                                    </a>

                                    {{-- Tombol Hapus --}}
                                    <form action="{{ route('admin.jadwal.destroy', $j->id_penjadwalan) }}" 
                                          method="POST" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-action delete" 
                                                onclick="return confirm('Yakin ingin menghapus jadwal ini?')">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align:center; padding:15px;">Belum ada jadwal</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</main>
@endsection
