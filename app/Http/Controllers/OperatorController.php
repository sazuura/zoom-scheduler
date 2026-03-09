<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Penjadwalan;
use App\Models\Peralatan;
use App\Models\Absensi;

class OperatorController extends Controller
{
    public function dashboard()
    {
        $userId = auth()->user()->id_user;
        $jumlahJadwal = Penjadwalan::where('id_user', $userId)->count();
        $jumlahAbsensi = Absensi::where('id_user', $userId)->count();
        $jumlahPeralatan = Peralatan::count();
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

    $jadwalHariIni = Absensi::with([
        'penjadwalan.jadwalPeralatan.peralatan',
        'dokumentasi'
        ])
        ->where('id_user', $userId)
        ->whereDate('tanggal', today())
        ->orderByDesc('created_at')
        ->get();

        $absensiSaya = Absensi::with([
            'penjadwalan',
            'dokumentasi'
        ])
        ->where('id_user', $userId)
        ->orderBy('tanggal','desc')
        ->get();

        return view('operator.absensi.index', compact(
            'jadwalHariIni',
            'absensiSaya'
        ));
    }
    public function absensiStore(Request $request)
    {
        $request->validate([
            'id_penjadwalan' => 'required|exists:penjadwalan,id_penjadwalan',
            'status' => 'required|string',
            'keterangan' => 'nullable|string'
        ]);

        $userId = auth()->user()->id_user;
        $jadwalId = $request->id_penjadwalan;
        $today = today()->toDateString();
        $now = Carbon::now('Asia/Jakarta');

        $jadwal = Penjadwalan::findOrFail($jadwalId);

        $start = $jadwal->startDateTime;
        $end = $jadwal->endDateTime;

        if (!$start || !$end) {
            return back()->with('error', 'Data waktu jadwal tidak valid.');
        }

        if ($today !== $jadwal->tanggal->toDateString()) {
            return back()->with('error', 'Anda hanya bisa absen pada tanggal jadwal.');
        }

        if (!$now->between($start, $end)) {
            return back()->with(
                'error',
                'Absensi hanya bisa pada jam '.$start->format('H:i').' - '.$end->format('H:i')
            );
        }

        $exists = Absensi::where('id_user', $userId)
            ->where('id_penjadwalan', $jadwalId)
            ->whereDate('tanggal', $today)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Anda sudah absen untuk jadwal ini.');
        }

        Absensi::create([
            'id_user' => $userId,
            'id_penjadwalan' => $jadwalId,
            'tanggal' => $today,
            'status' => $request->status,
            'keterangan' => $request->keterangan
        ]);

        return redirect()
            ->route('operator.absensi.index')
            ->with('success', 'Absensi berhasil disimpan.');
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
}