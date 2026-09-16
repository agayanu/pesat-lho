<?php

namespace App\Http\Controllers;

use App\Models\DailyLhoAttachment;
use App\Models\DailyLhoReport;
use App\Models\SchoolEvent;
use App\Models\SpecialActivityReport;
use App\Models\StudentAbsence;
use App\Models\StudentNote;
use App\Models\TeacherAbsence;
use App\Models\TeachingJournal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KadepModuleController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->query('date', date('Y-m-d'));

        $lhoReport = DailyLhoReport::with('attachments')->firstOrCreate(
            ['date' => $date],
            ['status' => 'Open']
        );

        $teachingJournals = TeachingJournal::with('teacher')->where('date', $date)->orderBy('jam_ke', 'asc')->get();
        $studentAbsences  = StudentAbsence::with('student')->where('date', $date)->orderBy('class_code', 'asc')->get();
        $teacherAbsences  = TeacherAbsence::with(['teacher', 'substituteTeacher'])->where('date', $date)->orderBy('id', 'desc')->get();
        $studentNotes     = StudentNote::with(['student', 'teacher'])->where('date', $date)->orderBy('jam_ke', 'asc')->get();
        $specialReports   = SpecialActivityReport::where('date', $date)->orderBy('id', 'desc')->get();
        $schoolEvents     = SchoolEvent::where('date', $date)->orderBy('id', 'desc')->get();

        return view('kadep.dashboard', compact(
            'date',
            'lhoReport',
            'teachingJournals',
            'studentAbsences',
            'teacherAbsences',
            'studentNotes',
            'specialReports',
            'schoolEvents'
        ));
    }

    public function storeNotes(Request $request)
    {
        $request->validate([
            'date'                 => 'required|date',
            'kadep_global_notes'   => 'required|string',
            'kadep_ph_notes'       => 'nullable|string',
            'kadep_files.*'        => 'file|mimes:pdf,docx,doc,txt,jpg,jpeg,png,webp,gif|max:10240',
            'kadep_file_labels.*'  => 'required_with:kadep_files.*|string|max:255',
        ], [
            'kadep_global_notes.required'    => 'Catatan pengawasan global Kepala Departemen wajib diisi',
            'kadep_files.*.mimes'            => 'Format file lampiran harus berupa dokumen (.pdf, .docx, .doc, .txt) atau gambar/foto (.jpg, .jpeg, .png, .webp, .gif)',
            'kadep_files.*.max'              => 'Ukuran masing-masing file maksimal 10 MB',
            'kadep_file_labels.*.required_with' => 'Nama / label untuk file lampiran wajib diisi sebelum menyimpan',
        ]);

        $date = $request->date;
        $lhoReport = DailyLhoReport::firstOrCreate(['date' => $date]);

        $data = [
            'kadep_user'         => Auth::user()->name ?? 'Kepala Departemen',
            'kadep_global_notes' => $request->kadep_global_notes,
            'kadep_ph_notes'     => $request->kadep_ph_notes,
            'status'             => 'Reviewed_Kadep',
        ];

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

        // Handle Multiple File Upload with User Labels
        if ($request->hasFile('kadep_files')) {
            $uploadDir = public_path('uploads/lho_files');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            foreach ($request->file('kadep_files') as $idx => $file) {
                if ($file && $file->isValid()) {
                    $origName = $file->getClientOriginalName();
                    $ext = strtolower($file->getClientOriginalExtension());
                    $filename = 'KADEP_' . $date . '_' . time() . '_' . $idx . '.' . $ext;
                    $file->move($uploadDir, $filename);

                    $label = !empty($request->kadep_file_labels[$idx]) ? trim($request->kadep_file_labels[$idx]) : pathinfo($origName, PATHINFO_FILENAME);
                    $fileType = in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif']) ? 'image' : 'document';

                    DailyLhoAttachment::create([
                        'date'                => $date,
                        'daily_lho_report_id' => $lhoReport->id,
                        'role'                => 'KADEP',
                        'file_label'          => $label,
                        'file_path'           => 'uploads/lho_files/' . $filename,
                        'file_name'           => $origName,
                        'file_type'           => $fileType,
                        'extension'           => $ext,
                        'file_size'           => @filesize($uploadDir . '/' . $filename),
                        'uploaded_by'         => Auth::user()->name ?? 'Kepala Departemen',
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Catatan pengawasan, lampiran file, dan coretan tulis tangan Kepala Departemen berhasil disimpan!');
    }
}
