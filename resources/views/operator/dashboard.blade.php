@extends('layouts.operator')
@section('title', 'Dashboard Operator')
@section('content')
    <main>
        <!-- Judul Halaman -->
        <div class="head-title">
            <div class="left">
                <h1>Dashboard Operator</h1>
            </div>
        </div>
        <!-- Ringkasan -->
        <ul class="box-info">
            <li>
                <i class='bx bxs-calendar'></i>
                <span class="text">
                    <h3>{{ $jumlahJadwal }}</h3>
                    <p>Jadwal Saya</p>
                </span>
            </li>
            <li>
                <i class='bx bxs-check-circle'></i>
                <span class="text">
                    <h3>{{ $jumlahAbsensi }}</h3>
                    <p>Total Presensi</p>
                </span>
            </li>
            <li>
                <i class='bx bxs-wrench'></i>
                <span class="text">
                    <h3>{{ $jumlahPeralatan }}</h3>
                    <p>Jumlah Peralatan</p>
                </span>
            </li>
        </ul>
        <!-- Grafik & Jadwal -->
        <div class="table-data" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <!-- Grafik Absensi -->
            <div class="order" style="min-height: 280px;">
                <div class="head">
                    <h3>Grafik Presensi Saya</h3>
                </div>
                <div style="height:220px; display:flex; align-items:center; justify-content:center;">
                    <canvas id="absensiChart"></canvas>
                </div>
            </div>
            <!-- Jadwal Terdekat -->
            <div class="order">
                <div class="head">
                    <h3>Jadwal Terdekat</h3>
                </div>
                <div style="max-height:280px; overflow-y:auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>Judul</th>
                                <th>Jadwal</th>
                                <th>Waktu</th>
                                <th>Platform</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jadwalTerdekat as $j)
                                <tr>
                                    <td>{{ $j->penjadwalan->judul_kegiatan }}</td>
                                    <td>{{ \Carbon\Carbon::parse($j->penjadwalan->tanggal)->translatedFormat('d F Y') }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($j->penjadwalan->waktu_mulai)->format('H:i') }}
                                        -
                                        {{ \Carbon\Carbon::parse($j->penjadwalan->waktu_selesai)->format('H:i') }}
                                    </td>
                                    <td>{{ $j->penjadwalan->platform }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Belum ada jadwal</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('scripts')
    <script>
        new Chart(document.getElementById('absensiChart').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Hadir', 'Izin', 'Sakit', 'Tidak Hadir'],
                datasets: [{
                    data: [{{ $hadir }}, {{ $izin }}, {{ $sakit }}, {{ $tidakHadir }}],
                    backgroundColor: ['#3C91E6', '#FFCE26', '#FD7238', '#DB504A']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                },
                animation: {
                    animateScale: true,
                    animateRotate: true,
                    duration: 1200
                }
            }
        });
    </script>
@endsection