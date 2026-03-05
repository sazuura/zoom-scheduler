@extends('layouts.admin')

@section('title', 'Tambah Peralatan')

@section('content')
    <main>
        <div class="head-title">
            <div class="left">
                <h1>Tambah Peralatan</h1>
                <ul class="breadcrumb">
                    <li><a href="{{ route('admin.peralatan.index') }}">Peralatan</a></li>
                    <li><i class="bx bx-chevron-right"></i></li>
                    <li><a class="active" href="#">Tambah</a></li>
                </ul>
            </div>
        </div>

        <div class="table-data">
            <div class="order" style="flex:1;">
                <div class="head">
                    <h3>Form Tambah Peralatan</h3>
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

                <form action="{{ route('admin.peralatan.store') }}" method="POST" autocomplete="off">
                    @csrf

                    {{-- ID Peralatan --}}
                    <div class="mb-3" style="margin-bottom:12px;">
                        <label>ID Peralatan</label>
                        <input type="text" value="{{ $newId }}" readonly
                            style="width:100%; padding:8px 10px; border-radius:6px; border:1px solid #ddd; background:#f5f5f5;">
                    </div>

                    {{-- Nama Peralatan --}}
                    <div class="mb-3" style="margin-bottom:12px;">
                        <label>Nama Peralatan</label>
                        <input type="text" name="nama_peralatan" required value="{{ old('nama_peralatan') }}"
                            style="width:100%; padding:8px 10px; border-radius:6px; border:1px solid #ddd;">
                    </div>

                    {{-- Lokasi Penyimpanan --}}
                    <div class="mb-3" style="margin-bottom:12px;">
                        <label>Lokasi Penyimpanan</label>
                        <input type="text" name="lokasi_penyimpanan" required value="{{ old('lokasi_penyimpanan') }}"
                            style="width:100%; padding:8px 10px; border-radius:6px; border:1px solid #ddd;"
                            placeholder="Contoh: Gedung A Lt.2 Ruang Gudang 1">
                    </div>

                    {{-- Stok --}}
                    <div class="mb-3" style="margin-bottom:12px;">
                        <label>Stok</label>
                        <input type="number" name="stok" min="0" required value="{{ old('stok') }}"
                            style="width:100%; padding:8px 10px; border-radius:6px; border:1px solid #ddd;">
                    </div>
                    {{-- Tombol --}}
                    <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:18px;">
                        <a href="{{ route('admin.peralatan.index') }}"
                            style="padding:8px 14px; background:#e0e0e0; border-radius:6px; text-decoration:none; color:#222;">
                            Batal
                        </a>

                        <button type="submit"
                            style="padding:8px 14px; background:#3C91E6; color:#fff; border:none; border-radius:6px;">
                            Simpan
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </main>
@endsection