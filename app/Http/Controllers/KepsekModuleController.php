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

class KepsekModuleController extends Controller
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

        return view('kepsek.dashboard', compact(
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
            'date'         => 'required|date',
            'kepsek_notes' => 'required|string',
            'kepsek_file'  => 'nullable|file|mimes:pdf,docx,doc,txt|max:5120',
        ], [
            'kepsek_notes.required' => 'Catatan arahan Kepala Sekolah wajib diisi',
            'kepsek_file.mimes'     => 'Format file lampiran harus berupa .pdf, .docx, .doc, atau .txt',
            'kepsek_file.max'       => 'Ukuran file maksimal 5 MB',
        ]);

        $date = $request->date;
        $lhoReport = DailyLhoReport::firstOrCreate(['date' => $date]);

        $data = [
            'kepsek_user'  => Auth::user()->name ?? 'Kepala Sekolah',
            'kepsek_notes' => $request->kepsek_notes,
            'status'       => 'Approved_Kepsek',
        ];

        if ($request->hasFile('kepsek_file')) {
            $file = $request->file('kepsek_file');
            $filename = 'KEPSEK_' . $date . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/lho_files'), $filename);
            $data['kepsek_file'] = 'uploads/lho_files/' . $filename;
        }

        // Handle Base64 Canvas Handwriting Drawing
        if (!empty($request->kepsek_handwriting_data)) {
            $imgData = $request->kepsek_handwriting_data;
            if (preg_match('/^data:image\/(\w+);base64,/', $imgData, $type)) {
                $imgData = substr($imgData, strpos($imgData, ',') + 1);
                $type = strtolower($type[1]);
                $imgData = base64_decode($imgData);
                if ($imgData !== false) {
                    $filename = 'KEPSEK_HW_' . $date . '_' . time() . '.' . $type;
                    $dir = public_path('uploads/handwritings');
                    if (!file_exists($dir)) {
                        mkdir($dir, 0777, true);
                    }
                    file_put_contents($dir . '/' . $filename, $imgData);
                    $data['kepsek_handwriting_img'] = 'uploads/handwritings/' . $filename;
                }
            }
        }

        $lhoReport->update($data);

        return redirect()->back()->with('success', 'Catatan arahan, lampiran file, dan coretan tulis tangan Kepala Sekolah berhasil disimpan!');
    }
}
