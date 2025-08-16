@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
 <h4 class="h4 mb-4 text-gray-800" style="font-family: 'Nunito', sans-serif;">@yield('title')</h4>
<div class="row">

    <!-- Operator -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Jumlah Operator
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $jumlahOperator }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-tie fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Jadwal -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Jumlah Jadwal
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $jumlahPenjadwalan }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Peralatan -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Jumlah Peralatan
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $jumlahPeralatan }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-tools fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Grafik -->
<div class="row">
    <div class="col-xl-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Grafik Absensi</h6>
            </div>
            <div class="card-body">
                <canvas id="absensiChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Seberapa Sering Jadi Operator</h6>
            </div>
            <div class="card-body">
                <canvas id="operatorChart"></canvas>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    new Chart(document.getElementById('absensiChart'), {
        type: 'doughnut',
        data: {
            labels: ['Hadir', 'Izin', 'Sakit', 'Tidak Hadir'],
            datasets: [{
                data: [{{ $hadir }}, {{ $izin }}, {{ $sakit }}, {{ $tidakHadir }}],
                backgroundColor: ['#1A237E', '#0D1B2A', '#2E2E2E', '#B0BEC5'],
            }]
        }
    });

    new Chart(document.getElementById('operatorChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($operatorLabels) !!},
            datasets: [{
                label: "Jumlah Penjadwalan",
                backgroundColor: "#1A237E",
                data: {!! json_encode($operatorCounts) !!}
            }]
        }
    });
</script>
@endsection
