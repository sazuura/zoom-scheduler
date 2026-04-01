<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Penjadwalan;
use App\Models\Peralatan;
use App\Models\Absensi;
use App\Models\JadwalPeralatan;

class OperatorController extends Controller
{
    public function dashboard()
    {
        $userId = auth()->user()->id_user;
        $jumlahJadwal = Absensi::where('id_user', $userId)->count();
        $jumlahAbsensi = Absensi::where('id_user', $userId)->where('status', 'hadir')->count();
        $jumlahPeralatan = JadwalPeralatan::with([
            'peralatan',
            'penjadwalan'
        ])
        ->whereHas('penjadwalan.absensi', function ($q) use ($userId) {
            $q->where('id_user', $userId);
        })-> count();
        $absensi = Absensi::where('id_user', $userId)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();
        $hadir = $absensi['hadir'] ?? 0;
        $izin = ($absensi['izin'] ?? 0) + ($absensi['izin_disetujui'] ?? 0);
        $sakit = ($absensi['sakit'] ?? 0) + ($absensi['sakit_disetujui'] ?? 0);
        $tidakHadir = ($absensi['alpha'] ?? 0) + ($absensi['ditolak'] ?? 0);
        $jadwalTerdekat = Absensi::where('id_user', $userId)
            ->orderBy('tanggal','desc')
            ->limit(5)
            ->get();
        return view('operator.dashboard', compact(
            'jumlahJadwal',
            'jumlahAbsensi',
            'jumlahPeralatan',
            'hadir',
            'izin',
            'sakit',
            'tidakHadir',
            'jadwalTerdekat'
        ));
    }
    public function jadwal()
    {
        $jadwal = Penjadwalan::with('user')
            ->where('id_user', auth()->user()->id_user)
            ->orderBy('tanggal', 'desc')
            ->get();
        return view('operator.jadwal.index', compact('jadwal'));
    }
    public function absensi()
    {
        $userId = auth()->user()->id_user;
        $now = Carbon::now('Asia/Jakarta');
        $absensi = Absensi::with('penjadwalan')
            ->where('id_user', $userId)
            ->get();
        foreach ($absensi as $a) {
            $end = $a->penjadwalan->endDateTime ?? null;
            if ($end && $now->gt($end)) {
                if (!in_array($a->status, ['hadir','izin','sakit','alpha','izin_disetujui','sakit_disetujui'])) {
                    $a->update([
                        'status' => 'alpha',
                        'keterangan' => 'Tidak melakukan presensi'
                    ]);
                }
            }
        }
        $hariini = Carbon::now('Asia/Jakarta')->toDateString();
        $jadwalHariIni = Absensi::with([
            'penjadwalan.jadwalPeralatan.peralatan',
            'dokumentasi'
        ])
            ->where('id_user', $userId)
            ->whereDate('tanggal', '>=', $hariini)
            ->orderByDesc('created_at')
            ->get();
        $absensiSaya = Absensi::with([
            'penjadwalan',
            'dokumentasi'
        ])
            ->where('id_user', $userId)
            ->orderBy('tanggal','desc')
            ->paginate(5)
            ->withQueryString();
        return view('operator.absensi.index', compact(
            'jadwalHariIni',
            'absensiSaya',
        ));
    }
    public function absensiStore(Request $request)
    {
        $request->validate([
            'id_absensi' => 'required|exists:absensi,id_absensi',
            'status' => 'required|string',
            'keterangan' => 'nullable|string'
        ]);

        $absensi = Absensi::with('penjadwalan')->findOrFail($request->id_absensi);
        $jadwal = $absensi->penjadwalan;

    $now = Carbon::now('Asia/Jakarta');
    $start = $jadwal->startDateTime;
    $end = $jadwal->endDateTime;

    $today = $now->toDateString();
    $hMinus1 = $start->copy()->subDay()->toDateString();

    // Status 'hadir' hanya untuk jadwal hari ini
    if ($request->status == 'hadir') {
        if ($today != $start->toDateString()) {
            return back()->with('error', 'Hadir hanya bisa untuk jadwal hari ini.');
        }
        if (!$now->between($start, $end)) {
            return back()->with('error', 'Hadir hanya bisa saat jadwal berlangsung ('.$start->format('H:i').' - '.$end->format('H:i').').');
        }
    }

    // Status 'izin' atau 'sakit' hanya bisa H-1 atau lebih
    if (in_array($request->status, ['izin', 'sakit'])) {
        if ($today > $hMinus1) {
            return back()->with('error', 'Izin / Sakit hanya bisa diajukan H-1 sebelum jadwal.');
        }
    }

        // Update absensi
        $absensi->update([
            'status' => $request->status,
            'keterangan' => $request->keterangan
        ]);

        return redirect()
            ->route('operator.absensi.index')
            ->with('success', 'Absensi berhasil diperbarui.');
    }
    public function absensiCancel($id)
    {
        $absen = Absensi::findOrFail($id);

        if ($absen->id_user !== auth()->user()->id_user) {
            abort(403);
        }

        if (in_array($absen->status, [
            'izin_disetujui',
            'sakit_disetujui',
            'alpha'
        ])) {
            return back()->with(
                'error',
                'Absensi sudah divalidasi admin dan tidak dapat dibatalkan.'
            );
        }

        $absen->delete();

        return back()->with('success', 'Absensi dibatalkan.');
    }
    public function peralatan()
    {
        $userId = auth()->user()->id_user;
        $peralatan = JadwalPeralatan::with([
            'peralatan',
            'penjadwalan'
        ])
        ->whereHas('penjadwalan.absensi', function ($q) use ($userId) {
            $q->where('id_user', $userId);
        })
        ->orderByDesc('id_jadwal_alat')
        ->get();
        return view('operator.peralatan.index', compact('peralatan'));
    }
    public function peralatanUpdate($id)
    {
        $alat = JadwalPeralatan::findOrFail($id);
        $alat->update([
            'status_pemasangan' => 'sudah_dipasang'
        ]);
        return back()->with('success','Peralatan berhasil divalidasi.');
    }
}