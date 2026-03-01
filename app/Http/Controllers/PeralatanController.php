<?php

namespace App\Http\Controllers;

use App\Models\Peralatan;
use Illuminate\Http\Request;

class PeralatanController extends Controller
{
    public function index()
    {
        $peralatan = Peralatan::all();
        return view('admin.peralatan.index', compact('peralatan'));
    }

    public function create()
    {
    
        $last = Peralatan::orderBy('id_peralatan', 'desc')->first();

        if ($last) {
            $lastNumber = (int) substr($last->id_peralatan, 3);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        $newId = 'PR-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        return view('admin.peralatan.create', compact('newId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_peralatan' => 'required|string|max:100',
            'kondisi' => 'required|in:Baik,Diperbaiki,Rusak',
            'stok' => 'required|integer|min:0',
        ]);

        
        $last = Peralatan::orderBy('id_peralatan', 'desc')->first();
        if ($last) {
            $lastNumber = (int) substr($last->id_peralatan, 3);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        $newId = 'PR-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        Peralatan::create([
            'id_peralatan' => $newId,
            'nama_peralatan' => $request->nama_peralatan,
            'kondisi' => $request->kondisi,
            'stok' => $request->stok,
        ]);

        return redirect()->route('admin.peralatan.index')->with('success', 'Peralatan berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $peralatan = Peralatan::findOrFail($id);
        return view('admin.peralatan.edit', compact('peralatan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_peralatan' => 'required|string|max:100',
            'kondisi' => 'required|in:Baik,Diperbaiki,Rusak',
            'stok' => 'required|integer|min:0',
        ]);

        $peralatan = Peralatan::findOrFail($id);
        $peralatan->update([
            'nama_peralatan' => $request->nama_peralatan,
            'kondisi' => $request->kondisi,
            'stok' => $request->stok,
        ]);

        return redirect()->route('admin.peralatan.index')->with('success', 'Peralatan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $peralatan = Peralatan::findOrFail($id);
        $peralatan->delete();

        return redirect()->route('admin.peralatan.index')->with('success', 'Peralatan berhasil dihapus!');
    }
}
