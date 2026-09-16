<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\SchoolEvent;
use App\Models\SpecialActivityReport;
use App\Models\Student;
use App\Models\StudentAbsence;
use App\Models\StudentNote;
use App\Models\TeacherAbsence;
use App\Models\TeachingJournal;
use App\Models\User;
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
        $selectedTingkat = $request->query('tingkat');
        $selectedClass   = $request->query('class_code');

        $classList = Classes::orderBy('code', 'asc')->get();

        // Query Dasar dengan Filter Tanggal
        $teachingJournalsQuery = TeachingJournal::with('teacher')->where('date', $date);
        $studentAbsencesQuery  = StudentAbsence::with('student')->where('date', $date);
        $teacherAbsencesQuery  = TeacherAbsence::with(['teacher', 'substituteTeacher'])->where('date', $date);
        $studentNotesQuery     = StudentNote::with(['student', 'teacher'])->where('date', $date);

        // Terapkan Filter Tingkat dan Kelas
        $this->applyClassAndTingkatFilter($teachingJournalsQuery, $selectedClass, $selectedTingkat);
        $this->applyClassAndTingkatFilter($studentAbsencesQuery, $selectedClass, $selectedTingkat);
        $this->applyClassAndTingkatFilter($teacherAbsencesQuery, $selectedClass, $selectedTingkat);
        $this->applyClassAndTingkatFilter($studentNotesQuery, $selectedClass, $selectedTingkat);

        $teachingJournals = $teachingJournalsQuery->orderBy('jam_ke', 'asc')->get();
        $studentAbsences  = $studentAbsencesQuery->orderBy('class_code', 'asc')->get();
        $teacherAbsences  = $teacherAbsencesQuery->orderBy('id', 'desc')->get();
        $studentNotes     = $studentNotesQuery->orderBy('id', 'desc')->get();

        $specialReports   = SpecialActivityReport::where('date', $date)->orderBy('id', 'desc')->get();
        $schoolEvents     = SchoolEvent::where('date', $date)->orderBy('id', 'desc')->get();

        return view('piket.dashboard', compact(
            'date',
            'selectedTingkat',
            'selectedClass',
            'classList',
            'teachingJournals',
            'studentAbsences',
            'teacherAbsences',
            'studentNotes',
            'specialReports',
            'schoolEvents'
        ));
    }

    /**
     * Helper untuk memfilter query berdasarkan kelas dan tingkat (10, 11, 12)
     */
    private function applyClassAndTingkatFilter($query, $classCode, $tingkat)
    {
        if (!empty($classCode)) {
            $query->where('class_code', $classCode);
        } elseif (!empty($tingkat)) {
            if ($tingkat == '10') {
                $query->where(function ($q) {
                    $q->where('class_code', 'like', 'X.%')
                      ->orWhere('class_code', 'like', 'X-%')
                      ->orWhere('class_code', 'like', 'X %')
                      ->orWhere('class_code', 'like', '10%');
                })->where('class_code', 'not like', 'XI%');
            } elseif ($tingkat == '11') {
                $query->where(function ($q) {
                    $q->where('class_code', 'like', 'XI.%')
                      ->orWhere('class_code', 'like', 'XI-%')
                      ->orWhere('class_code', 'like', 'XI %')
                      ->orWhere('class_code', 'like', '11%');
                })->where('class_code', 'not like', 'XII%');
            } elseif ($tingkat == '12') {
                $query->where(function ($q) {
                    $q->where('class_code', 'like', 'XII.%')
                      ->orWhere('class_code', 'like', 'XII-%')
                      ->orWhere('class_code', 'like', 'XII %')
                      ->orWhere('class_code', 'like', '12%');
                });
            }
        }
        return $query;
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

        $teacherAbsences = TeacherAbsence::with(['teacher', 'substituteTeacher'])
            ->where('date', $date)
            ->orderBy('id', 'desc')
            ->get();

        $teachers  = User::whereNotIn('position', [1, 8])->orderBy('name', 'asc')->get();
        $classList = Classes::orderBy('code', 'asc')->get();

        return view('piket.teacher_absences', compact('date', 'teacherAbsences', 'teachers', 'classList'));
    }

    /**
     * Action Simpan Absensi Guru Tidak Hadir
     */
    public function storeTeacherAbsence(Request $request)
    {
        $request->validate([
            'date'                  => 'required|date',
            'teacher_id'            => 'required|exists:users,id',
            'class_code'            => 'required|string',
            'status'                => 'required|in:Izin,Sakit,Dinas,Alpha',
            'from_jam_ke'           => 'required|integer|min:1|max:12',
            'to_jam_ke'             => 'required|integer|min:1|max:12|gte:from_jam_ke',
            'substitute_teacher_id' => 'nullable|exists:users,id',
            'task_description'      => 'nullable|string',
        ], [
            'teacher_id.required'   => 'Guru tidak hadir wajib dipilih',
            'class_code.required'   => 'Kelas wajib dipilih',
            'status.required'       => 'Status ketidakhadiran wajib dipilih',
            'from_jam_ke.required'  => 'Dari Jam Ke- wajib dipilih',
            'to_jam_ke.required'    => 'Sampai Jam Ke- wajib dipilih',
            'to_jam_ke.gte'         => 'Sampai Jam Ke- harus sama atau lebih besar dari Dari Jam Ke-',
        ]);

        $absentUser = User::find($request->teacher_id);
        $substituteUser = $request->substitute_teacher_id ? User::find($request->substitute_teacher_id) : null;

        TeacherAbsence::create([
            'date'                  => $request->date,
            'teacher_id'            => $request->teacher_id,
            'teacher_name'          => $absentUser->name ?? '',
            'class_code'            => $request->class_code,
            'status'                => $request->status,
            'from_jam_ke'           => $request->from_jam_ke,
            'to_jam_ke'             => $request->to_jam_ke,
            'substitute_teacher_id' => $request->substitute_teacher_id,
            'substitute_teacher'    => $substituteUser->name ?? null,
            'task_description'      => $request->task_description,
            'piket_user'            => Auth::user()->name ?? 'Guru Piket',
        ]);

        return redirect()->back()->with('success', 'Data presensi guru tidak hadir berhasil disimpan');
    }

    /**
     * Action Edit Catatan Siswa oleh Guru Piket
     */
    public function updateStudentNote(Request $request, $id)
    {
        if (!Auth::user()->hasPosition('Guru Piket') && !Auth::user()->hasPosition('Piket') && Auth::user()->position != 1) {
            abort(403, 'Hanya Guru Piket yang dapat mengubah catatan siswa.');
        }

        $note = StudentNote::findOrFail($id);

        $request->validate([
            'note'        => 'required|string',
            'edit_reason' => 'required|string|max:255',
        ], [
            'note.required'        => 'Isi catatan siswa wajib diisi',
            'edit_reason.required' => 'Alasan perubahan catatan wajib diisi oleh Guru Piket',
        ]);

        $note->update([
            'note'                => $request->note,
            'is_edited_by_piket'  => true,
            'piket_user'          => Auth::user()->name ?? 'Guru Piket',
            'edit_reason'         => $request->edit_reason,
        ]);

        return redirect()->back()->with('success', 'Catatan siswa berhasil diperbarui oleh Guru Piket');
    }

    /**
     * Action Hapus Catatan Siswa oleh Guru Piket
     */
    public function destroyStudentNote(Request $request, $id)
    {
        if (!Auth::user()->hasPosition('Guru Piket') && !Auth::user()->hasPosition('Piket') && Auth::user()->position != 1) {
            abort(403, 'Hanya Guru Piket yang dapat menghapus catatan siswa.');
        }

        $note = StudentNote::findOrFail($id);
        $note->update([
            'piket_user'  => Auth::user()->name ?? 'Guru Piket',
            'edit_reason' => $request->edit_reason ?? 'Dihapus oleh Guru Piket',
        ]);
        $note->delete();

        return redirect()->back()->with('success', 'Catatan siswa berhasil dihapus oleh Guru Piket');
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
