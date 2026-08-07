<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\SchoolEvent;
use App\Models\SpecialActivityReport;
use App\Models\Student;
use App\Models\StudentAbsence;
use App\Models\Teacher;
use App\Models\TeacherAbsence;
use App\Models\TeachingJournal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PiketModuleController extends Controller
{
    /**
     * Dashboard Monitoring Guru Piket
     */
    public function index(Request $request)
    {
        $date = $request->query('date', date('Y-m-d'));

        // Summary Data Hari Ini
        $teachingJournals = TeachingJournal::where('date', $date)->orderBy('jam_ke', 'asc')->get();
        $studentAbsences  = StudentAbsence::with('student')->where('date', $date)->orderBy('class_code', 'asc')->get();
        $teacherAbsences  = TeacherAbsence::where('date', $date)->orderBy('id', 'desc')->get();
        $specialReports   = SpecialActivityReport::where('date', $date)->orderBy('id', 'desc')->get();
        $schoolEvents     = SchoolEvent::where('date', $date)->orderBy('id', 'desc')->get();

        return view('piket.dashboard', compact(
            'date',
            'teachingJournals',
            'studentAbsences',
            'teacherAbsences',
            'specialReports',
            'schoolEvents'
        ));
    }

    /**
     * Halaman Kelola / Koreksi Presensi Siswa oleh Guru Piket
     */
    public function studentAbsences(Request $request)
    {
        $date = $request->query('date', date('Y-m-d'));
        $selectedClass = $request->query('class_code');

        $classList = Classes::orderBy('code', 'asc')->get();

        $absencesQuery = StudentAbsence::with('student')
            ->where('date', $date);

        if ($selectedClass) {
            $absencesQuery->where('class_code', $selectedClass);
        }

        $absences = $absencesQuery->orderBy('class_code', 'asc')
            ->orderBy('jam_ke', 'asc')
            ->paginate(15)
            ->withQueryString();

        return view('piket.student_absences', compact('date', 'selectedClass', 'classList', 'absences'));
    }

    /**
     * Action Koreksi Presensi Siswa
     */
    public function updateStudentAbsence(Request $request, $id)
    {
        $absence = StudentAbsence::findOrFail($id);

        $request->validate([
            'status'      => 'required|in:Hadir,Izin,Sakit,Alpha',
            'edit_reason' => 'required|string|max:255',
        ], [
            'status.required'      => 'Status presensi wajib dipilih',
            'edit_reason.required' => 'Alasan perubahan data wajib diisi oleh Guru Piket',
        ]);

        $piketUser = Auth::user()->name ?? 'Guru Piket';

        if ($request->status == 'Hadir') {
            // If changed to Hadir, soft-delete or remove the absence record
            $absence->update([
                'is_edited_by_piket' => true,
                'piket_user'         => $piketUser,
                'edit_reason'        => $request->edit_reason,
            ]);
            $absence->delete();
        } else {
            $absence->update([
                'status'             => $request->status,
                'is_edited_by_piket' => true,
                'piket_user'         => $piketUser,
                'edit_reason'        => $request->edit_reason,
            ]);
        }

        return redirect()->back()->with('success', 'Presensi siswa berhasil dikoreksi oleh Guru Piket');
    }

    /**
     * Halaman Kelola Absensi Guru & Pengganti/Tugas Kelas
     */
    public function teacherAbsences(Request $request)
    {
        $date = $request->query('date', date('Y-m-d'));

        $teacherAbsences = TeacherAbsence::where('date', $date)
            ->orderBy('id', 'desc')
            ->get();

        $teachers  = Teacher::orderBy('name', 'asc')->get();
        $classList = Classes::orderBy('code', 'asc')->get();

        return view('piket.teacher_absences', compact('date', 'teacherAbsences', 'teachers', 'classList'));
    }

    /**
     * Action Simpan Absensi Guru Tidak Hadir
     */
    public function storeTeacherAbsence(Request $request)
    {
        $request->validate([
            'date'               => 'required|date',
            'teacher_name'       => 'required|string',
            'class_code'         => 'required|string',
            'status'             => 'required|in:Izin,Sakit,Dinas,Alpha',
            'substitute_teacher' => 'nullable|string',
            'task_description'   => 'nullable|string',
        ], [
            'teacher_name.required' => 'Nama guru tidak hadir wajib dipilih/diisi',
            'class_code.required'   => 'Kelas wajib dipilih',
            'status.required'       => 'Status ketidakhadiran wajib dipilih',
        ]);

        TeacherAbsence::create([
            'date'               => $request->date,
            'teacher_name'       => $request->teacher_name,
            'class_code'         => $request->class_code,
            'status'             => $request->status,
            'substitute_teacher' => $request->substitute_teacher,
            'task_description'   => $request->task_description,
            'piket_user'         => Auth::user()->name ?? 'Guru Piket',
        ]);

        return redirect()->back()->with('success', 'Data presensi guru tidak hadir berhasil disimpan');
    }

    public function destroyTeacherAbsence($id)
    {
        $absence = TeacherAbsence::findOrFail($id);
        $absence->delete();

        return redirect()->back()->with('success', 'Data presensi guru berhasil dihapus');
    }

    /**
     * Halaman Kelola Event & Kejadian Sekolah
     */
    public function schoolEvents(Request $request)
    {
        $date = $request->query('date', date('Y-m-d'));

        $events = SchoolEvent::where('date', $date)
            ->orderBy('id', 'desc')
            ->get();

        return view('piket.school_events', compact('date', 'events'));
    }

    /**
     * Action Simpan Event / Kejadian Sekolah
     */
    public function storeSchoolEvent(Request $request)
    {
        $request->validate([
            'date'        => 'required|date',
            'category'    => 'required|string',
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
        ], [
            'category.required'    => 'Kategori acara/kejadian wajib dipilih',
            'title.required'       => 'Judul acara/kejadian wajib diisi',
            'description.required' => 'Deskripsi detail wajib diisi',
        ]);

        SchoolEvent::create([
            'date'        => $request->date,
            'category'    => $request->category,
            'title'       => $request->title,
            'description' => $request->description,
            'piket_user'  => Auth::user()->name ?? 'Guru Piket',
        ]);

        return redirect()->back()->with('success', 'Acara / Event Sekolah berhasil dicatat');
    }

    public function destroySchoolEvent($id)
    {
        $event = SchoolEvent::findOrFail($id);
        $event->delete();

        return redirect()->back()->with('success', 'Data event sekolah berhasil dihapus');
    }
}
