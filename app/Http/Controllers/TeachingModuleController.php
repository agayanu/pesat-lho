<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Student;
use App\Models\StudentAbsence;
use App\Models\Studentday;
use App\Models\StudentNote;
use App\Models\TeachingJournal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeachingModuleController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $date = $request->query('date', date('Y-m-d'));

        // Cek posisi spesifik Guru Studentday atau penugasan studentday
        $isSpecificGuruStudentday = (
            ($user->pos && str_contains(strtolower($user->pos->name), 'studentday')) ||
            $user->positions()->where('name', 'like', '%Studentday%')->exists() ||
            Studentday::where('teacher', $user->id)->exists()
        );

        $hasRegularTeacherRole = (
            ($user->pos && (str_contains(strtolower($user->pos->name), 'guru kelas') || str_contains(strtolower($user->pos->name), 'pengajar'))) ||
            $user->positions()->where(function($q) {
                $q->where('name', 'like', '%Guru Kelas%')->orWhere('name', 'like', '%Pengajar%');
            })->exists() ||
            $user->position == 1 ||
            $user->hasPosition('Administrator')
        );

        // Tentukan mode: jika akun Guru Studentday, default-nya mode Studentday
        $requestedMode = $request->query('mode');
        if ($requestedMode === 'regular') {
            $isStudentdayMode = false;
        } elseif ($requestedMode === 'studentday') {
            $isStudentdayMode = true;
        } else {
            $isStudentdayMode = $isSpecificGuruStudentday;
        }

        $myStudentday = null;
        $noStudentdayAssigned = false;
        $classList = collect();

        if ($isStudentdayMode) {
            $myStudentday = Studentday::where('teacher', $user->id)->first();
            if (!$myStudentday) {
                $noStudentdayAssigned = true;
            }

            // Guru studentday tidak perlu memilih kelas karena sudah terdata dia mengajar studentday apa
            $selectedClass = $myStudentday ? $myStudentday->name : null;

            // Jam pelajaran hanya muncul 2 pilihan: 1 = Jam Ke-1 (Pagi), 2 = Jam Ke-2 (Siang)
            $selectedJam = (int) $request->query('jam_ke', 1);
            if ($selectedJam !== 1 && $selectedJam !== 2) {
                $selectedJam = 1;
            }
        } else {
            $classList = Classes::orderBy('code', 'asc')->get();
            $selectedClass = $request->query('class_code');
            $selectedJam = (int) $request->query('jam_ke', 1);
        }

        $students = collect();
        $previousAbsences = collect();
        $currentAbsences = collect();
        $existingJournal = null;
        $isAlreadySubmitted = false;
        $studentNotes = collect();

        if ($selectedClass) {
            if ($isStudentdayMode && $myStudentday) {
                // Data siswa yang perlu dia absen sesuai dengan tabel students field studentday
                $students = Student::where('studentday', $myStudentday->id)
                    ->orderBy('name', 'asc')
                    ->get();
            } else {
                // Ambil seluruh siswa pada kelas yang dipilih
                $students = Student::where('classes', $selectedClass)
                    ->orderBy('name', 'asc')
                    ->get();
            }

            // Ambil absensi sesi lebih awal hari ini untuk kelas/studentday ini
            $previousAbsences = StudentAbsence::with('student')
                ->where('date', $date)
                ->where('class_code', $selectedClass)
                ->where('jam_ke', '<', $selectedJam)
                ->get()
                ->keyBy('student_id');

            // Ambil absensi pada sesi saat ini
            $currentAbsences = StudentAbsence::where('date', $date)
                ->where('class_code', $selectedClass)
                ->where('jam_ke', $selectedJam)
                ->get()
                ->keyBy('student_id');

            // Ambil catatan siswa hari ini
            $studentNotes = StudentNote::with(['teacher'])
                ->where('date', $date)
                ->where('class_code', $selectedClass)
                ->orderBy('id', 'asc')
                ->get()
                ->groupBy('student_id');

            // Ambil jurnal mengajar sesi saat ini jika sudah ada
            $existingJournal = TeachingJournal::with('teacher')
                ->where('date', $date)
                ->where('class_code', $selectedClass)
                ->where('jam_ke', $selectedJam)
                ->first();

            if ($existingJournal) {
                $isAlreadySubmitted = true;
            }
        }

        return view('teaching.index', compact(
            'date',
            'selectedClass',
            'selectedJam',
            'classList',
            'students',
            'previousAbsences',
            'currentAbsences',
            'studentNotes',
            'existingJournal',
            'isAlreadySubmitted',
            'isStudentdayMode',
            'isSpecificGuruStudentday',
            'hasRegularTeacherRole',
            'myStudentday',
            'noStudentdayAssigned'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date'          => 'required|date',
            'class_code'    => 'required|string',
            'jam_ke'        => 'required|integer|min:1|max:10',
            'material'      => 'required|string',
            'activity'      => 'required|string',
            'absences'      => 'nullable|array',
            'student_notes' => 'nullable|array',
        ], [
            'class_code.required' => 'Kelas / Bidang Studentday wajib diisi',
            'jam_ke.required'     => 'Jam pelajaran wajib dipilih',
            'material.required'   => 'Materi pembelajaran wajib diisi',
            'activity.required'   => 'Deskripsi kegiatan pembelajaran wajib diisi',
        ]);

        $date = $request->date;
        $classCode = $request->class_code;
        $jamKe = $request->jam_ke;
        $userId = Auth::id();
        $user = Auth::user()->name ?? 'Guru';

        // Check if journal already exists for this session
        $existingJournal = TeachingJournal::where('date', $date)
            ->where('class_code', $classCode)
            ->where('jam_ke', $jamKe)
            ->first();

        // Ordinary teacher cannot modify submitted entries (Immutability)
        if ($existingJournal) {
            return redirect()->back()->with('error', 'Presensi & Jurnal KBM untuk jam pelajaran ini sudah di-submit dan tidak dapat diubah kembali. Hubungi Guru Piket jika ada perubahan.');
        }

        // Save Teaching Journal with teacher_id
        TeachingJournal::create([
            'date'         => $date,
            'class_code'   => $classCode,
            'jam_ke'       => $jamKe,
            'teacher_id'   => $userId,
            'teacher_name' => $user,
            'material'     => $request->material,
            'activity'     => $request->activity,
            'user'         => Auth::user()->username ?? 'guru',
        ]);

        // Save new Student Absences
        if ($request->has('absences') && is_array($request->absences)) {
            foreach ($request->absences as $studentId => $status) {
                if (in_array($status, ['Izin', 'Sakit', 'Alpha'])) {
                    StudentAbsence::create([
                        'date'       => $date,
                        'class_code' => $classCode,
                        'jam_ke'     => $jamKe,
                        'student_id' => $studentId,
                        'status'     => $status,
                        'user'       => Auth::user()->username ?? 'guru',
                    ]);
                }
            }
        }

        // Save Student Notes
        if ($request->has('student_notes') && is_array($request->student_notes)) {
            foreach ($request->student_notes as $studentId => $noteText) {
                if (!empty(trim($noteText))) {
                    StudentNote::create([
                        'date'         => $date,
                        'class_code'   => $classCode,
                        'jam_ke'       => $jamKe,
                        'student_id'   => $studentId,
                        'teacher_id'   => $userId,
                        'teacher_name' => $user,
                        'note'         => trim($noteText),
                        'created_by'   => Auth::user()->username ?? 'guru',
                    ]);
                }
            }
        }

        return redirect()->route('teaching.history')->with('success', 'Presensi Siswa, Catatan, dan Jurnal KBM berhasil disimpan!');
    }

    /**
     * Action Simpan Catatan Siswa Secara Cepat / Mandiri
     */
    public function storeStudentNote(Request $request)
    {
        $request->validate([
            'date'       => 'required|date',
            'class_code' => 'required|string',
            'jam_ke'     => 'required|integer',
            'student_id' => 'required|exists:students,id',
            'note'       => 'required|string',
        ], [
            'note.required' => 'Isi catatan siswa wajib diisi',
        ]);

        StudentNote::create([
            'date'         => $request->date,
            'class_code'   => $request->class_code,
            'jam_ke'       => $request->jam_ke,
            'student_id'   => $request->student_id,
            'teacher_id'   => Auth::id(),
            'teacher_name' => Auth::user()->name ?? 'Guru',
            'note'         => trim($request->note),
            'created_by'   => Auth::user()->username ?? 'guru',
        ]);

        return redirect()->back()->with('success', 'Catatan khusus siswa berhasil ditambahkan!');
    }

    public function history(Request $request)
    {
        $date = $request->query('date', date('Y-m-d'));
        $userId = Auth::id();
        $username = Auth::user()->username ?? '';

        $myJournals = TeachingJournal::with('teacher')
            ->where('date', $date)
            ->where(function($q) use ($userId, $username) {
                $q->where('teacher_id', $userId)
                  ->orWhere('user', $username);
            })
            ->orderBy('jam_ke', 'asc')
            ->get();

        $isGuruStudentday = (
            (Auth::user()->pos && str_contains(strtolower(Auth::user()->pos->name), 'studentday')) ||
            Auth::user()->positions()->where('name', 'like', '%Studentday%')->exists() ||
            Studentday::where('teacher', $userId)->exists()
        );

        return view('teaching.history', compact('date', 'myJournals', 'isGuruStudentday'));
    }
}
