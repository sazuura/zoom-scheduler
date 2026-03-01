@extends('layouts.admin')

@section('title', 'Edit Jadwal')

@section('content')
<main>
    <div class="head-title">
        <div class="left">
            <h1>Edit Jadwal</h1>
            <ul class="breadcrumb">
                <li><a href="{{ route('admin.jadwal.index') }}">Jadwal</a></li>
                <li><i class="bx bx-chevron-right"></i></li>
                <li><a class="active" href="#">Edit</a></li>
            </ul>
        </div>
    </div>

    <div class="table-data">
        <div class="order" style="flex:1;">
            <div class="head">
                <h3>Form Edit Jadwal</h3>
            </div>

            {{-- ALERT SECTION --}}
            @if(session('error'))
                <div style="background:#fde2e2; border:1px solid #f5c2c2; padding:10px 15px; border-radius:8px; color:#b91c1c; margin-bottom:15px;">
                    <strong>⚠️ Terjadi Kesalahan:</strong> {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div style="background:#fff3cd; border:1px solid #ffeeba; padding:10px 15px; border-radius:8px; color:#856404; margin-bottom:15px;">
                    <ul style="margin:0; padding-left:20px;">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.jadwal.update', $jadwal->id_penjadwalan) }}" method="POST" autocomplete="off">
                @csrf @method('PUT')

                {{-- ID Jadwal --}}
                <div class="mb-3" style="margin-bottom:12px;">
                    <label>ID Jadwal</label>
                    <input type="text" value="{{ $jadwal->id_penjadwalan }}" readonly
                           style="width:100%; padding:8px 10px; border-radius:6px; border:1px solid #ddd; background:#f5f5f5;">
                </div>

                {{-- Judul Kegiatan --}}
                <div class="mb-3" style="margin-bottom:12px;">
                    <label>Judul Kegiatan</label>
                    <input type="text" name="judul_kegiatan"
                           value="{{ old('judul_kegiatan', $jadwal->judul_kegiatan) }}" required
                           style="width:100%; padding:8px 10px; border-radius:6px; border:1px solid #ddd;">
                </div>

                {{-- Tanggal --}}
                <div class="mb-3" style="margin-bottom:12px;">
                    <label>Tanggal</label>
                    <input type="date" name="tanggal"
                           value="{{ old('tanggal', \Carbon\Carbon::parse($jadwal->tanggal)->format('Y-m-d')) }}" required
                           style="width:100%; padding:8px 10px; border-radius:6px; border:1px solid #ddd; background:#fff;">
                </div>

                {{-- Waktu Mulai --}}
                <div class="mb-3" style="margin-bottom:12px;">
                    <label>Waktu Mulai</label>
                    <input type="time" name="waktu_mulai"
                           value="{{ old('waktu_mulai', \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('H:i')) }}" required
                           style="width:100%; padding:8px 10px; border-radius:6px; border:1px solid #ddd; background:#fff;">
                </div>

                {{-- Waktu Selesai --}}
                <div class="mb-3" style="margin-bottom:12px;">
                    <label>Waktu Selesai</label>
                    <input type="time" name="waktu_selesai"
                           value="{{ old('waktu_selesai', \Carbon\Carbon::parse($jadwal->waktu_selesai)->format('H:i')) }}" required
                           style="width:100%; padding:8px 10px; border-radius:6px; border:1px solid #ddd; background:#fff;">
                </div>

                {{-- Platform --}}
                <div class="mb-3" style="margin-bottom:12px;">
                    <label>Platform</label>
                    <select name="platform" required
                            style="width:100%; padding:8px 10px; border-radius:6px; border:1px solid #ddd; background:#fff;">
                        @foreach($platforms as $platform)
                            <option value="{{ $platform }}"
                                {{ old('platform', $jadwal->platform) == $platform ? 'selected' : '' }}>
                                {{ $platform }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Operator --}}
                <div class="mb-3" style="margin-bottom:12px;">
                    <label>Operator</label>
                    <select name="id_user" required
                            style="width:100%; padding:8px 10px; border-radius:6px; border:1px solid #ddd; background:#fff;">
                        @foreach($operators as $op)
                            <option value="{{ $op->id_user }}"
                                {{ old('id_user', $jadwal->id_user) == $op->id_user ? 'selected' : '' }}>
                                {{ $op->nama_user }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Tombol --}}
                <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:18px;">
                    <a href="{{ route('admin.jadwal.index') }}" class="btn"
                       style="padding:8px 14px; background:#e0e0e0; border-radius:6px; text-decoration:none; color:#222;">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary"
                            style="padding:8px 14px; background:#3C91E6; color:#fff; border:none; border-radius:6px;">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection
