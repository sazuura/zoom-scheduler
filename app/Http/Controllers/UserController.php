<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_user', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->status == 'active') {
            $query->where('status', 'active');
        } elseif ($request->status == 'inactive') {
            $query->where('status', 'inactive');
        }
        $users = $query->orderBy('nama_user')
                    ->paginate(5)
                    ->withQueryString();
        return view('admin.users.index', compact('users'));
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
    public function store(Request $request)
    {
        $request->validate([
            'nama_user' => 'required|string|max:100',
            'nohp' => 'required|string|max:20|unique:users,nohp',
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
            'nohp' => $request->nohp,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
        ]);
        //Log::info('User baru dibuat:', $user->toArray());
        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan!');
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
            'nohp' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $id_user . ',id_user',
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:admin,operator',
        ]);
        $user = User::findOrFail($id_user);
        $data = [
            'nama_user' => $request->nama_user,
            'nohp' => $request->nohp,
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
        if ($user->role === 'admin' && $user->id_user === auth()->user()->id_user) {
            return back()->with('error', 'Admin tidak dapat menonaktifkan dirinya sendiri.');
        }
        if ($user->status === 'active') {
            $user->update(['status' => 'inactive']);
            $message = 'User berhasil dinonaktifkan.';
        } else {
            $user->update(['status' => 'active']);
            $message = 'User berhasil diaktifkan kembali.';
        }
        return redirect()->route('admin.users.index')
            ->with('success', $message);
    }
}