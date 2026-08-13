<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Student;
use App\Models\StudentAbsence;
use App\Models\TeachingJournal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WaliKelasController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->query('date', date('Y-m-d'));
        $userId = Auth::id();

        // Find class assigned to this homeroom teacher
        $assignedClass = Classes::where('homeroom', $userId)->first();
        
        $selectedClassCode = $request->query('class_code', $assignedClass->code ?? null);

        // Fallback: list all classes for selection if not assigned or for flexible viewing
        $classList = Classes::orderBy('code', 'asc')->get();

        if (!$selectedClassCode && $classList->count() > 0) {
            $selectedClassCode = $classList->first()->code;
        }

        $studentAbsences  = collect();
        $teachingJournals = collect();
        $studentsList     = collect();

        if ($selectedClassCode) {
            $studentAbsences = StudentAbsence::with('student')
                ->where('date', $date)
                ->where('class_code', $selectedClassCode)
                ->orderBy('jam_ke', 'asc')
                ->get();

            $teachingJournals = TeachingJournal::with('teacher')
                ->where('date', $date)
                ->where('class_code', $selectedClassCode)
                ->orderBy('jam_ke', 'asc')
                ->get();

            $studentsList = Student::where('classes', $selectedClassCode)
                ->orderBy('name', 'asc')
                ->get();
        }

        return view('walikelas.dashboard', compact(
            'date',
            'assignedClass',
            'selectedClassCode',
            'classList',
            'studentAbsences',
            'teachingJournals',
            'studentsList'
        ));
    }
}
