<?php

namespace App\Http\Controllers;

use App\Models\Penjadwalan;
use App\Models\User;
use Illuminate\Http\Request;

class PenjadwalanController extends Controller
{
    private $platforms = [
        'Zoom',
        'Google Meet',
        'Microsoft Teams',
        'Cisco Webex',
        'Skype',
        'Jitsi Meet',
        'BlueJeans',
        'GoToMeeting',
        'Zoho Meeting',
        'Slack Huddle',
        'BigBlueButton',
        'Whereby',
        'Discord',
        'Telegram Video Call',
        'WhatsApp Call',
        'Lainnya'
    ];

    
    public function index()
    {
        $jadwal = Penjadwalan::with('user')->get();
        return view('admin.jadwal.index', compact('jadwal'));
    }

    
    public function create()
    {
        $operators = User::where('role', 'operator')->get();

        $last = Penjadwalan::orderBy('id_penjadwalan', 'desc')->first();
        $newNumber = $last ? ((int) substr($last->id_penjadwalan, 2)) + 1 : 1;
        $newId = 'PJ' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        return view('admin.jadwal.create', [
            'operators' => $operators,
            'newId' => $newId,
            'platforms' => $this->platforms
        ]);
    }

    
    public function store(Request $request)
    {
        $request->merge([
            'waktu_mulai' => substr($request->waktu_mulai, 0, 5),
            'waktu_selesai' => substr($request->waktu_selesai, 0, 5),
        ]);

        $request->validate([
            'judul_kegiatan' => 'required|string|max:150',
            'tanggal' => 'required|date|after_or_equal:today',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
            'platform' => 'required|string|max:50',
            'id_user' => 'required|exists:users,id_user',
        ]);

        
        $bentrok = Penjadwalan::where('id_user', $request->id_user)
            ->whereDate('tanggal', $request->tanggal)
            ->where(function ($query) use ($request) {
                $query->whereBetween('waktu_mulai', [$request->waktu_mulai, $request->waktu_selesai])
                      ->orWhereBetween('waktu_selesai', [$request->waktu_mulai, $request->waktu_selesai])
                      ->orWhere(function ($q) use ($request) {
                          $q->where('waktu_mulai', '<=', $request->waktu_mulai)
                            ->where('waktu_selesai', '>=', $request->waktu_selesai);
                      });
            })
            ->exists();

        if ($bentrok) {
            return back()->withInput()->with('error', 'Operator ini sudah memiliki jadwal lain pada rentang waktu tersebut!');
        }

        $last = Penjadwalan::orderBy('id_penjadwalan', 'desc')->first();
        $newNumber = $last ? ((int) substr($last->id_penjadwalan, 2)) + 1 : 1;
        $newId = 'PJ' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        Penjadwalan::create([
            'id_penjadwalan' => $newId,
            'judul_kegiatan' => $request->judul_kegiatan,
            'tanggal' => $request->tanggal,
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_selesai' => $request->waktu_selesai,
            'platform' => $request->platform,
            'id_user' => $request->id_user,
        ]);

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $jadwal = Penjadwalan::findOrFail($id);
        $operators = User::where('role', 'operator')->get();

        return view('admin.jadwal.edit', [
            'jadwal' => $jadwal,
            'operators' => $operators,
            'platforms' => $this->platforms
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->merge([
            'waktu_mulai' => substr($request->waktu_mulai, 0, 5),
            'waktu_selesai' => substr($request->waktu_selesai, 0, 5),
        ]);

        $request->validate([
            'judul_kegiatan' => 'required|string|max:150',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
            'platform' => 'required|string|max:50',
            'id_user' => 'required|exists:users,id_user',
        ]);

        $bentrok = Penjadwalan::where('id_user', $request->id_user)
            ->whereDate('tanggal', $request->tanggal)
            ->where('id_penjadwalan', '!=', $id)
            ->where(function ($query) use ($request) {
                $query->whereBetween('waktu_mulai', [$request->waktu_mulai, $request->waktu_selesai])
                      ->orWhereBetween('waktu_selesai', [$request->waktu_mulai, $request->waktu_selesai])
                      ->orWhere(function ($q) use ($request) {
                          $q->where('waktu_mulai', '<=', $request->waktu_mulai)
                            ->where('waktu_selesai', '>=', $request->waktu_selesai);
                      });
            })
            ->exists();

        if ($bentrok) {
            return back()->withInput()->with('error', 'Operator ini sudah memiliki jadwal lain pada rentang waktu tersebut!');
        }
        $jadwal = Penjadwalan::findOrFail($id);

        $jadwal->update([
            'judul_kegiatan' => $request->judul_kegiatan,
            'tanggal' => $request->tanggal,
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_selesai' => $request->waktu_selesai,
            'platform' => $request->platform,
            'id_user' => $request->id_user,
        ]);

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $jadwal = Penjadwalan::findOrFail($id);
        $jadwal->delete();

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil dihapus!');
    }
}
