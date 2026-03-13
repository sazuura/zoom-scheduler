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
                <form action="{{ route('admin.jadwal.store') }}" method="POST" autocomplete="off">
                    @csrf
                    <div class="mb-3">
                        <label for="id_penjadwalan">ID Jadwal</label>
                        <input type="text" id="id_penjadwalan" name="id_penjadwalan" value="{{ $newId }}" readonly
                            style="width:100%; padding:8px 10px; border-radius:6px; border:1px solid #ddd; background:#f5f5f5;">
                    </div>
                    <div class="mb-3">
                        <label for="judul_kegiatan">Judul Kegiatan</label>
                        <input type="text" id="judul_kegiatan" name="judul_kegiatan" value="{{ old('judul_kegiatan') }}"
                            required style="width:100%; padding:8px 10px; border-radius:6px; border:1px solid #ddd;">
                    </div>
                    <div class="mb-3">
                        <label for="tanggal">Tanggal</label>
                        <input type="date" id="tanggal" name="tanggal" value="{{ old('tanggal') }}" required
                            style="width:100%; padding:8px 10px; border-radius:6px; border:1px solid #ddd;">
                    </div>
                    <div class="mb-3">
                        <label for="waktu_mulai">Waktu Mulai</label>
                        <input type="time" id="waktu_mulai" name="waktu_mulai" value="{{ old('waktu_mulai') }}" required
                            style="width:100%; padding:8px 10px; border-radius:6px; border:1px solid #ddd;">
                    </div>
                    <div class="mb-3">
                        <label for="waktu_selesai">Waktu Selesai</label>
                        <input type="time" id="waktu_selesai" name="waktu_selesai" value="{{ old('waktu_selesai') }}"
                            required style="width:100%; padding:8px 10px; border-radius:6px; border:1px solid #ddd;">
                    </div>
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
                    <div class="mb-3">
                        <label for="keterangan">keterangan</label>
                        <input type="text" id="keterangan" name="keterangan" value="{{ old('keterangan') }}"
                            style="width:100%; padding:8px 10px; border-radius:6px; border:1px solid #ddd;">
                        <small id="keterangan_note" style="color: #777; font-size: 13px;">
                            Pilih platform terlebih dahulu.
                        </small>
                    </div>
                    <div class="mb-3">
                        <label>Operator</label>
                        <div id="operator-wrapper">
                            <div class="operator-item" style="display:flex; gap:10px; margin-bottom:8px;">
                                <select name="id_user[]"
                                    style="flex:2; padding:8px; border-radius:6px; border:1px solid #ddd;">
                                    <option value="" disabled selected>-- Pilih Operator --</option>
                                    @foreach($operators as $op)
                                        <option value="{{ $op->id_user }}">
                                            {{ $op->nama_user }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="button" class="remove-operator"
                                    style="background:#e74c3c;color:white;border:none;border-radius:6px;padding:8px;">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </div>
                        </div>
                        <button type="button" id="add-operator"
                            style="margin-top:8px; padding:6px 10px; background:#3C91E6; color:white; border:none; border-radius:6px;">
                            + Tambah Operator
                        </button>
                    </div>
                    <div class="mb-3">
                        <label>Peralatan yang Digunakan</label>
                        <div id="peralatan-wrapper">
                            <div class="peralatan-item" style="display:flex; gap:10px; margin-bottom:8px;">
                                <select name="peralatan[]"
                                    style="flex:2; padding:8px; border-radius:6px; border:1px solid #ddd;">
                                    <option value="" disabled selected>-- Pilih Peralatan --</option>
                                    @foreach($peralatans as $alat)
                                        <option value="{{ $alat->id_peralatan }}">
                                            {{ $alat->nama_peralatan }} (stok: {{ $alat->stok_tersedia }})
                                        </option>
                                    @endforeach
                                </select>
                                <input type="number" name="jumlah[]" min="1" placeholder="Jumlah"
                                    style="width:100px; padding:8px; border-radius:6px; border:1px solid #ddd;">
                                <button type="button" class="remove-peralatan"
                                    style="background:#e74c3c;color:white;border:none;border-radius:6px;padding:8px;">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </div>
                        </div>
                        <button type="button" id="add-peralatan"
                            style="margin-top:8px; padding:6px 10px; background:#3C91E6; color:white; border:none; border-radius:6px;">
                            + Tambah Peralatan
                        </button>
                    </div>

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
    <script>

        document.addEventListener("DOMContentLoaded", function () {

            /* ===============================
               PLATFORM NOTE
            =============================== */

            const platformSelect = document.getElementById('platform');
            const note = document.getElementById('keterangan_note');
            const input = document.getElementById('keterangan');

            function updateNote() {

                const value = platformSelect.value;

                if (value.includes('Offline')) {

                    note.textContent = "Masukkan lokasi rapat (Gedung, Ruangan, Lantai, dll).";
                    input.placeholder = "Contoh: Gedung A Lt.2 Ruang Rapat 1";
                    input.type = "text";

                } else if (value.includes('Online')) {

                    note.textContent = "Masukkan link Zoom atau Google Meet.";
                    input.placeholder = "Contoh: https://zoom.us/j/xxxxxxx";
                    input.type = "url";

                } else {

                    note.textContent = "Pilih platform terlebih dahulu.";
                    input.placeholder = "";
                    input.type = "text";

                }

            }

            platformSelect.addEventListener('change', updateNote);
            updateNote();


            /* ===============================
               OPERATOR FILTER
            =============================== */

            function updateOperatorOptions() {

                let selected = [];

                document.querySelectorAll("select[name='id_user[]']").forEach(sel => {
                    if (sel.value) {
                        selected.push(sel.value);
                    }
                });

                document.querySelectorAll("select[name='id_user[]']").forEach(sel => {

                    Array.from(sel.options).forEach(opt => {

                        if (opt.value === "") {
                            opt.disabled = false;
                            return;
                        }

                        if (selected.includes(opt.value) && sel.value !== opt.value) {
                            opt.disabled = true;
                        } else {
                            opt.disabled = false;
                        }

                    });

                });

            }


            /* ===============================
               PERALATAN FILTER
            =============================== */

            function updatePeralatanOptions() {

                let selected = [];

                document.querySelectorAll("select[name='peralatan[]']").forEach(sel => {
                    if (sel.value) {
                        selected.push(sel.value);
                    }
                });

                document.querySelectorAll("select[name='peralatan[]']").forEach(sel => {

                    Array.from(sel.options).forEach(opt => {

                        if (opt.value === "") {
                            opt.disabled = false;
                            return;
                        }

                        if (selected.includes(opt.value) && sel.value !== opt.value) {
                            opt.disabled = true;
                        } else {
                            opt.disabled = false;
                        }

                    });

                });

            }


            /* ===============================
               TAMBAH PERALATAN
            =============================== */

            const wrapper = document.getElementById('peralatan-wrapper');
            const addBtn = document.getElementById('add-peralatan');

            addBtn.addEventListener('click', function () {

                const item = document.querySelector('.peralatan-item').cloneNode(true);

                item.querySelector('select').value = "";
                item.querySelector('input').value = "";

                wrapper.appendChild(item);

                updatePeralatanOptions();

            });


            /* ===============================
               TAMBAH OPERATOR
            =============================== */

            const operatorWrapper = document.getElementById('operator-wrapper');
            const addOperatorBtn = document.getElementById('add-operator');

            addOperatorBtn.addEventListener('click', function () {

                const item = document.querySelector('.operator-item').cloneNode(true);

                item.querySelector('select').value = "";

                operatorWrapper.appendChild(item);

                updateOperatorOptions();

            });


            /* ===============================
               REMOVE ITEM
            =============================== */

            document.addEventListener('click', function (e) {

                if (e.target.closest('.remove-peralatan')) {

                    if (document.querySelectorAll('.peralatan-item').length > 1) {

                        e.target.closest('.peralatan-item').remove();
                        updatePeralatanOptions();

                    }

                }

                if (e.target.closest('.remove-operator')) {

                    if (document.querySelectorAll('.operator-item').length > 1) {

                        e.target.closest('.operator-item').remove();
                        updateOperatorOptions();

                    }

                }

            });


            /* ===============================
               SELECT CHANGE EVENT
            =============================== */

            document.addEventListener("change", function (e) {

                if (e.target.name === "id_user[]") {
                    updateOperatorOptions();
                }

                if (e.target.name === "peralatan[]") {
                    updatePeralatanOptions();
                }

            });


            /* ===============================
               INIT
            =============================== */

            updateOperatorOptions();
            updatePeralatanOptions();

        });
    </script>
@endsection