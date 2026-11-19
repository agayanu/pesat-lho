<?php

namespace App\Exports;

use App\Models\Classes;
use App\Models\Studentday;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentTemplateExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new StudentDataSheet(),
            new StudentReferenceSheet(),
        ];
    }
}

class StudentDataSheet implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, WithStyles
{
    public function title(): string
    {
        return 'Data Siswa';
    }

    public function headings(): array
    {
        return [
            'id_siswa',
            'nama',
            'jenis_kelamin',
            'kelas',
            'studentday',
        ];
    }

    public function collection()
    {
        // Sample rows to guide the user
        $firstClass = Classes::orderBy('code', 'asc')->first();
        $classCode = $firstClass ? $firstClass->code : 'X.I-1';

        $firstSd = Studentday::orderBy('name', 'asc')->first();
        $sdName = $firstSd ? $firstSd->name : 'ANIMASI X';

        return collect([
            [
                'id_siswa'      => '20260001',
                'nama'          => 'CONTOH NAMA SISWA 1',
                'jenis_kelamin' => 'L',
                'kelas'         => $classCode,
                'studentday'    => $sdName,
            ],
            [
                'id_siswa'      => '20260002',
                'nama'          => 'CONTOH NAMA SISWA 2',
                'jenis_kelamin' => 'P',
                'kelas'         => $classCode,
                'studentday'    => '',
            ],
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '2A3042'],
                ],
            ],
        ];
    }
}

class StudentReferenceSheet implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, WithStyles
{
    public function title(): string
    {
        return 'Referensi Kode & Studentday';
    }

    public function headings(): array
    {
        return [
            'Daftar Kode Kelas (Field: code)',
            'Daftar Nama Student Day (Field: name)',
        ];
    }

    public function collection()
    {
        $classes = Classes::orderBy('code', 'asc')->pluck('code')->toArray();
        $studentdays = Studentday::orderBy('name', 'asc')->pluck('name')->toArray();

        $max = max(count($classes), count($studentdays));
        $rows = [];

        for ($i = 0; $i < $max; $i++) {
            $rows[] = [
                'kelas'      => $classes[$i] ?? '',
                'studentday' => $studentdays[$i] ?? '',
            ];
        }

        return collect($rows);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '198754'],
                ],
            ],
        ];
    }
}
