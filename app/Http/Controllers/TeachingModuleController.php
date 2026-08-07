<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Student;
use App\Models\StudentAbsence;
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

            // Get existing journal for current session
            $existingJournal = TeachingJournal::where('date', $date)
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
            'existingJournal',
            'isAlreadySubmitted'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date'       => 'required|date',
            'class_code' => 'required|string',
            'jam_ke'     => 'required|integer|min:1|max:10',
            'material'   => 'required|string',
            'activity'   => 'required|string',
            'absences'   => 'nullable|array',
        ], [
            'class_code.required' => 'Kelas wajib dipilih',
            'jam_ke.required'     => 'Jam pelajaran wajib dipilih',
            'material.required'   => 'Materi pembelajaran wajib diisi',
            'activity.required'   => 'Deskripsi kegiatan pembelajaran wajib diisi',
        ]);

        $date = $request->date;
        $classCode = $request->class_code;
        $jamKe = $request->jam_ke;
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

        // Save Teaching Journal
        TeachingJournal::create([
            'date'         => $date,
            'class_code'   => $classCode,
            'jam_ke'       => $jamKe,
            'teacher_name' => $user,
            'material'     => $request->material,
            'activity'     => $request->activity,
            'user'         => Auth::user()->username ?? 'guru',
        ]);

        // Save new Student Absences (excluding those already marked in previous sessions if skipped)
        if ($request->has('absences') && is_array($request->absences)) {
            foreach ($request->absences as $studentId => $status) {
                if (in_array($status, ['Izin', 'Sakit', 'Alpha'])) {
                    // Check if already created for this session
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

        return redirect()->route('teaching.history')->with('success', 'Presensi Siswa dan Jurnal KBM berhasil disimpan!');
    }

    public function history(Request $request)
    {
        $date = $request->query('date', date('Y-m-d'));
        $username = Auth::user()->username ?? '';

        $myJournals = TeachingJournal::where('date', $date)
            ->where('user', $username)
            ->orderBy('jam_ke', 'asc')
            ->get();

        return view('teaching.history', compact('date', 'myJournals'));
    }
}
