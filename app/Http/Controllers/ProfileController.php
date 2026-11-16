<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Show form for changing own password.
     */
    public function showChangePassword()
    {
        $user = Auth::user();
        return view('profile.password', compact('user'));
    }

    /**
     * Update current authenticated user's password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password'         => 'required|string|min:6|confirmed|different:current_password',
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'password.required'         => 'Password baru wajib diisi.',
            'password.min'              => 'Password baru minimal harus 6 karakter.',
            'password.confirmed'        => 'Konfirmasi password baru tidak cocok.',
            'password.different'        => 'Password baru harus berbeda dengan password saat ini.',
        ]);

        $user = Auth::user();

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Password saat ini yang Anda masukkan tidak sesuai.'
            ])->withInput();
        }

        // Update password
        /** @var User $user */
        $user = User::find($user->id);
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('password.change')->with('success', 'Password Anda berhasil diubah! Gunakan password baru untuk login berikutnya.');
    }
}
