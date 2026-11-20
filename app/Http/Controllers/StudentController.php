<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Classes;
use App\Models\Studentday;
use App\Exports\StudentTemplateExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Csv;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $students = Student::with('studentdayRef')
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                             ->orWhere('id_siswa', 'like', "%{$search}%")
                             ->orWhere('classes', 'like', "%{$search}%");
            })
            ->orderBy('id', 'desc')
            ->paginate(15)
            ->withQueryString();

        $classesList = Classes::orderBy('code', 'asc')->get();
        $studentdaysList = Studentday::orderBy('name', 'asc')->get();

        return view('students.index', compact('students', 'classesList', 'studentdaysList', 'search'));
    }

    public function downloadTemplate()
    {
        return Excel::download(new StudentTemplateExport, 'Template_Data_Siswa.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240',
        ], [
            'file.required' => 'File Excel wajib diunggah',
            'file.max'      => 'Ukuran file maksimal 10MB',
        ]);

        $file = $request->file('file');
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, ['xlsx', 'xls', 'csv'])) {
            return redirect()->back()->withErrors(['file' => 'Format file harus berupa .xlsx, .xls, atau .csv']);
        }
        $filePath = $file->getRealPath();

        try {
            if ($extension === 'xlsx') {
                $reader = new Xlsx();
            } elseif ($extension === 'xls') {
                $reader = new Xls();
            } else {
                $reader = new Csv();
            }

            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();
        } catch (\Throwable $e) {
            return redirect()->back()->withErrors(['file' => 'Gagal membaca file: ' . $e->getMessage()]);
        }

        if (count($rows) < 2) {
            return redirect()->back()->withErrors(['file' => 'File Excel kosong atau tidak memiliki baris data.']);
        }

        // Header mapping
        $headerRow = array_map(function ($h) {
            return strtolower(trim((string)$h));
        }, $rows[0]);

        $idCol = array_search('id_siswa', $headerRow);
        if ($idCol === false) $idCol = array_search('nis', $headerRow);

        $nameCol = array_search('nama', $headerRow);
        if ($nameCol === false) $nameCol = array_search('name', $headerRow);
        if ($nameCol === false) $nameCol = array_search('nama_siswa', $headerRow);

        $genderCol = array_search('jenis_kelamin', $headerRow);
        if ($genderCol === false) $genderCol = array_search('gender', $headerRow);
        if ($genderCol === false) $genderCol = array_search('l_p', $headerRow);

        $classCol = array_search('kelas', $headerRow);
        if ($classCol === false) $classCol = array_search('classes', $headerRow);

        $sdCol = array_search('studentday', $headerRow);

        if ($idCol === false || $nameCol === false) {
            return redirect()->back()->withErrors([
                'file' => 'Format kolom Excel tidak sesuai template. Pastikan terdapat header: id_siswa, nama, jenis_kelamin, kelas, studentday.'
            ]);
        }

        // Cache classes and studentdays for fast lookup
        $classesMap = Classes::all()->keyBy(function ($item) {
            return strtoupper(trim($item->code));
        });

        $studentdayMap = Studentday::all()->keyBy(function ($item) {
            return strtoupper(trim($item->name));
        });

        $insertedCount = 0;
        $updatedCount = 0;
        $currentUser = Auth::user()->username ?? Auth::user()->name ?? 'admin';

        for ($i = 1; $i < count($rows); $i++) {
            $row = $rows[$i];

            $idSiswa = isset($row[$idCol]) ? trim((string)$row[$idCol]) : '';
            if ($idSiswa === '') {
                continue; // Skip empty rows
            }

            $name = isset($row[$nameCol]) ? trim((string)$row[$nameCol]) : '';
            if ($name === '') {
                continue;
            }

            // Gender normalization
            $rawGender = ($genderCol !== false && isset($row[$genderCol])) ? strtoupper(trim((string)$row[$genderCol])) : 'L';
            if (str_starts_with($rawGender, 'P') || str_starts_with($rawGender, 'W')) {
                $gender = 'P';
            } else {
                $gender = 'L';
            }

            // Class code and program lookup
            $rawClass = ($classCol !== false && isset($row[$classCol])) ? trim((string)$row[$classCol]) : '';
            $classUpper = strtoupper($rawClass);
            $classCode = $rawClass;
            $program = 'REGULER';

            if (isset($classesMap[$classUpper])) {
                $matchedClass = $classesMap[$classUpper];
                $classCode = $matchedClass->code;
                $program = $matchedClass->program ?? 'REGULER';
            }

            // Studentday lookup by name
            $rawSd = ($sdCol !== false && isset($row[$sdCol])) ? trim((string)$row[$sdCol]) : '';
            $sdUpper = strtoupper($rawSd);
            $studentdayId = null;

            if ($rawSd !== '') {
                if (isset($studentdayMap[$sdUpper])) {
                    $studentdayId = $studentdayMap[$sdUpper]->id;
                } elseif (is_numeric($rawSd)) {
                    $studentdayId = (int)$rawSd;
                }
            }

            // Check if student with this id_siswa already exists in database (including soft-deleted)
            $existing = Student::withTrashed()->where('id_siswa', $idSiswa)->first();

            if ($existing) {
                $existing->update([
                    'name'       => $name,
                    'gender'     => $gender,
                    'classes'    => $classCode,
                    'program'    => $program,
                    'studentday' => $studentdayId,
                    'user'       => $currentUser,
                    'deleted_at' => null,
                ]);
                $updatedCount++;
            } else {
                Student::create([
                    'id_siswa'   => $idSiswa,
                    'name'       => $name,
                    'gender'     => $gender,
                    'classes'    => $classCode,
                    'program'    => $program,
                    'studentday' => $studentdayId,
                    'user'       => $currentUser,
                ]);
                $insertedCount++;
            }
        }

        return redirect()->route('students.index')->with(
            'success',
            "Upload data siswa berhasil diproses: {$insertedCount} data baru ditambahkan, {$updatedCount} data diperbarui / direplace."
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_siswa'   => 'required|string|max:100|unique:students,id_siswa',
            'name'       => 'required|string|max:255',
            'gender'     => 'required|in:L,P',
            'classes'    => 'required|string|max:100',
            'program'    => 'nullable|string|max:100',
            'studentday' => 'nullable|string|max:255',
        ], [
            'id_siswa.required' => 'ID/NIS Siswa wajib diisi',
            'id_siswa.unique'   => 'ID/NIS Siswa sudah terdaftar',
            'name.required'     => 'Nama siswa wajib diisi',
            'gender.required'   => 'Jenis kelamin wajib dipilih',
            'classes.required'  => 'Kelas wajib dipilih/diisi',
        ]);

        $matchedClass = Classes::where('code', $request->classes)->first();
        $program = $request->program ?: ($matchedClass->program ?? 'REGULER');

        $studentdayId = null;
        if ($request->filled('studentday')) {
            $studentdayId = is_numeric($request->studentday) ? (int)$request->studentday : null;
        }

        Student::create([
            'id_siswa'   => $request->id_siswa,
            'name'       => $request->name,
            'gender'     => $request->gender,
            'classes'    => $request->classes,
            'program'    => $program,
            'studentday' => $studentdayId,
            'user'       => Auth::user()->username ?? Auth::user()->name ?? 'admin',
        ]);

        return redirect()->route('students.index')->with('success', 'Data Siswa berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $request->validate([
            'name'       => 'required|string|max:255',
            'gender'     => 'required|in:L,P',
            'classes'    => 'required|string|max:100',
            'program'    => 'nullable|string|max:100',
            'studentday' => 'nullable|string|max:255',
        ], [
            'name.required'     => 'Nama siswa wajib diisi',
            'gender.required'   => 'Jenis kelamin wajib dipilih',
            'classes.required'  => 'Kelas wajib dipilih/diisi',
        ]);

        $matchedClass = Classes::where('code', $request->classes)->first();
        $program = $request->program ?: ($matchedClass->program ?? $student->program ?? 'REGULER');

        $studentdayId = null;
        if ($request->filled('studentday')) {
            $studentdayId = is_numeric($request->studentday) ? (int)$request->studentday : null;
        }

        $student->update([
            'name'       => $request->name,
            'gender'     => $request->gender,
            'classes'    => $request->classes,
            'program'    => $program,
            'studentday' => $studentdayId,
            'user'       => Auth::user()->username ?? Auth::user()->name ?? 'admin',
        ]);

        return redirect()->route('students.index')->with('success', 'Data Siswa berhasil diperbarui');
    }

    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        $student->delete();

        return redirect()->route('students.index')->with('success', 'Data Siswa berhasil dihapus');
    }
}
