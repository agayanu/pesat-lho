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

class PhModuleController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->query('date', date('Y-m-d'));

        $lhoReport = DailyLhoReport::firstOrCreate(
            ['date' => $date],
            ['status' => 'Open']
        );

        $teachingJournals = TeachingJournal::with('teacher')->where('date', $date)->orderBy('jam_ke', 'asc')->get();
        $studentAbsences  = StudentAbsence::with('student')->where('date', $date)->orderBy('class_code', 'asc')->get();
        $teacherAbsences  = TeacherAbsence::with(['teacher', 'substituteTeacher'])->where('date', $date)->orderBy('id', 'desc')->get();
        $specialReports   = SpecialActivityReport::where('date', $date)->orderBy('id', 'desc')->get();
        $schoolEvents     = SchoolEvent::where('date', $date)->orderBy('id', 'desc')->get();

        return view('ph.dashboard', compact(
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
            'date'     => 'required|date',
            'ph_notes' => 'required|string',
            'ph_file'  => 'nullable|file|mimes:pdf,docx,doc,txt|max:5120',
        ], [
            'ph_notes.required' => 'Catatan pengawasan global PH wajib diisi',
            'ph_file.mimes'     => 'Format file lampiran harus berupa .pdf, .docx, .doc, atau .txt',
            'ph_file.max'       => 'Ukuran file maksimal 5 MB',
        ]);

        $date = $request->date;
        $lhoReport = DailyLhoReport::firstOrCreate(['date' => $date]);

        $data = [
            'ph_user'  => Auth::user()->name ?? 'Penanggung Jawab Harian',
            'ph_notes' => $request->ph_notes,
            'status'   => 'Submitted_PH',
        ];

        // Handle File Upload
        if ($request->hasFile('ph_file')) {
            $file = $request->file('ph_file');
            $filename = 'PH_' . $date . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/lho_files'), $filename);
            $data['ph_file'] = 'uploads/lho_files/' . $filename;
        }

        // Handle Base64 Canvas Handwriting Drawing
        if (!empty($request->ph_handwriting_data)) {
            $imgData = $request->ph_handwriting_data;
            if (preg_match('/^data:image\/(\w+);base64,/', $imgData, $type)) {
                $imgData = substr($imgData, strpos($imgData, ',') + 1);
                $type = strtolower($type[1]);
                $imgData = base64_decode($imgData);
                if ($imgData !== false) {
                    $filename = 'PH_HW_' . $date . '_' . time() . '.' . $type;
                    $dir = public_path('uploads/handwritings');
                    if (!file_exists($dir)) {
                        mkdir($dir, 0777, true);
                    }
                    file_put_contents($dir . '/' . $filename, $imgData);
                    $data['ph_handwriting_img'] = 'uploads/handwritings/' . $filename;
                }
            }
        }

        $lhoReport->update($data);

        return redirect()->back()->with('success', 'Catatan pengawasan, lampiran file, dan coretan tulis tangan PH berhasil disimpan!');
    }
}
