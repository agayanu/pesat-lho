<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Classes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $students = Student::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%")
                         ->orWhere('id_siswa', 'like', "%{$search}%")
                         ->orWhere('classes', 'like', "%{$search}%");
        })
        ->orderBy('id', 'desc')
        ->paginate(10)
        ->withQueryString();

        $classesList = Classes::orderBy('code', 'asc')->get();

        return view('students.index', compact('students', 'classesList', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_siswa'   => 'required|string|max:100|unique:students,id_siswa',
            'name'       => 'required|string|max:255',
            'gender'     => 'required|in:L,P',
            'classes'    => 'required|string|max:100',
            'program'    => 'required|in:Pioneer,Unggulan,Reguler',
            'studentday' => 'nullable|string|max:255',
        ], [
            'id_siswa.required' => 'ID/NIS Siswa wajib diisi',
            'id_siswa.unique'   => 'ID/NIS Siswa sudah terdaftar',
            'name.required'     => 'Nama siswa wajib diisi',
            'gender.required'   => 'Jenis kelamin wajib dipilih',
            'classes.required'  => 'Kelas wajib dipilih/diisi',
            'program.required'  => 'Program sekolah wajib dipilih',
        ]);

        Student::create([
            'id_siswa'   => $request->id_siswa,
            'name'       => $request->name,
            'gender'     => $request->gender,
            'classes'    => $request->classes,
            'program'    => $request->program,
            'studentday' => $request->studentday,
            'user'       => Auth::user()->username ?? 'admin',
        ]);

        return redirect()->route('students.index')->with('success', 'Data Siswa berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $request->validate([
            'id_siswa'   => 'required|string|max:100|unique:students,id_siswa,' . $id,
            'name'       => 'required|string|max:255',
            'gender'     => 'required|in:L,P',
            'classes'    => 'required|string|max:100',
            'program'    => 'required|in:Pioneer,Unggulan,Reguler',
            'studentday' => 'nullable|string|max:255',
        ], [
            'id_siswa.required' => 'ID/NIS Siswa wajib diisi',
            'id_siswa.unique'   => 'ID/NIS Siswa sudah terdaftar',
            'name.required'     => 'Nama siswa wajib diisi',
            'gender.required'   => 'Jenis kelamin wajib dipilih',
            'classes.required'  => 'Kelas wajib dipilih/diisi',
            'program.required'  => 'Program sekolah wajib dipilih',
        ]);

        $student->update([
            'id_siswa'   => $request->id_siswa,
            'name'       => $request->name,
            'gender'     => $request->gender,
            'classes'    => $request->classes,
            'program'    => $request->program,
            'studentday' => $request->studentday,
            'user'       => Auth::user()->username ?? 'admin',
        ]);

        return redirect()->route('students.index')->with('success', 'Data Siswa berhasil diperbarui');
    }

    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        $student->delete();

        return redirect()->route('students.index')->with('success', 'Data Siswa berhasil dihapus');
    }
}
