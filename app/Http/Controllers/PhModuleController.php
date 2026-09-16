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

class PhModuleController extends Controller
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

        return view('ph.dashboard', compact(
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
            'date'               => 'required|date',
            'ph_notes'           => 'required|string',
            'ph_files.*'         => 'file|mimes:pdf,docx,doc,txt,jpg,jpeg,png,webp,gif|max:10240',
            'ph_file_labels.*'   => 'required_with:ph_files.*|string|max:255',
        ], [
            'ph_notes.required'          => 'Catatan pengawasan global PH wajib diisi',
            'ph_files.*.mimes'           => 'Format file lampiran harus berupa dokumen (.pdf, .docx, .doc, .txt) atau gambar/foto (.jpg, .jpeg, .png, .webp, .gif)',
            'ph_files.*.max'             => 'Ukuran masing-masing file maksimal 10 MB',
            'ph_file_labels.*.required_with' => 'Nama / label untuk file lampiran wajib diisi sebelum menyimpan',
        ]);

        $date = $request->date;
        $lhoReport = DailyLhoReport::firstOrCreate(['date' => $date]);

        $data = [
            'ph_user'  => Auth::user()->name ?? 'Penanggung Jawab Harian',
            'ph_notes' => $request->ph_notes,
            'status'   => 'Submitted_PH',
        ];

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

        // Handle Multiple File Upload with User Labels
        if ($request->hasFile('ph_files')) {
            $uploadDir = public_path('uploads/lho_files');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            foreach ($request->file('ph_files') as $idx => $file) {
                if ($file && $file->isValid()) {
                    $origName = $file->getClientOriginalName();
                    $ext = strtolower($file->getClientOriginalExtension());
                    $filename = 'PH_' . $date . '_' . time() . '_' . $idx . '.' . $ext;
                    $file->move($uploadDir, $filename);

                    $label = !empty($request->ph_file_labels[$idx]) ? trim($request->ph_file_labels[$idx]) : pathinfo($origName, PATHINFO_FILENAME);
                    $fileType = in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif']) ? 'image' : 'document';

                    DailyLhoAttachment::create([
                        'date'                => $date,
                        'daily_lho_report_id' => $lhoReport->id,
                        'role'                => 'PH',
                        'file_label'          => $label,
                        'file_path'           => 'uploads/lho_files/' . $filename,
                        'file_name'           => $origName,
                        'file_type'           => $fileType,
                        'extension'           => $ext,
                        'file_size'           => @filesize($uploadDir . '/' . $filename),
                        'uploaded_by'         => Auth::user()->name ?? 'PH',
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Catatan pengawasan, lampiran file, dan coretan tulis tangan PH berhasil disimpan!');
    }

    /**
     * Hapus lampiran LHO
     */
    public function destroyAttachment($id)
    {
        $attachment = DailyLhoAttachment::findOrFail($id);

        $filePath = public_path($attachment->file_path);
        if (file_exists($filePath)) {
            @unlink($filePath);
        }

        $attachment->delete();

        return redirect()->back()->with('success', 'File lampiran berhasil dihapus!');
    }
}
