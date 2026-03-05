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

                <form action="{{ route('admin.peralatan.update', $peralatan->id_peralatan) }}" method="POST"
                    autocomplete="off">
                    @csrf
                    @method('PUT')
                    {{-- ID --}}
                    <div class="mb-3" style="margin-bottom:12px;">
                        <label>ID Peralatan</label>
                        <input type="text" value="{{ $peralatan->id_peralatan }}" readonly
                            style="width:100%; padding:8px 10px; border-radius:6px; border:1px solid #ddd; background:#f5f5f5;">
                    </div>

                    {{-- Nama --}}
                    <div class="mb-3" style="margin-bottom:12px;">
                        <label>Nama Peralatan</label>
                        <input type="text" name="nama_peralatan"
                            value="{{ old('nama_peralatan', $peralatan->nama_peralatan) }}" required
                            style="width:100%; padding:8px 10px; border-radius:6px; border:1px solid #ddd;">
                    </div>

                    {{-- Lokasi --}}
                    <div class="mb-3" style="margin-bottom:12px;">
                        <label>Lokasi Penyimpanan</label>
                        <input type="text" name="lokasi_penyimpanan"
                            value="{{ old('lokasi_penyimpanan', $peralatan->lokasi_penyimpanan) }}" required
                            style="width:100%; padding:8px 10px; border-radius:6px; border:1px solid #ddd;">
                    </div>

                    {{-- Stok --}}
                    <div class="mb-3" style="margin-bottom:12px;">
                        <label>Stok</label>
                        <input type="number" name="stok" min="0" value="{{ old('stok', $peralatan->stok) }}" required
                            style="width:100%; padding:8px 10px; border-radius:6px; border:1px solid #ddd;">
                    </div>

                    {{-- Informasi Stok --}}
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:12px;">
                        {{-- Terpakai --}}
                        <div>
                            <label>Jumlah Terpakai</label>
                            <input type="number" value="{{ $peralatan->dipakai }}" disabled
                                style="width:100%; padding:8px 10px; border-radius:6px; border:1px solid #ddd; background:#f5f5f5;">
                        </div>
                        {{-- Tersedia --}}
                        <div>
                            <label>Unit yang Tersedia</label>
                            <input type="number" value="{{ $peralatan->stok_tersedia }}" disabled
                                style="width:100%; padding:8px 10px; border-radius:6px; border:1px solid #ddd; background:#f5f5f5;">
                        </div>
                    </div>

                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:12px;">
                        {{-- Rusak --}}
                        <div>
                            <label>Jumlah Rusak</label>
                            <input type="number" id="rusak" name="rusak" min="0"
                                value="{{ old('rusak', $peralatan->rusak) }}"
                                style="width:100%; padding:8px 10px; border-radius:6px; border:1px solid #ddd;">
                        </div>
                        {{-- Maintenance --}}
                        <div>
                            <label>Jumlah Maintenance</label>
                            <input type="number" id="perbaikan" name="perbaikan" min="0"
                                value="{{ old('perbaikan', $peralatan->perbaikan) }}"
                                style="width:100%; padding:8px 10px; border-radius:6px; border:1px solid #ddd;">
                        </div>
                    </div>

                    {{-- Keterangan --}}
                    <div class="mb-3" id="keterangan" style="margin-bottom:12px; display:none;">
                        <label>Keterangan</label>
                        <textarea name="keterangan" rows="3"
                            placeholder="Contoh: Lensa kamera retak / Sedang diperbaiki di Lab Elektronik / Unit cadangan ruang rapat A"
                            style="width:100%; padding:8px 10px; border-radius:6px; border:1px solid #ddd;">{{ old('keterangan', $peralatan->keterangan ?? '') }}</textarea>
                        <small style="color:#777;">
                            Tambahkan keterangan kondisi barang, lokasi perbaikan, atau informasi tambahan lainnya.
                        </small>
                    </div>

                    {{-- Tombol --}}
                    <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:18px;">
                        <a href="{{ route('admin.peralatan.index') }}"
                            style="padding:8px 14px; background:#e0e0e0; border-radius:6px; text-decoration:none; color:#222;">
                            Batal
                        </a>
                        <button type="submit"
                            style="padding:8px 14px; background:#3C91E6; color:#fff; border:none; border-radius:6px;">
                            Update
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </main>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const rusakInput = document.getElementById("rusak");
            const perbaikanInput = document.getElementById("perbaikan");
            const keteranganBox = document.getElementById("keterangan");
            function toggleKeterangan() {
                const rusak = parseInt(rusakInput.value) || 0;
                const perbaikan = parseInt(perbaikanInput.value) || 0;
                if (rusak > 0 || perbaikan > 0) {
                    keteranganBox.style.display = "block";
                } else {
                    keteranganBox.style.display = "none";
                }
            }
            toggleKeterangan();
            rusakInput.addEventListener("input", toggleKeterangan);
            perbaikanInput.addEventListener("input", toggleKeterangan);
        });
    </script>
@endsection