<?php

namespace App\Http\Controllers;

use App\Models\Position;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $users = User::with(['pos', 'positions'])
        ->when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%")
                         ->orWhere('username', 'like', "%{$search}%");
        })
        ->orderBy('id', 'desc')
        ->paginate(10)
        ->withQueryString();

        $positions = Position::all();

        return view('users.index', compact('users', 'positions', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'username'    => 'required|string|max:100|unique:users,username',
            'password'    => 'required|string|min:6',
            'gender'      => 'required|in:L,P',
            'positions'   => 'required|array|min:1',
            'positions.*' => 'exists:positions,id',
        ], [
            'name.required'      => 'Nama user wajib diisi',
            'username.required'  => 'Username wajib diisi',
            'username.unique'    => 'Username sudah digunakan',
            'password.required'  => 'Password wajib diisi',
            'password.min'       => 'Password minimal 6 karakter',
            'gender.required'    => 'Jenis kelamin wajib dipilih',
            'positions.required' => 'Minimal 1 Jabatan / Posisi wajib dipilih',
        ]);

        $firstPositionId = $request->positions[0];

        $user = User::create([
            'name'     => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'gender'   => $request->gender,
            'position' => $firstPositionId,
            'user'     => Auth::user()->username ?? 'admin',
        ]);

        // Sync multiple positions in position_user table
        $user->positions()->sync($request->positions);

        return redirect()->route('users.index')->with('success', 'Data User berhasil ditambahkan dengan jabatan terpilih');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:255',
            'username'    => 'required|string|max:100|unique:users,username,' . $id,
            'password'    => 'nullable|string|min:6',
            'gender'      => 'required|in:L,P',
            'positions'   => 'required|array|min:1',
            'positions.*' => 'exists:positions,id',
        ], [
            'name.required'      => 'Nama user wajib diisi',
            'username.required'  => 'Username wajib diisi',
            'username.unique'    => 'Username sudah digunakan',
            'password.min'       => 'Password minimal 6 karakter',
            'gender.required'    => 'Jenis kelamin wajib dipilih',
            'positions.required' => 'Minimal 1 Jabatan / Posisi wajib dipilih',
        ]);

        $firstPositionId = $request->positions[0];

        $data = [
            'name'     => $request->name,
            'username' => $request->username,
            'gender'   => $request->gender,
            'position' => $firstPositionId,
            'user'     => Auth::user()->username ?? 'admin',
        ];

        if (!empty($request->password)) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        // Sync multiple positions in position_user table
        $user->positions()->sync($request->positions);

        return redirect()->route('users.index')->with('success', 'Data User dan Jabatan berhasil diperbarui');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->positions()->detach();
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Data User berhasil dihapus');
    }
}
