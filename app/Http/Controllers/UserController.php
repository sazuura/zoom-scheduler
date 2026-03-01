<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    public function store(Request $request)
{
    $request->validate([
        'nama_user' => 'required|string|max:100',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6',
        'role' => 'required|in:admin,operator',
    ]);

    $last = User::orderBy('id_user', 'desc')->first();
    $newNumber = $last ? ((int) substr($last->id_user, 2)) + 1 : 1;
    $newId = 'US' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

    $user = User::create([
        'id_user' => $newId,
        'nama_user' => $request->nama_user,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'role' => $request->role,
    ]);

    \Log::info('User baru dibuat:', $user->toArray());

    return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan!');
}


    public function create()
    {
        $last = User::orderBy('id_user', 'desc')->first();
        if ($last) {
            $lastNumber = (int) substr($last->id_user, 2);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        $newId = 'US' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        return view('admin.users.create', compact('newId'));
    }



    public function edit($id_user)
    {
        $user = User::findOrFail($id_user);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id_user)
    {
        $request->validate([
            'nama_user' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $id_user . ',id_user',
            'role' => 'required|in:admin,operator',
        ]);

        $user = User::findOrFail($id_user);

        $data = [
            'nama_user' => $request->nama_user,
            'email' => $request->email,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui!');
    }

    public function destroy($id_user)
    {
        $user = User::findOrFail($id_user);
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus!');
    }
}
