<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penjadwalan</title>
    <style>
        body { font-family: sans-serif; font-size:12px; }
        table { width:100%; border-collapse: collapse; margin-top:20px; }
        th, td { border:1px solid #444; padding:6px; text-align:left; }
        th { background:#f2f2f2; }
    </style>
</head>
<body>
    <h2>Laporan Penjadwalan</h2>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Judul Kegiatan</th>
                <th>Operator</th>
                <th>Waktu</th>
                <th>Status</th>
                <th>Validasi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($jadwal as $j)
                @php $absen = $j->absensi->first(); @endphp
                <tr>
                    <td>{{ $j->tanggal }}</td>
                    <td>{{ $j->judul_kegiatan }}</td>
                    <td>{{ $j->user->nama_user ?? '-' }}</td>
                    <td>{{ $j->waktu_mulai }} - {{ $j->waktu_selesai }}</td>
                    <td>{{ $absen ? ucfirst($absen->status) : 'Belum Absen' }}</td>
                    <td>{{ $absen ? ($absen->validated ? 'Valid' : 'Menunggu') : '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
