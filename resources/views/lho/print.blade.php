<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak LHO - {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}</title>
    <link href="{{ asset('metoxi/css/bootstrap.min.css') }}" rel="stylesheet">
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            background: #f8f9fa;
            color: #000;
        }
        .paper {
            background: #fff;
            padding: 30px 40px;
            margin: 20px auto;
            max-width: 1000px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header-title {
            text-align: center;
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header-title h3 {
            margin: 0;
            font-weight: bold;
            text-transform: uppercase;
        }
        .header-title h5 {
            margin: 5px 0 0 0;
            font-weight: normal;
        }
        .table-print {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 13px;
        }
        .table-print th, .table-print td {
            border: 1px solid #000;
            padding: 6px 8px;
        }
        .table-print th {
            background-color: #f0f0f0 !important;
            text-align: center;
            font-weight: bold;
        }
        .section-header {
            font-size: 15px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 8px;
            border-left: 4px solid #000;
            padding-left: 8px;
            text-transform: uppercase;
        }
        .signature-box {
            margin-top: 40px;
            page-break-inside: avoid;
        }
        @media print {
            body {
                background: #fff;
            }
            .paper {
                box-shadow: none;
                padding: 0;
                margin: 0;
                max-width: 100%;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

<div class="container text-center mt-3 no-print">
    <button onclick="window.print()" class="btn btn-primary btn-lg shadow">
        <i class="bx bx-printer"></i> Cetak / Simpan PDF Dokumen Ini
    </button>
    <button onclick="window.close()" class="btn btn-secondary btn-lg ms-2">
        Tutup
    </button>
</div>

<div class="paper">
    <!-- Header Dokumen -->
    <div class="header-title">
        <h3>LAPORAN HARIAN OPERASIONAL (LHO)</h3>
        <h5>SMA PLUS PGRI CIBINONG</h5>
        <p class="mb-0 text-muted">Tanggal Pelaporan: <strong>{{ \Carbon\Carbon::parse($date)->locale('id')->translatedFormat('l, d F Y') }}</strong></p>
    </div>

    <!-- 1. Presensi Siswa Tidak Hadir -->
    <div class="section-header">1. Rekapitulasi Ketidakhadiran Siswa</div>
    <table class="table-print">
        <thead>
            <tr>
                <th style="width: 30px;">#</th>
                <th>Jam Ke-</th>
                <th>Kelas</th>
                <th>NIS</th>
                <th>Nama Siswa</th>
                <th>Status</th>
                <th>Keterangan / Koreksi Piket</th>
            </tr>
        </thead>
        <tbody>
            @forelse($studentAbsences as $index => $abs)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">Jam ke-{{ $abs->jam_ke }}</td>
                    <td class="text-center"><strong>{{ $abs->class_code }}</strong></td>
                    <td class="text-center">{{ $abs->student->id_siswa ?? '-' }}</td>
                    <td>{{ $abs->student->name ?? '-' }}</td>
                    <td class="text-center"><strong>{{ $abs->status }}</strong></td>
                    <td>
                        @if($abs->is_edited_by_piket)
                            Koreksi Piket ({{ $abs->piket_user }}): {{ $abs->edit_reason }}
                        @else
                            Di-input oleh {{ $abs->user }}
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">Nihil (Semua siswa hadir).</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- 2. Absensi Guru & Guru Pengganti -->
    <div class="section-header">2. Presensi Guru Tidak Hadir & Guru Pengganti / Tugas Kelas</div>
    <table class="table-print">
        <thead>
            <tr>
                <th style="width: 30px;">#</th>
                <th>Guru Tidak Hadir</th>
                <th>Kelas</th>
                <th>Status</th>
                <th>Guru Pengganti</th>
                <th>Tugas Kelas Diberikan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($teacherAbsences as $index => $ta)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td><strong>{{ $ta->teacher_name }}</strong></td>
                    <td class="text-center">{{ $ta->class_code }}</td>
                    <td class="text-center">{{ $ta->status }}</td>
                    <td>{{ $ta->substitute_teacher ?? '-' }}</td>
                    <td>{{ $ta->task_description ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">Nihil (Semua guru hadir).</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- 3. Jurnal Mengajar (KBM) -->
    <div class="section-header">3. Jurnal Kegiatan Belajar Mengajar (KBM)</div>
    <table class="table-print">
        <thead>
            <tr>
                <th style="width: 30px;">#</th>
                <th>Jam</th>
                <th>Kelas</th>
                <th>Guru Pengajar</th>
                <th>Materi Pembelajaran</th>
                <th>Deskripsi Kegiatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($teachingJournals as $index => $j)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">Jam {{ $j->jam_ke }}</td>
                    <td class="text-center"><strong>{{ $j->class_code }}</strong></td>
                    <td>{{ $j->teacher_name }}</td>
                    <td>{{ $j->material }}</td>
                    <td>{{ $j->activity }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">Belum ada jurnal KBM terisi.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- 4. Laporan Kegiatan Spesifik Unit -->
    <div class="section-header">4. Laporan Kegiatan Penanggung Jawab Unit Spesifik</div>
    <table class="table-print">
        <thead>
            <tr>
                <th style="width: 30px;">#</th>
                <th>Unit Kegiatan</th>
                <th>Kelas / Peserta</th>
                <th>Materi / Deskripsi Kegiatan</th>
                <th>Pembimbing / Guru</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($specialReports as $index => $sr)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td><strong>{{ $sr->unit_name }}</strong></td>
                    <td>{{ $sr->class_or_participants }}</td>
                    <td>{{ $sr->material_activity }}</td>
                    <td>{{ $sr->pic_teacher }}</td>
                    <td>{{ $sr->notes ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">Belum ada laporan kegiatan spesifik.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- 5. Event & Kejadian Sekolah -->
    <div class="section-header">5. Event & Kejadian Sekolah</div>
    <table class="table-print">
        <thead>
            <tr>
                <th style="width: 30px;">#</th>
                <th>Kategori</th>
                <th>Judul Event / Kejadian</th>
                <th>Deskripsi Rincian</th>
                <th>Petugas Piket</th>
            </tr>
        </thead>
        <tbody>
            @forelse($schoolEvents as $index => $ev)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ $ev->category }}</td>
                    <td><strong>{{ $ev->title }}</strong></td>
                    <td>{{ $ev->description }}</td>
                    <td class="text-center">{{ $ev->piket_user }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted">Nihil.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- 6. Catatan Pengawasan & Coreatan Tulis Tangan Tablet -->
    <div class="section-header">6. Catatan Pengawasan Management</div>
    
    <table class="table-print">
        <tr>
            <th style="width: 25%;">Peran</th>
            <th style="width: 25%;">Nama Penanggung Jawab</th>
            <th>Catatan Pengawasan Teks & Coretan Tulis Tangan (Tablet Stylus)</th>
        </tr>
        <tr>
            <td><strong>1. Penanggung Jawab Harian (PH)</strong></td>
            <td>{{ $lhoReport->ph_user ?? '-' }}</td>
            <td>
                <div>{{ $lhoReport->ph_notes ?? 'Belum mengisi catatan.' }}</div>
                @if($lhoReport?->ph_handwriting_img)
                    <div class="mt-2">
                        <small class="d-block text-muted">Catatan Tulis Tangan Tablet PH:</small>
                        <img src="{{ asset($lhoReport->ph_handwriting_img) }}" style="max-height: 120px; border: 1px solid #ccc; padding: 2px; border-radius: 4px;" alt="Tulis Tangan PH">
                    </div>
                @endif
                @if($lhoReport?->ph_file)
                    <div class="mt-1"><small><em>(Melampirkan File: {{ basename($lhoReport->ph_file) }})</em></small></div>
                @endif
            </td>
        </tr>
        <tr>
            <td><strong>2. Kepala Departemen</strong></td>
            <td>{{ $lhoReport->kadep_user ?? '-' }}</td>
            <td>
                <div>{{ $lhoReport->kadep_global_notes ?? 'Belum mengisi catatan.' }}</div>
                @if($lhoReport?->kadep_handwriting_img)
                    <div class="mt-2">
                        <small class="d-block text-muted">Catatan Tulis Tangan Tablet Kadep:</small>
                        <img src="{{ asset($lhoReport->kadep_handwriting_img) }}" style="max-height: 120px; border: 1px solid #ccc; padding: 2px; border-radius: 4px;" alt="Tulis Tangan Kadep">
                    </div>
                @endif
                @if($lhoReport?->kadep_file)
                    <div class="mt-1"><small><em>(Melampirkan File: {{ basename($lhoReport->kadep_file) }})</em></small></div>
                @endif
            </td>
        </tr>
        <tr>
            <td><strong>3. Kepala Sekolah</strong></td>
            <td>{{ $lhoReport->kepsek_user ?? '-' }}</td>
            <td>
                <div>{{ $lhoReport->kepsek_notes ?? 'Belum mengisi catatan.' }}</div>
                @if($lhoReport?->kepsek_handwriting_img)
                    <div class="mt-2">
                        <small class="d-block text-muted">Catatan Tulis Tangan Tablet Kepsek:</small>
                        <img src="{{ asset($lhoReport->kepsek_handwriting_img) }}" style="max-height: 120px; border: 1px solid #ccc; padding: 2px; border-radius: 4px;" alt="Tulis Tangan Kepsek">
                    </div>
                @endif
                @if($lhoReport?->kepsek_file)
                    <div class="mt-1"><small><em>(Melampirkan File: {{ basename($lhoReport->kepsek_file) }})</em></small></div>
                @endif
            </td>
        </tr>
    </table>

    <!-- Blok Tanda Tangan -->
    <div class="signature-box">
        <table style="width: 100%; border: none; text-align: center;">
            <tr style="border: none;">
                <td style="width: 25%; border: none;">
                    Mengetahui,<br><strong>Kepala Sekolah</strong>
                    <br><br><br><br>
                    <u><strong>{{ $lhoReport->kepsek_user ?? '(..........................)' }}</strong></u>
                </td>
                <td style="width: 25%; border: none;">
                    Meninjau,<br><strong>Kepala Departemen</strong>
                    <br><br><br><br>
                    <u><strong>{{ $lhoReport->kadep_user ?? '(..........................)' }}</strong></u>
                </td>
                <td style="width: 25%; border: none;">
                    Penanggung Jawab,<br><strong>PH (PJ Harian)</strong>
                    <br><br><br><br>
                    <u><strong>{{ $lhoReport->ph_user ?? '(..........................)' }}</strong></u>
                </td>
                <td style="width: 25%; border: none;">
                    Petugas,<br><strong>Guru Piket</strong>
                    <br><br><br><br>
                    <u><strong>(..........................)</strong></u>
                </td>
            </tr>
        </table>
    </div>
</div>

</body>
</html>
