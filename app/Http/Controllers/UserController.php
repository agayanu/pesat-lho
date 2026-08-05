<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $users = User::with('pos')
        ->when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%")
                         ->orWhere('username', 'like', "%{$search}%");
        })
        ->orderBy('id', 'desc')
        ->paginate(10)
        ->withQueryString();

        $positions = Position::orderBy('name', 'asc')->get();

        return view('users.index', compact('users', 'positions', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:100|unique:users,username',
            'password' => 'required|string|min:4',
            'gender'   => 'required|in:L,P',
            'position' => 'required|integer',
        ], [
            'name.required'     => 'Nama user wajib diisi',
            'username.required' => 'Username wajib diisi',
            'username.unique'   => 'Username sudah digunakan',
            'password.required' => 'Password wajib diisi',
            'password.min'      => 'Password minimal 4 karakter',
            'gender.required'   => 'Jenis kelamin wajib dipilih',
            'position.required' => 'Jabatan wajib dipilih',
        ]);

        User::create([
            'name'     => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'gender'   => $request->gender,
            'position' => $request->position,
            'user'     => Auth::user()->username ?? 'admin',
        ]);

        return redirect()->route('users.index')->with('success', 'User Pengguna berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:100|unique:users,username,' . $id,
            'gender'   => 'required|in:L,P',
            'position' => 'required|integer',
        ], [
            'name.required'     => 'Nama user wajib diisi',
            'username.required' => 'Username wajib diisi',
            'username.unique'   => 'Username sudah digunakan',
            'gender.required'   => 'Jenis kelamin wajib dipilih',
            'position.required' => 'Jabatan wajib dipilih',
        ]);

        $data = [
            'name'     => $request->name,
            'username' => $request->username,
            'gender'   => $request->gender,
            'position' => $request->position,
            'user'     => Auth::user()->username ?? 'admin',
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'User Pengguna berhasil diperbarui');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent deleting logged in user
        if (Auth::id() == $user->id) {
            return redirect()->route('users.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User Pengguna berhasil dihapus');
    }
}
