<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Student;
use App\Models\StudentAbsence;
use App\Models\StudentNote;
use App\Models\TeachingJournal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeachingModuleController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->query('date', date('Y-m-d'));
        $selectedClass = $request->query('class_code');
        $selectedJam = $request->query('jam_ke', 1);

        $classList = Classes::orderBy('code', 'asc')->get();

        $students = collect();
        $previousAbsences = collect();
        $currentAbsences = collect();
        $existingJournal = null;
        $isAlreadySubmitted = false;

        $studentNotes = collect();

        if ($selectedClass) {
            // Get all students in selected class
            $students = Student::where('classes', $selectedClass)
                ->orderBy('name', 'asc')
                ->get();

            // Get absences reported in earlier sessions today for this class
            $previousAbsences = StudentAbsence::with('student')
                ->where('date', $date)
                ->where('class_code', $selectedClass)
                ->where('jam_ke', '<', $selectedJam)
                ->get()
                ->keyBy('student_id');

            // Get absences reported in current session today
            $currentAbsences = StudentAbsence::where('date', $date)
                ->where('class_code', $selectedClass)
                ->where('jam_ke', $selectedJam)
                ->get()
                ->keyBy('student_id');

            // Get all notes for students in this class today
            $studentNotes = StudentNote::with(['teacher'])
                ->where('date', $date)
                ->where('class_code', $selectedClass)
                ->orderBy('id', 'asc')
                ->get()
                ->groupBy('student_id');

            // Get existing journal for current session
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
            'isAlreadySubmitted'
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
            'class_code.required' => 'Kelas wajib dipilih',
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

        return view('teaching.history', compact('date', 'myJournals'));
    }
}
