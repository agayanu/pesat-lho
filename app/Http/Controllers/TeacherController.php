<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $teachers = Teacher::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%");
        })
        ->orderBy('id', 'desc')
        ->paginate(10)
        ->withQueryString();

        return view('teachers.index', compact('teachers', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'gender' => 'required|in:L,P',
        ], [
            'name.required'   => 'Nama guru wajib diisi',
            'gender.required' => 'Jenis kelamin wajib dipilih',
        ]);

        Teacher::create([
            'name'   => $request->name,
            'gender' => $request->gender,
            'user'   => Auth::user()->username ?? 'admin',
        ]);

        return redirect()->route('teachers.index')->with('success', 'Data Guru berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $teacher = Teacher::findOrFail($id);

        $request->validate([
            'name'   => 'required|string|max:255',
            'gender' => 'required|in:L,P',
        ], [
            'name.required'   => 'Nama guru wajib diisi',
            'gender.required' => 'Jenis kelamin wajib dipilih',
        ]);

        $teacher->update([
            'name'   => $request->name,
            'gender' => $request->gender,
            'user'   => Auth::user()->username ?? 'admin',
        ]);

        return redirect()->route('teachers.index')->with('success', 'Data Guru berhasil diperbarui');
    }

    public function destroy($id)
    {
        $teacher = Teacher::findOrFail($id);
        $teacher->delete();

        return redirect()->route('teachers.index')->with('success', 'Data Guru berhasil dihapus');
    }
}
