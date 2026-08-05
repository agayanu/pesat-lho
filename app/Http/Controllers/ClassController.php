<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClassController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $classes = Classes::with('homeroomTeacher')
        ->when($search, function ($query, $search) {
            return $query->where('code', 'like', "%{$search}%");
        })
        ->orderBy('id', 'desc')
        ->paginate(10)
        ->withQueryString();

        $teachers = Teacher::orderBy('name', 'asc')->get();

        return view('classes.index', compact('classes', 'teachers', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code'     => 'required|string|max:100|unique:classes,code',
            'homeroom' => 'required|exists:teachers,id',
            'school'   => 'required|in:Unggulan,Reguler',
        ], [
            'code.required'     => 'Kode/Nama kelas wajib diisi',
            'code.unique'       => 'Kode/Nama kelas sudah ada',
            'homeroom.required' => 'Wali kelas wajib dipilih',
            'school.required'   => 'Kategori sekolah wajib dipilih',
        ]);

        Classes::create([
            'code'     => $request->code,
            'homeroom' => $request->homeroom,
            'school'   => $request->school,
            'user'     => Auth::user()->username ?? 'admin',
        ]);

        return redirect()->route('classes.index')->with('success', 'Data Kelas berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $class = Classes::findOrFail($id);

        $request->validate([
            'code'     => 'required|string|max:100|unique:classes,code,' . $id,
            'homeroom' => 'required|exists:teachers,id',
            'school'   => 'required|in:Unggulan,Reguler',
        ], [
            'code.required'     => 'Kode/Nama kelas wajib diisi',
            'code.unique'       => 'Kode/Nama kelas sudah ada',
            'homeroom.required' => 'Wali kelas wajib dipilih',
            'school.required'   => 'Kategori sekolah wajib dipilih',
        ]);

        $class->update([
            'code'     => $request->code,
            'homeroom' => $request->homeroom,
            'school'   => $request->school,
            'user'     => Auth::user()->username ?? 'admin',
        ]);

        return redirect()->route('classes.index')->with('success', 'Data Kelas berhasil diperbarui');
    }

    public function destroy($id)
    {
        $class = Classes::findOrFail($id);
        $class->delete();

        return redirect()->route('classes.index')->with('success', 'Data Kelas berhasil dihapus');
    }
}
