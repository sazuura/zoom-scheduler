@extends('layouts.admin')

@section('title', 'Edit Peralatan')

@section('content')
<main>
    <div class="head-title">
        <div class="left">
            <h1>Edit Peralatan</h1>
            <ul class="breadcrumb">
                <li><a href="{{ route('admin.peralatan.index') }}">Peralatan</a></li>
                <li><i class="bx bx-chevron-right"></i></li>
                <li><a class="active" href="#">Edit</a></li>
            </ul>
        </div>
    </div>

    <div class="table-data">
        <div class="order" style="flex:1;">
            <div class="head">
                <h3>Form Edit Peralatan</h3>
            </div>

            {{-- Validasi --}}
            @if ($errors->any())
                <div style="background:#fff3cd;border:1px solid #ffeeba;padding:12px;border-radius:8px;margin-bottom:16px;">
                    <ul style="margin:0;padding-left:18px;color:#856404;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.peralatan.update', $peralatan->id_peralatan) }}" method="POST" autocomplete="off">
                @csrf @method('PUT')

                {{-- ID Peralatan --}}
                <div class="mb-3" style="margin-bottom:12px;">
                    <label>ID Peralatan</label>
                    <input type="text" value="{{ $peralatan->id_peralatan }}" readonly
                           style="width:100%; padding:8px 10px; border-radius:6px; border:1px solid #ddd; background:#f5f5f5;">
                </div>

                {{-- Nama Peralatan --}}
                <div class="mb-3" style="margin-bottom:12px;">
                    <label>Nama Peralatan</label>
                    <input type="text" name="nama_peralatan" value="{{ $peralatan->nama_peralatan }}" required
                           style="width:100%; padding:8px 10px; border-radius:6px; border:1px solid #ddd;">
                </div>

                {{-- Kondisi --}}
                <div class="mb-3" style="margin-bottom:12px;">
                    <label>Kondisi</label>
                    <select name="kondisi" required
                            style="width:100%; padding:8px 10px; border-radius:6px; border:1px solid #ddd; background:#fff;">
                        <option value="Baik" {{ $peralatan->kondisi == 'Baik' ? 'selected' : '' }}>Baik</option>
                        <option value="Diperbaiki" {{ $peralatan->kondisi == 'Diperbaiki' ? 'selected' : '' }}>Diperbaiki</option>
                        <option value="Rusak" {{ $peralatan->kondisi == 'Rusak' ? 'selected' : '' }}>Rusak</option>
                    </select>
                </div>

                {{-- Stok --}}
                <div class="mb-3" style="margin-bottom:12px;">
                    <label>Stok</label>
                    <input type="number" name="stok" min="0" value="{{ $peralatan->stok }}" required
                           style="width:100%; padding:8px 10px; border-radius:6px; border:1px solid #ddd;">
                </div>

                {{-- Tombol --}}
                <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:18px;">
                    <a href="{{ route('admin.peralatan.index') }}" class="btn"
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
