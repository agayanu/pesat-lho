<?php

namespace App\Http\Controllers;

use App\Models\DailyLhoReport;
use App\Models\SchoolEvent;
use App\Models\SpecialActivityReport;
use App\Models\StudentAbsence;
use App\Models\TeacherAbsence;
use App\Models\TeachingJournal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KadepModuleController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->query('date', date('Y-m-d'));

        $lhoReport = DailyLhoReport::firstOrCreate(
            ['date' => $date],
            ['status' => 'Open']
        );

        $teachingJournals = TeachingJournal::where('date', $date)->orderBy('jam_ke', 'asc')->get();
        $studentAbsences  = StudentAbsence::with('student')->where('date', $date)->orderBy('class_code', 'asc')->get();
        $teacherAbsences  = TeacherAbsence::where('date', $date)->orderBy('id', 'desc')->get();
        $specialReports   = SpecialActivityReport::where('date', $date)->orderBy('id', 'desc')->get();
        $schoolEvents     = SchoolEvent::where('date', $date)->orderBy('id', 'desc')->get();

        return view('kadep.dashboard', compact(
            'date',
            'lhoReport',
            'teachingJournals',
            'studentAbsences',
            'teacherAbsences',
            'specialReports',
            'schoolEvents'
        ));
    }

    public function storeNotes(Request $request)
    {
        $request->validate([
            'date'               => 'required|date',
            'kadep_global_notes' => 'required|string',
            'kadep_ph_notes'     => 'nullable|string',
            'kadep_file'         => 'nullable|file|mimes:pdf,docx,doc,txt|max:5120',
        ], [
            'kadep_global_notes.required' => 'Catatan pengawasan global Kepala Departemen wajib diisi',
            'kadep_file.mimes'            => 'Format file lampiran harus berupa .pdf, .docx, .doc, atau .txt',
            'kadep_file.max'              => 'Ukuran file maksimal 5 MB',
        ]);

        $date = $request->date;
        $lhoReport = DailyLhoReport::firstOrCreate(['date' => $date]);

        $data = [
            'kadep_user'         => Auth::user()->name ?? 'Kepala Departemen',
            'kadep_global_notes' => $request->kadep_global_notes,
            'kadep_ph_notes'     => $request->kadep_ph_notes,
            'status'             => 'Reviewed_Kadep',
        ];

        if ($request->hasFile('kadep_file')) {
            $file = $request->file('kadep_file');
            $filename = 'KADEP_' . $date . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/lho_files'), $filename);
            $data['kadep_file'] = 'uploads/lho_files/' . $filename;
        }

        $lhoReport->update($data);

        return redirect()->back()->with('success', 'Catatan pengawasan dan lampiran Kepala Departemen berhasil disimpan!');
    }
}
