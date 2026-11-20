<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Student;
use App\Models\Studentday;
use App\Models\StudentdayExitedStudent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentdayMemberController extends Controller
{
    /**
     * Tampilkan halaman pengelolaan anggota Studentday.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $isAdmin = ($user->position == 1 || $user->hasPosition('Administrator'));
        $isGuruStudentday = (
            ($user->pos && str_contains(strtolower($user->pos->name), 'studentday')) ||
            $user->positions()->where('name', 'like', '%Studentday%')->exists() ||
            Studentday::where('teacher', $user->id)->exists()
        );

        if (!$isAdmin && !$isGuruStudentday) {
            abort(403, 'Akses ditolak. Halaman ini hanya untuk Guru Studentday atau Administrator.');
        }

        // Tentukan Studentday yang sedang dikelola
        $studentdayList = collect();
        $currentStudentday = null;

        if ($isAdmin) {
            $studentdayList = Studentday::with('teacherUser')->orderBy('name', 'asc')->get();
            $requestedSdId = $request->query('studentday_id');

            if ($requestedSdId) {
                $currentStudentday = Studentday::with('teacherUser')->find($requestedSdId);
            }
            if (!$currentStudentday && $studentdayList->isNotEmpty()) {
                $currentStudentday = $studentdayList->first();
            }
        } else {
            // Guru Studentday mengelola Studentday yang dibinanya
            $currentStudentday = Studentday::with('teacherUser')->where('teacher', $user->id)->first();
        }

        $search = $request->query('search');
        $tab = $request->query('tab', 'active');

        $activeMembers = collect();
        $exitedMembers = collect();
        $allStudents = collect();
        $classesList = Classes::orderBy('code', 'asc')->get();

        if ($currentStudentday) {
            // 1. Anggota Aktif
            $activeMembers = Student::where('studentday', $currentStudentday->id)
                ->when($search, function ($query, $search) {
                    return $query->where(function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                          ->orWhere('id_siswa', 'like', "%{$search}%")
                          ->orWhere('classes', 'like', "%{$search}%");
                    });
                })
                ->orderBy('classes', 'asc')
                ->orderBy('name', 'asc')
                ->paginate(15, ['*'], 'active_page')
                ->withQueryString();

            // 2. Riwayat Anggota yang Pernah Dikeluarkan dari Studentday Ini
            $exitedMembers = StudentdayExitedStudent::where('studentday_id', $currentStudentday->id)
                ->when($search, function ($query, $search) {
                    return $query->where(function ($q) use ($search) {
                        $q->where('student_name', 'like', "%{$search}%")
                          ->orWhere('id_siswa', 'like', "%{$search}%")
                          ->orWhere('student_class', 'like', "%{$search}%")
                          ->orWhere('reason', 'like', "%{$search}%");
                    });
                })
                ->orderBy('exit_date', 'desc')
                ->orderBy('id', 'desc')
                ->paginate(15, ['*'], 'exited_page')
                ->withQueryString();

            // 3. Seluruh Siswa untuk Modal Tambah Anggota
            // Mengambil siswa aktif dengan eager load studentdayRef
            $allStudents = Student::with('studentdayRef')
                ->orderBy('classes', 'asc')
                ->orderBy('name', 'asc')
                ->get();
        }

        return view('studentdays.members', compact(
            'isAdmin',
            'isGuruStudentday',
            'currentStudentday',
            'studentdayList',
            'activeMembers',
            'exitedMembers',
            'allStudents',
            'classesList',
            'search',
            'tab'
        ));
    }

    /**
     * Tambahkan siswa/anggota ke Studentday.
     */
    public function addMember(Request $request)
    {
        $user = Auth::user();
        $isAdmin = ($user->position == 1 || $user->hasPosition('Administrator'));

        $request->validate([
            'studentday_id' => 'required|exists:studentdays,id',
            'student_ids'   => 'required|array|min:1',
            'student_ids.*' => 'exists:students,id',
        ], [
            'student_ids.required' => 'Pilih minimal 1 siswa untuk ditambahkan.',
            'student_ids.min'      => 'Pilih minimal 1 siswa untuk ditambahkan.',
        ]);

        $studentday = Studentday::findOrFail($request->studentday_id);

        if (!$isAdmin && $studentday->teacher != $user->id) {
            abort(403, 'Anda tidak berhak mengelola anggota pada Studentday ini.');
        }

        $addedCount = 0;
        $alreadyInOtherCount = 0;

        foreach ($request->student_ids as $stdId) {
            $student = Student::with('studentdayRef')->find($stdId);
            if (!$student) continue;

            // Jika siswa sudah terdaftar di studentday lain, tidak boleh dimasukkan
            if (!empty($student->studentday) && $student->studentday != $studentday->id) {
                $alreadyInOtherCount++;
                continue;
            }

            // Jika belum di studentday ini, masukkan
            if ($student->studentday != $studentday->id) {
                $student->update(['studentday' => $studentday->id]);
                $addedCount++;
            }
        }

        $message = "Berhasil menambahkan {$addedCount} siswa ke Studentday {$studentday->name}.";
        if ($alreadyInOtherCount > 0) {
            $message .= " ({$alreadyInOtherCount} siswa dilewati karena sudah terdaftar di Studentday lain).";
        }

        return redirect()->route('studentday-members.index', ['studentday_id' => $studentday->id])
            ->with('success', $message);
    }

    /**
     * Hapus siswa dari Studentday (untuk guru salah memasukkan siswa).
     */
    public function removeMember(Request $request, $id)
    {
        $user = Auth::user();
        $isAdmin = ($user->position == 1 || $user->hasPosition('Administrator'));

        $student = Student::findOrFail($id);

        if (empty($student->studentday)) {
            return redirect()->back()->with('error', 'Siswa tidak terdaftar pada Studentday manapun.');
        }

        $studentday = Studentday::findOrFail($student->studentday);

        if (!$isAdmin && $studentday->teacher != $user->id) {
            abort(403, 'Anda tidak berhak menghapus anggota pada Studentday ini.');
        }

        $studentName = $student->name;
        // Hapus penugasan studentday tanpa mencatat riwayat (karena salah input)
        $student->update(['studentday' => null]);

        return redirect()->route('studentday-members.index', ['studentday_id' => $studentday->id])
            ->with('success', "Siswa {$studentName} berhasil dihapus dari keanggotaan Studentday {$studentday->name} (Pembatalan/Salah Input).");
    }

    /**
     * Keluarkan siswa dari Studentday (pindah studentday, naik ke kelas XII, dsb).
     * Mencatat ke tabel riwayat studentday_exited_students.
     */
    public function exitMember(Request $request, $id)
    {
        $user = Auth::user();
        $isAdmin = ($user->position == 1 || $user->hasPosition('Administrator'));

        $request->validate([
            'exit_date' => 'required|date',
            'reason'    => 'required|string|max:255',
            'notes'     => 'nullable|string',
        ], [
            'exit_date.required' => 'Tanggal keluar wajib diisi.',
            'reason.required'    => 'Alasan pengeluaran siswa wajib dipilih/diisi.',
        ]);

        $student = Student::findOrFail($id);

        if (empty($student->studentday)) {
            return redirect()->back()->with('error', 'Siswa tidak terdaftar pada Studentday manapun.');
        }

        $studentday = Studentday::findOrFail($student->studentday);

        if (!$isAdmin && $studentday->teacher != $user->id) {
            abort(403, 'Anda tidak berhak mengeluarkan anggota pada Studentday ini.');
        }

        // Catat ke tabel riwayat studentday_exited_students
        StudentdayExitedStudent::create([
            'student_id'      => $student->id,
            'studentday_id'   => $studentday->id,
            'id_siswa'        => $student->id_siswa,
            'student_name'    => $student->name,
            'student_class'   => $student->classes,
            'studentday_name' => $studentday->name,
            'exit_date'       => $request->exit_date,
            'reason'          => $request->reason,
            'notes'           => $request->notes,
            'user'            => $user->name ?? $user->username ?? 'Guru Studentday',
        ]);

        // Lepaskan keanggotaan studentday pada data siswa
        $student->update(['studentday' => null]);

        return redirect()->route('studentday-members.index', ['studentday_id' => $studentday->id, 'tab' => 'exited'])
            ->with('success', "Siswa {$student->name} berhasil dikeluarkan dari Studentday {$studentday->name} dan dicatat dalam riwayat pengeluaran.");
    }

    /**
     * Halaman Riwayat Siswa Studentday secara global (terutama untuk Admin).
     * Melihat riwayat siswa sudah pernah berada di studentday mana saja.
     */
    public function history(Request $request)
    {
        $search = $request->query('search');
        $studentdayFilter = $request->query('studentday_id');
        $reasonFilter = $request->query('reason');

        $histories = StudentdayExitedStudent::with(['student', 'studentday'])
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('student_name', 'like', "%{$search}%")
                      ->orWhere('id_siswa', 'like', "%{$search}%")
                      ->orWhere('student_class', 'like', "%{$search}%")
                      ->orWhere('notes', 'like', "%{$search}%");
                });
            })
            ->when($studentdayFilter, function ($query, $studentdayFilter) {
                return $query->where('studentday_id', $studentdayFilter);
            })
            ->when($reasonFilter, function ($query, $reasonFilter) {
                return $query->where('reason', $reasonFilter);
            })
            ->orderBy('exit_date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(20)
            ->withQueryString();

        $studentdayList = Studentday::orderBy('name', 'asc')->get();

        return view('studentdays.history', compact('histories', 'studentdayList', 'search', 'studentdayFilter', 'reasonFilter'));
    }
}
