<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\DailyLhoReport;
use App\Models\SchoolEvent;
use App\Models\SpecialActivityReport;
use App\Models\StudentAbsence;
use App\Models\StudentNote;
use App\Models\TeacherAbsence;
use App\Models\TeachingJournal;
use Illuminate\Http\Request;

class LhoPrintController extends Controller
{
    public function print(Request $request)
    {
        $date = $request->query('date', date('Y-m-d'));
        $school = $request->query('school'); // 'SMAS', 'Reguler', or empty for Semua Sekolah

        $lhoReport = DailyLhoReport::with('attachments')->where('date', $date)->first();

        // Filter by school if selected
        $schoolClasses = null;
        if (!empty($school) && in_array($school, ['SMAS', 'Reguler'])) {
            $schoolClasses = Classes::where('school', $school)->pluck('code')->toArray();
        }

        $teachingJournals = TeachingJournal::with('teacher')
            ->where('date', $date)
            ->when($schoolClasses !== null, function ($q) use ($schoolClasses) {
                return $q->whereIn('class_code', $schoolClasses);
            })
            ->orderBy('jam_ke', 'asc')
            ->get();

        $studentAbsences = StudentAbsence::with('student')
            ->where('date', $date)
            ->when($schoolClasses !== null, function ($q) use ($schoolClasses) {
                return $q->whereIn('class_code', $schoolClasses);
            })
            ->orderBy('class_code', 'asc')
            ->get();

        $teacherAbsences = TeacherAbsence::with(['teacher', 'substituteTeacher'])
            ->where('date', $date)
            ->when($schoolClasses !== null, function ($q) use ($schoolClasses) {
                return $q->whereIn('class_code', $schoolClasses);
            })
            ->orderBy('id', 'desc')
            ->get();

        $studentNotes = StudentNote::with(['student', 'teacher'])
            ->where('date', $date)
            ->when($schoolClasses !== null, function ($q) use ($schoolClasses) {
                return $q->whereIn('class_code', $schoolClasses);
            })
            ->orderBy('jam_ke', 'asc')
            ->get();

        $specialReports = SpecialActivityReport::where('date', $date)->orderBy('id', 'desc')->get();
        $schoolEvents   = SchoolEvent::where('date', $date)->orderBy('id', 'desc')->get();

        return view('lho.print', compact(
            'date',
            'school',
            'lhoReport',
            'teachingJournals',
            'studentAbsences',
            'teacherAbsences',
            'studentNotes',
            'specialReports',
            'schoolEvents'
        ));
    }
}
