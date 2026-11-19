<?php

namespace App\Http\Controllers;

use App\Models\ExitedStudent;
use App\Models\Student;
use App\Models\Classes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExitedStudentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $selectedClass = $request->query('class_code');

        $exitedStudents = ExitedStudent::with('studentdayRef')
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                             ->orWhere('id_siswa', 'like', "%{$search}%")
                             ->orWhere('classes', 'like', "%{$search}%")
                             ->orWhere('reason', 'like', "%{$search}%");
            })
            ->when($selectedClass, function ($query, $selectedClass) {
                return $query->where('classes', $selectedClass);
            })
            ->orderBy('exit_date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(15)
            ->withQueryString();

        $classesList = Classes::orderBy('code', 'asc')->get();

        return view('exited_students.index', compact('exitedStudents', 'classesList', 'search', 'selectedClass'));
    }

    public function searchActiveStudents(Request $request)
    {
        $q = $request->query('q');
        $class = $request->query('class_code');

        $students = Student::when($q, function ($query, $q) {
                return $query->where(function ($sub) use ($q) {
                    $sub->where('name', 'like', "%{$q}%")
                        ->orWhere('id_siswa', 'like', "%{$q}%");
                });
            })
            ->when($class, function ($query, $class) {
                return $query->where('classes', $class);
            })
            ->orderBy('name', 'asc')
            ->limit(30)
            ->get();

        return response()->json($students);
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'exit_date'  => 'required|date',
            'reason'     => 'required|string|max:255',
            'notes'      => 'nullable|string|max:500',
        ], [
            'student_id.required' => 'Pilih siswa yang akan dicatat keluar.',
            'student_id.exists'   => 'Siswa tidak ditemukan pada data siswa aktif.',
            'exit_date.required'  => 'Tanggal keluar wajib diisi.',
            'reason.required'     => 'Alasan keluar wajib diisi.',
        ]);

        $student = Student::findOrFail($request->student_id);
        $currentUser = Auth::user()->name ?? Auth::user()->username ?? 'admin';

        DB::beginTransaction();
        try {
            ExitedStudent::create([
                'original_student_id' => $student->id,
                'id_siswa'            => $student->id_siswa,
                'name'                => $student->name,
                'gender'              => $student->gender,
                'classes'             => $student->classes,
                'program'             => $student->program,
                'studentday'          => $student->studentday,
                'exit_date'           => $request->exit_date,
                'reason'              => $request->reason,
                'notes'               => $request->notes,
                'user'                => $currentUser,
            ]);

            // Dipindahkan dari tabel students
            $student->forceDelete();

            DB::commit();
            return redirect()->route('exited-students.index')->with(
                'success',
                "Siswa {$student->name} ({$student->id_siswa}) berhasil dicatat sebagai Siswa Keluar dan dipindahkan dari data siswa aktif."
            );
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['msg' => 'Gagal memproses data siswa keluar: ' . $e->getMessage()]);
        }
    }

    public function restore($id)
    {
        $exited = ExitedStudent::findOrFail($id);

        DB::beginTransaction();
        try {
            // Restore back to active students table
            Student::create([
                'id_siswa'   => $exited->id_siswa,
                'name'       => $exited->name,
                'gender'     => $exited->gender,
                'classes'    => $exited->classes,
                'program'    => $exited->program ?: 'REGULER',
                'studentday' => is_numeric($exited->studentday) ? (int)$exited->studentday : null,
                'user'       => Auth::user()->name ?? Auth::user()->username ?? 'admin',
            ]);

            $exited->delete();

            DB::commit();
            return redirect()->route('exited-students.index')->with(
                'success',
                "Siswa {$exited->name} ({$exited->id_siswa}) berhasil dikembalikan ke status Siswa Aktif."
            );
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['msg' => 'Gagal mengembalikan siswa: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $exited = ExitedStudent::findOrFail($id);
        $exited->delete();

        return redirect()->route('exited-students.index')->with('success', 'Catatan siswa keluar berhasil dihapus.');
    }
}
