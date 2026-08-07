<?php

namespace App\Http\Controllers;

use App\Models\DailyLhoReport;
use App\Models\SchoolEvent;
use App\Models\SpecialActivityReport;
use App\Models\StudentAbsence;
use App\Models\TeacherAbsence;
use App\Models\TeachingJournal;
use Illuminate\Http\Request;

class LhoPrintController extends Controller
{
    public function print(Request $request)
    {
        $date = $request->query('date', date('Y-m-d'));

        $lhoReport = DailyLhoReport::where('date', $date)->first();

        $teachingJournals = TeachingJournal::where('date', $date)->orderBy('jam_ke', 'asc')->get();
        $studentAbsences  = StudentAbsence::with('student')->where('date', $date)->orderBy('class_code', 'asc')->get();
        $teacherAbsences  = TeacherAbsence::where('date', $date)->orderBy('id', 'desc')->get();
        $specialReports   = SpecialActivityReport::where('date', $date)->orderBy('id', 'desc')->get();
        $schoolEvents     = SchoolEvent::where('date', $date)->orderBy('id', 'desc')->get();

        return view('lho.print', compact(
            'date',
            'lhoReport',
            'teachingJournals',
            'studentAbsences',
            'teacherAbsences',
            'specialReports',
            'schoolEvents'
        ));
    }
}
