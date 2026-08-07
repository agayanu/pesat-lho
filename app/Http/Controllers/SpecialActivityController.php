<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\SpecialActivityReport;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpecialActivityController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->query('date', date('Y-m-d'));
        $selectedUnit = $request->query('unit_name');

        $unitList = [
            'Bimbingan Baca Quran (BBQ)',
            'Kuliah Dhuha',
            'Kopasus IT (RPL, TKJ, MM)',
            'Kursus Bahasa Inggris',
            'Medical Center (UKS)',
            'Perpustakaan',
            'Radio Sekolah',
            'Laboratorium IPA',
            'Praktek Olahraga',
            'Ekstrakurikuler',
            'Test Out',
            'Lainnya',
        ];

        $reportsQuery = SpecialActivityReport::where('date', $date);

        if ($selectedUnit) {
            $reportsQuery->where('unit_name', $selectedUnit);
        }

        $reports = $reportsQuery->orderBy('id', 'desc')->get();
        $teachers = Teacher::orderBy('name', 'asc')->get();
        $classList = Classes::orderBy('code', 'asc')->get();

        return view('special_activities.index', compact(
            'date',
            'selectedUnit',
            'unitList',
            'reports',
            'teachers',
            'classList'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date'                  => 'required|date',
            'unit_name'             => 'required|string',
            'class_or_participants' => 'required|string|max:255',
            'material_activity'     => 'required|string',
            'pic_teacher'           => 'required|string|max:255',
            'notes'                 => 'nullable|string',
        ], [
            'unit_name.required'             => 'Unit kegiatan wajib dipilih',
            'class_or_participants.required' => 'Kelas / Jumlah Peserta wajib diisi',
            'material_activity.required'     => 'Materi / Deskripsi kegiatan wajib diisi',
            'pic_teacher.required'           => 'Pembimbing / Guru bertugas wajib dipilih/diisi',
        ]);

        SpecialActivityReport::create([
            'date'                  => $request->date,
            'unit_name'             => $request->unit_name,
            'class_or_participants' => $request->class_or_participants,
            'material_activity'     => $request->material_activity,
            'pic_teacher'           => $request->pic_teacher,
            'notes'                 => $request->notes,
            'user'                  => Auth::user()->name ?? 'PJ Kegiatan',
        ]);

        return redirect()->back()->with('success', 'Laporan Kegiatan Spesifik berhasil disimpan');
    }

    public function destroy($id)
    {
        $report = SpecialActivityReport::findOrFail($id);
        $report->delete();

        return redirect()->back()->with('success', 'Laporan kegiatan berhasil dihapus');
    }
}
