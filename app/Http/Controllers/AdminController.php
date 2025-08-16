<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Penjadwalan;
use App\Models\Absensi;

class AdminController extends Controller
{
    public function dashboard()
    {
       $jumlahOperator     = User::where('role', 'operator')->count();
$jumlahPenjadwalan  = Penjadwalan::count(); // <- konsisten 'penjadwalan'
$jumlahPeralatan    = 8;

$absensi = Absensi::selectRaw('status, COUNT(*) as total')
    ->groupBy('status')
    ->pluck('total', 'status')
    ->toArray();

$hadir      = $absensi['hadir'] ?? 0;
$izin       = $absensi['izin'] ?? 0;
$sakit      = $absensi['sakit'] ?? 0;
$tidakHadir = $absensi['tidak_hadir'] ?? 0;

$operatorData = User::where('role', 'operator')
    ->withCount('penjadwalan') // sesuai relasi di model User
    ->get();

$operatorLabels = $operatorData->pluck('nama_user');
$operatorCounts = $operatorData->pluck('penjadwalan_count');

return view('admin.dashboard', compact(
    'jumlahOperator',
    'jumlahPenjadwalan',
    'jumlahPeralatan',
    'hadir',
    'izin',
    'sakit',
    'tidakHadir',
    'operatorLabels',
    'operatorCounts',
    'operatorData'
));


    }
}
