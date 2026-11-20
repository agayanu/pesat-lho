<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Studentday;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentdayController extends Controller
{
    /**
     * Memeriksa hak akses Administrator.
     */
    private function checkAdminAccess()
    {
        $user = Auth::user();
        if ($user->position != 1 && !$user->hasPosition('Administrator')) {
            abort(403, 'Akses ditolak. Halaman ini hanya untuk Administrator.');
        }
    }

    /**
     * Tampilkan data Studentday.
     */
    public function index(Request $request)
    {
        $this->checkAdminAccess();

        $search = $request->query('search');

        $studentdays = Studentday::with('teacherUser')
            ->withCount('students')
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhereHas('teacherUser', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            })
            ->orderBy('name', 'asc')
            ->paginate(10)
            ->withQueryString();

        // Mengambil list guru yang memiliki posisi 'Guru Studentday'
        $teachers = User::where(function ($query) {
            $query->whereHas('positions', function ($q) {
                $q->where('name', 'like', '%Guru Studentday%')
                  ->orWhere('name', 'like', '%Studentday%');
            })->orWhereHas('pos', function ($q) {
                $q->where('name', 'like', '%Guru Studentday%')
                  ->orWhere('name', 'like', '%Studentday%');
            });
        })->orderBy('name', 'asc')->get();

        return view('studentdays.index', compact('studentdays', 'teachers', 'search'));
    }

    /**
     * Simpan data Studentday baru dan penentuan Guru Studentday.
     */
    public function store(Request $request)
    {
        $this->checkAdminAccess();

        $request->validate([
            'name'    => 'required|string|max:150|unique:studentdays,name',
            'teacher' => 'required|exists:users,id',
        ], [
            'name.required'    => 'Nama Studentday wajib diisi.',
            'name.unique'      => 'Nama Studentday sudah terdaftar di sistem.',
            'teacher.required' => 'Guru Studentday wajib dipilih.',
            'teacher.exists'   => 'Guru Studentday yang dipilih tidak valid.',
        ]);

        Studentday::create([
            'name'    => trim($request->name),
            'teacher' => $request->teacher,
        ]);

        return redirect()->route('studentdays.index')->with('success', 'Data Studentday dan penugasan Guru Studentday berhasil ditambahkan.');
    }

    /**
     * Perbarui data Studentday dan penugasan Guru Studentday.
     */
    public function update(Request $request, $id)
    {
        $this->checkAdminAccess();

        $studentday = Studentday::findOrFail($id);

        $request->validate([
            'name'    => 'required|string|max:150|unique:studentdays,name,' . $id,
            'teacher' => 'required|exists:users,id',
        ], [
            'name.required'    => 'Nama Studentday wajib diisi.',
            'name.unique'      => 'Nama Studentday sudah terdaftar di sistem.',
            'teacher.required' => 'Guru Studentday wajib dipilih.',
            'teacher.exists'   => 'Guru Studentday yang dipilih tidak valid.',
        ]);

        $studentday->update([
            'name'    => trim($request->name),
            'teacher' => $request->teacher,
        ]);

        return redirect()->route('studentdays.index')->with('success', 'Data Studentday berhasil diperbarui.');
    }

    /**
     * Hapus data Studentday.
     */
    public function destroy($id)
    {
        $this->checkAdminAccess();

        $studentday = Studentday::findOrFail($id);

        // Lepaskan referensi studentday pada siswa yang terdaftar agar tidak orphan
        Student::where('studentday', $studentday->id)->update(['studentday' => null]);

        $studentday->delete();

        return redirect()->route('studentdays.index')->with('success', 'Data Studentday berhasil dihapus.');
    }
}
