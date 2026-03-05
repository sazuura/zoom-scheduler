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
                            value="{{ old('waktu_mulai', \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('H:i')) }}"
                            required
                            style="width:100%; padding:8px 10px; border-radius:6px; border:1px solid #ddd; background:#fff;">
                    </div>

                    {{-- Waktu Selesai --}}
                    <div class="mb-3" style="margin-bottom:12px;">
                        <label>Waktu Selesai</label>
                        <input type="time" name="waktu_selesai"
                            value="{{ old('waktu_selesai', \Carbon\Carbon::parse($jadwal->waktu_selesai)->format('H:i')) }}"
                            required
                            style="width:100%; padding:8px 10px; border-radius:6px; border:1px solid #ddd; background:#fff;">
                    </div>

                    {{-- Platform --}}
                    <div class="mb-3" style="margin-bottom:12px;">
                        <label>Platform</label>
                        <select name="platform" id="platform"required
                            style="width:100%; padding:8px 10px; border-radius:6px; border:1px solid #ddd; background:#fff;">
                            @foreach($platforms as $platform)
                                <option value="{{ $platform }}" {{ old('platform', $jadwal->platform) == $platform ? 'selected' : '' }}>
                                    {{ $platform }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- keterangan --}}
                    <div class="mb-3">
                        <label for="keterangan">keterangan</label>
                        <input type="text" name="keterangan" id="keterangan"
                            value="{{ old('keterangan', $jadwal->keterangan) }}" required
                            style="width:100%; padding:8px 10px; border-radius:6px; border:1px solid #ddd;">
                        <small id="keterangan_note" style="color: #777; font-size: 13px;">
                            Pilih platform terlebih dahulu.
                        </small>
                    </div>

                    {{-- Operator --}}
                    <div class="mb-3">
                        <label>Operator</label>
                        <div id="operator-wrapper">
                            @foreach($selectedOperators as $opId)
                                <div class="operator-item" style="display:flex; gap:10px; margin-bottom:8px;">
                                    <select name="id_user[]" style="flex:2; padding:8px; border-radius:6px; border:1px solid #ddd;">
                                        <option value="">-- Pilih Operator --</option>
                                            @foreach($operators as $op)
                                                <option value="{{ $op->id_user }}"{{ $opId == $op->id_user ? 'selected' : '' }}>
                                                    {{ $op->nama_user }}
                                                </option>
                                            @endforeach
                                    </select>
                                    <button type="button" class="remove-operator" style="background:#e74c3c;color:white;border:none;border-radius:6px;padding:8px;">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                            <button type="button" id="add-operator" style="margin-top:8px; padding:6px 10px; background:#3C91E6; color:white; border:none; border-radius:6px;">
                                + Tambah Operator
                            </button>
                    </div>

                    {{-- Peralatan --}}
                    <div class="mb-3">
                        <label>Peralatan yang Digunakan</label>
                        <div id="peralatan-wrapper">
                            @foreach($jadwal->jadwalPeralatan as $alat)
                                <div class="peralatan-item" style="display:flex; gap:10px; margin-bottom:8px;">
                                    <select name="peralatan[]" style="flex:2; padding:8px; border-radius:6px; border:1px solid #ddd;">
                                        <option value="">-- Pilih Peralatan --</option>
                                            @foreach($peralatans as $p)
                                                <option value="{{ $p->id_peralatan }}" {{ $p->id_peralatan == $alat->id_peralatan ? 'selected' : '' }}>
                                                    {{ $p->nama_peralatan }} (stok: {{ $p->stok_tersedia }})
                                                </option>
                                            @endforeach
                                    </select>
                                    <input type="number" name="jumlah[]" value="{{ $alat->jumlah }}" min="1" style="width:100px; padding:8px; border-radius:6px; border:1px solid #ddd;">
                                        <button type="button" class="remove-peralatan" style="background:#e74c3c;color:white;border:none;border-radius:6px;padding:8px;">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                </div>
                            @endforeach
                        </div>
                            <button type="button" id="add-peralatan" style="margin-top:8px; padding:6px 10px; background:#3C91E6; color:white; border:none; border-radius:6px;">
                                + Tambah Peralatan
                            </button>
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
    <script>
        // Detail kecil untuk pengisian keterangan
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
        // Detail kecil untuk pengisian operator
        const operatorWrapper = document.getElementById('operator-wrapper');
        const addOperatorBtn = document.getElementById('add-operator');
        addOperatorBtn.addEventListener('click', function () {
            const item = document.querySelector('.operator-item').cloneNode(true);
            item.querySelector('select').value = "";
            operatorWrapper.appendChild(item);

        });
        document.addEventListener('click', function (e) {
            if (e.target.closest('.remove-operator')) {
                if (document.querySelectorAll('.operator-item').length > 1) {
                    e.target.closest('.operator-item').remove();
                }
            }
        });
        // Detail kecil untuk pengisian peralatan
        const alatWrapper = document.getElementById('peralatan-wrapper');
        const addAlatBtn = document.getElementById('add-peralatan');
        addAlatBtn.addEventListener('click', function () {
            const item = document.querySelector('.peralatan-item').cloneNode(true);
            item.querySelector('select').value = "";
            item.querySelector('input').value = "";
            alatWrapper.appendChild(item);
        });
        document.addEventListener('click', function (e) {
            if (e.target.closest('.remove-peralatan')) {
                if (document.querySelectorAll('.peralatan-item').length > 1) {
                    e.target.closest('.peralatan-item').remove();
                }
            }
        });
    </script>
@endsection