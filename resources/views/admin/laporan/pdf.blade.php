<!DOCTYPE html>
<html>
<head>
    <title>Laporan Presensi Operator</title>
    <style>
        body {
            font-family: "Times New Roman", serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
        }
        .header h2 {
            margin: 0;
            text-transform: uppercase;
        }
        .header p {
            margin: 2px 0;
        }
        .info {
            margin-top: 10px;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
        }
        th {
            text-align: center;
            background: #eee;
        }
        .ttd {
            margin-top: 40px;
            width: 100%;
        }
        .ttd td {
            border: none;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="header">
    <h2>LAPORAN PRESENSI OPERATOR</h2>
    <p>Sistem Penjadwalan dan Monitoring Kegiatan</p>
</div>

<div class="info">
    <p>
        Periode:
        {{ request('start') ? \Carbon\Carbon::parse(request('start'))->format('d/m/Y') : '-' }}
        s/d
        {{ request('end') ? \Carbon\Carbon::parse(request('end'))->format('d/m/Y') : '-' }}
    </p>
    <p>Tanggal Cetak: {{ now()->format('d/m/Y H:i') }}</p>
</div>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Kegiatan</th>
            <th>Jadwal</th>
            <th>Operator</th>
            <th>Status Presensi</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        @forelse($absensi as $index => $absen)
        <tr>
            <td style="text-align:center;">{{ $index + 1 }}</td>

            <td>{{ $absen->penjadwalan->judul_kegiatan ?? '-' }}</td>

            <td>
                {{ $absen->tanggal?->format('d/m/Y') ?? '-' }}<br>
                {{ \Carbon\Carbon::parse($absen->penjadwalan->waktu_mulai)->format('H:i') }}
                -
                {{ \Carbon\Carbon::parse($absen->penjadwalan->waktu_selesai)->format('H:i') }}
            </td>

            <td>{{ $absen->user->nama_user ?? '-' }}</td>

            <td style="text-align:center;">
                @switch($absen->status)
                    @case('pending') Pending @break
                    @case('hadir') Hadir @break
                    @case('izin') Izin @break
                    @case('sakit') Sakit @break
                    @case('sakit_disetujui') Sakit (Disetujui) @break
                    @case('izin_disetujui') Izin (Disetujui) @break
                    @case('alpha') Alpha @break
                    @case('ditolak') Ditolak @break
                    @default Tidak Diketahui
                @endswitch
            </td>

            <td>{{ $absen->keterangan ?? '-' }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="6" align="center">Tidak terdapat data presensi</td>
        </tr>
        @endforelse
    </tbody>
</table>
</body>
</html>