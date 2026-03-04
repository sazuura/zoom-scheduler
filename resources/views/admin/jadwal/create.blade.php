@extends('layouts.admin')
@section('title', 'Tambah Jadwal')
@section('content')
    <main>
        <div class="head-title">
            <div class="left">
                <h1>Tambah Jadwal</h1>
            </div>
            <a href="{{ route('admin.jadwal.index') }}" class="btn-download">
                <i class="bx bx-arrow-back"></i>
                <span class="text">Kembali</span>
            </a>
        </div>

        <div class="table-data">
            <div class="order" style="flex:1;">
                <div class="head">
                    <h3>Form Tambah Jadwal</h3>
                </div>

                {{-- ALERT SECTION --}}
                @if(session('error'))
                    <div
                        style="background:#fde2e2; border:1px solid #f5c2c2; padding:10px 15px; border-radius:8px; color:#b91c1c; margin-bottom:15px;">
                        <strong>⚠️ Terjadi Kesalahan:</strong> {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div
                        style="background:#fff3cd; border:1px solid #ffeeba; padding:10px 15px; border-radius:8px; color:#856404; margin-bottom:15px;">
                        <ul style="margin:0; padding-left:20px;">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- FORM TAMBAH JADWAL --}}
                <form action="{{ route('admin.jadwal.store') }}" method="POST" autocomplete="off">
                    @csrf

                    {{-- ID Jadwal --}}
                    <div class="mb-3">
                        <label for="id_penjadwalan">ID Jadwal</label>
                        <input type="text" id="id_penjadwalan" name="id_penjadwalan" value="{{ $newId }}" readonly
                            style="width:100%; padding:8px 10px; border-radius:6px; border:1px solid #ddd; background:#f5f5f5;">
                    </div>

                    {{-- Judul --}}
                    <div class="mb-3">
                        <label for="judul_kegiatan">Judul Kegiatan</label>
                        <input type="text" id="judul_kegiatan" name="judul_kegiatan" value="{{ old('judul_kegiatan') }}"
                            required style="width:100%; padding:8px 10px; border-radius:6px; border:1px solid #ddd;">
                    </div>

                    {{-- Tanggal --}}
                    <div class="mb-3">
                        <label for="tanggal">Tanggal</label>
                        <input type="date" id="tanggal" name="tanggal" value="{{ old('tanggal') }}" required
                            style="width:100%; padding:8px 10px; border-radius:6px; border:1px solid #ddd;">
                    </div>

                    {{-- Waktu Mulai --}}
                    <div class="mb-3">
                        <label for="waktu_mulai">Waktu Mulai</label>
                        <input type="time" id="waktu_mulai" name="waktu_mulai" value="{{ old('waktu_mulai') }}" required
                            style="width:100%; padding:8px 10px; border-radius:6px; border:1px solid #ddd;">
                    </div>

                    {{-- Waktu Selesai --}}
                    <div class="mb-3">
                        <label for="waktu_selesai">Waktu Selesai</label>
                        <input type="time" id="waktu_selesai" name="waktu_selesai" value="{{ old('waktu_selesai') }}"
                            required style="width:100%; padding:8px 10px; border-radius:6px; border:1px solid #ddd;">
                    </div>

                    {{-- Platform --}}
                    <div class="mb-3">
                        <label for="platform">Platform</label>
                        <select id="platform" name="platform" required
                            style="width:100%; padding:8px 10px; border-radius:6px; border:1px solid #ddd; background:#fff;">
                            <option value="">-- Pilih Platform --</option>
                            @foreach($platforms as $platform)
                                <option value="{{ $platform }}" {{ old('platform') == $platform ? 'selected' : '' }}>
                                    {{ $platform }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- keterangan --}}
                    <div class="mb-3">
                        <label for="keterangan">keterangan</label>
                        <input type="text" id="keterangan" name="keterangan" value="{{ old('keterangan') }}"
                            style="width:100%; padding:8px 10px; border-radius:6px; border:1px solid #ddd;">
                        <small id="keterangan_note" style="color: #777; font-size: 13px;">
                            Pilih platform terlebih dahulu.
                        </small>
                    </div>

                    {{-- Operator --}}
                    <div class="mb-3">
                        <label for="id_user">Operator</label>
                        <select id="id_user" name="id_user" required
                            style="width:100%; padding:8px 10px; border-radius:6px; border:1px solid #ddd; background:#fff;">
                            <option value="">-- Pilih Operator --</option>
                            @foreach($operators as $op)
                                <option value="{{ $op->id_user }}" {{ old('id_user') == $op->id_user ? 'selected' : '' }}>
                                    {{ $op->nama_user }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Tombol --}}
                    <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:18px;">
                        <a href="{{ route('admin.jadwal.index') }}"
                            style="padding:8px 14px; background:#e0e0e0; border-radius:6px; text-decoration:none; color:#222;">
                            Batal
                        </a>
                        <button type="submit"
                            style="padding:8px 14px; background:#3C91E6; color:#fff; border:none; border-radius:6px; cursor:pointer;">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
    {{-- Detail kecil untuk pengisian keterangan --}}
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const platformSelect = document.getElementById('platform');
            const note = document.getElementById('keterangan_note');
            const input = document.getElementById('keterangan');
            function updateNote() {
                const value = platformSelect.value;
                if (value.includes('Offline')) {
                    note.textContent = "Masukkan lokasi rapat (Gedung, Ruangan, Lantai, dll).";
                    input.placeholder = "Contoh: Gedung A Lt.2 Ruang Rapat 1";
                    input.type = "text";
                }
                else if (value.includes('Online')) {
                    note.textContent = "Masukkan link Zoom atau Google Meet.";
                    input.placeholder = "Contoh: https://zoom.us/j/xxxxxxx";
                    input.type = "url";
                }
                else {
                    note.textContent = "Pilih platform terlebih dahulu.";
                    input.placeholder = "";
                    input.type = "text";
                }
            }
            platformSelect.addEventListener('change', updateNote);
            updateNote();
        });
    </script>
@endsection