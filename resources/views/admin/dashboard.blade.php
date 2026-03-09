@extends('layouts.admin')
@section('title', 'Dashboard')
@section('content')
    <main>
        <div class="head-title">
            <div class="left">
                <h1>Dashboard</h1>
            </div>
        </div>
        <ul class="box-info">
            <li>
                <i class='bx bxs-user'></i>
                <span class="text">
                    <h3>{{ $jumlahOperator }}</h3>
                    <p>Jumlah Operator</p>
                </span>
            </li>
            <li>
                <i class='bx bxs-calendar'></i>
                <span class="text">
                    <h3>{{ $jumlahPenjadwalan }}</h3>
                    <p>Jumlah Jadwal</p>
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
        <div class="table-data"
            style="display:grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap:20px;">
            <div class="order" style="padding:15px; border-radius:10px; background:#fff;">
                <div class="head">
                    <h3>Grafik Absensi</h3>
                </div>
                <canvas id="absensiChart" style="max-height:280px;"></canvas>
            </div>
            <div class="order" style="padding:15px; border-radius:10px; background:#fff;">
                <div class="head">
                    <h3>Statistik Penjadwalan Operator</h3>
                </div>
                <canvas id="operatorChart" style="max-height:280px;"></canvas>
            </div>
            <div class="order" style="padding:15px; border-radius:10px; background:#fff;">
                <div class="head">
                    <h3>Tren Absensi Harian</h3>
                </div>
                <canvas id="trenAbsensiChart" style="max-height:280px;"></canvas>
            </div>
        </div>
    </main>
@endsection
@section('scripts')
    <script>
        function generatePastelColors(count) {
            const colors = [];
            for (let i = 0; i < count; i++) {
                const r = Math.floor((Math.random() * 127) + 100);
                const g = Math.floor((Math.random() * 127) + 100);
                const b = Math.floor((Math.random() * 127) + 100);
                colors.push(`rgba(${r}, ${g}, ${b}, 0.7)`);
            }
            return colors;
        }
        const operatorChart = new Chart(document.getElementById('operatorChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($operatorLabels) !!},
                datasets: [{
                    label: "Jumlah Jadwal",
                    backgroundColor: generatePastelColors({!! count($operatorLabels) !!}),
                    data: {!! json_encode($operatorCounts) !!}
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 1500,
                    easing: 'easeOutBounce'
                },
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 } }
                }
            }
        });
        const absensiChart = new Chart(document.getElementById('absensiChart').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Hadir', 'Izin', 'Sakit', 'Alpha'],
                datasets: [{
                    data: [{{ $hadir }}, {{ $izin }}, {{ $sakit }}, {{ $alpha }}],
                    backgroundColor: ['#36A2EB', '#FFCE56', '#FF6384', '#9966FF']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    animateScale: true,
                    animateRotate: true,
                    duration: 1200
                }
            }
        });
        const trenAbsensiChart = new Chart(document.getElementById('trenAbsensiChart').getContext('2d'), {
            type: 'line',
            data: {
                labels: {!! json_encode($tanggalLabels) !!},
                datasets: [
                    { label: 'Hadir', data: {!! json_encode($hadirData) !!}, borderColor: '#36A2EB', fill: false },
                    { label: 'Izin', data: {!! json_encode($izinData) !!}, borderColor: '#FFCE56', fill: false },
                    { label: 'Sakit', data: {!! json_encode($sakitData) !!}, borderColor: '#FF6384', fill: false },
                    { label: 'Alpha', data: {!! json_encode($alphaData) !!}, borderColor: '#9966FF', fill: false }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 1500,
                    easing: 'easeOutQuart'
                }
            }
        });
    </script>
@endsection