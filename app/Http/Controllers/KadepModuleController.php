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

        // Handle Base64 Canvas Handwriting Drawing
        if (!empty($request->kadep_handwriting_data)) {
            $imgData = $request->kadep_handwriting_data;
            if (preg_match('/^data:image\/(\w+);base64,/', $imgData, $type)) {
                $imgData = substr($imgData, strpos($imgData, ',') + 1);
                $type = strtolower($type[1]);
                $imgData = base64_decode($imgData);
                if ($imgData !== false) {
                    $filename = 'KADEP_HW_' . $date . '_' . time() . '.' . $type;
                    $dir = public_path('uploads/handwritings');
                    if (!file_exists($dir)) {
                        mkdir($dir, 0777, true);
                    }
                    file_put_contents($dir . '/' . $filename, $imgData);
                    $data['kadep_handwriting_img'] = 'uploads/handwritings/' . $filename;
                }
            }
        }

        $lhoReport->update($data);

        return redirect()->back()->with('success', 'Catatan pengawasan, lampiran file, dan coretan tulis tangan Kepala Departemen berhasil disimpan!');
    }
}
