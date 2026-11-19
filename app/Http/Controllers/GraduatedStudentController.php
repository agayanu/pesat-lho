<?php

namespace App\Http\Controllers;

use App\Models\GraduatedStudent;
use App\Models\Student;
use App\Models\Classes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GraduatedStudentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $selectedClass = $request->query('class_code');

        $graduatedStudents = GraduatedStudent::with('studentdayRef')
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                             ->orWhere('id_siswa', 'like', "%{$search}%")
                             ->orWhere('classes', 'like', "%{$search}%");
            })
            ->when($selectedClass, function ($query, $selectedClass) {
                return $query->where('classes', $selectedClass);
            })
            ->orderBy('graduation_date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(15)
            ->withQueryString();

        $classesList = Classes::orderBy('code', 'asc')->get();

        // Categorize classes for easy selection (e.g. XII classes)
        $xiiClasses = Classes::where('code', 'like', 'XII.%')
            ->orWhere('code', 'like', 'XII-%')
            ->orWhere('code', 'like', '12%')
            ->orderBy('code', 'asc')
            ->get();

        return view('graduated_students.index', compact('graduatedStudents', 'classesList', 'xiiClasses', 'search', 'selectedClass'));
    }

    public function getStudentsByClass(Request $request)
    {
        $classCode = $request->query('class_code');
        $students = Student::where('classes', $classCode)->orderBy('name', 'asc')->get();

        return response()->json($students);
    }

    public function storeByClasses(Request $request)
    {
        $request->validate([
            'classes'         => 'required|array|min:1',
            'graduation_date' => 'required|date',
            'notes'           => 'nullable|string|max:255',
        ], [
            'classes.required'         => 'Pilih minimal satu kelas untuk diproses kelulusannya.',
            'graduation_date.required' => 'Tanggal kelulusan wajib diisi.',
        ]);

        $classes = $request->classes;
        $graduationDate = $request->graduation_date;
        $notes = $request->notes;
        $currentUser = Auth::user()->name ?? Auth::user()->username ?? 'admin';

        $students = Student::whereIn('classes', $classes)->get();

        if ($students->isEmpty()) {
            return redirect()->back()->withErrors(['classes' => 'Tidak ditemukan siswa aktif pada kelas yang dipilih.']);
        }

        DB::beginTransaction();
        try {
            $count = 0;
            foreach ($students as $student) {
                GraduatedStudent::create([
                    'original_student_id' => $student->id,
                    'id_siswa'            => $student->id_siswa,
                    'name'                => $student->name,
                    'gender'              => $student->gender,
                    'classes'             => $student->classes,
                    'program'             => $student->program,
                    'studentday'          => $student->studentday,
                    'graduation_date'     => $graduationDate,
                    'notes'               => $notes ?: 'Kelulusan Massal Kelas ' . $student->classes,
                    'user'                => $currentUser,
                ]);

                // Dipindahkan: hapus dari tabel students agar tabel students hanya berisi siswa aktif
                $student->forceDelete();
                $count++;
            }

            DB::commit();
            return redirect()->route('graduated-students.index')->with(
                'success',
                "Berhasil memproses kelulusan untuk {$count} siswa dari " . count($classes) . " kelas terpilih."
            );
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['msg' => 'Gagal memproses kelulusan: ' . $e->getMessage()]);
        }
    }

    public function storeByStudents(Request $request)
    {
        $request->validate([
            'student_ids'     => 'required|array|min:1',
            'graduation_date' => 'required|date',
            'notes'           => 'nullable|string|max:255',
        ], [
            'student_ids.required'     => 'Pilih minimal satu siswa yang diluluskan.',
            'graduation_date.required' => 'Tanggal kelulusan wajib diisi.',
        ]);

        $graduationDate = $request->graduation_date;
        $notes = $request->notes;
        $currentUser = Auth::user()->name ?? Auth::user()->username ?? 'admin';

        $students = Student::whereIn('id', $request->student_ids)->get();

        if ($students->isEmpty()) {
            return redirect()->back()->withErrors(['student_ids' => 'Data siswa tidak ditemukan.']);
        }

        DB::beginTransaction();
        try {
            $count = 0;
            foreach ($students as $student) {
                GraduatedStudent::create([
                    'original_student_id' => $student->id,
                    'id_siswa'            => $student->id_siswa,
                    'name'                => $student->name,
                    'gender'              => $student->gender,
                    'classes'             => $student->classes,
                    'program'             => $student->program,
                    'studentday'          => $student->studentday,
                    'graduation_date'     => $graduationDate,
                    'notes'               => $notes ?: 'Kelulusan Siswa Terpilih',
                    'user'                => $currentUser,
                ]);

                // Dipindahkan: hapus dari tabel students
                $student->forceDelete();
                $count++;
            }

            DB::commit();
            return redirect()->route('graduated-students.index')->with(
                'success',
                "Berhasil memproses kelulusan untuk {$count} siswa terpilih."
            );
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['msg' => 'Gagal memproses kelulusan: ' . $e->getMessage()]);
        }
    }

    public function restore($id)
    {
        $graduated = GraduatedStudent::findOrFail($id);

        DB::beginTransaction();
        try {
            // Restore back to active students table
            Student::create([
                'id_siswa'   => $graduated->id_siswa,
                'name'       => $graduated->name,
                'gender'     => $graduated->gender,
                'classes'    => $graduated->classes,
                'program'    => $graduated->program ?: 'REGULER',
                'studentday' => is_numeric($graduated->studentday) ? (int)$graduated->studentday : null,
                'user'       => Auth::user()->name ?? Auth::user()->username ?? 'admin',
            ]);

            $graduated->delete();

            DB::commit();
            return redirect()->route('graduated-students.index')->with(
                'success',
                "Siswa {$graduated->name} ({$graduated->id_siswa}) berhasil dikembalikan ke status Siswa Aktif."
            );
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['msg' => 'Gagal mengembalikan siswa: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $graduated = GraduatedStudent::findOrFail($id);
        $graduated->delete();

        return redirect()->route('graduated-students.index')->with('success', 'Catatan siswa lulus berhasil dihapus.');
    }
}
