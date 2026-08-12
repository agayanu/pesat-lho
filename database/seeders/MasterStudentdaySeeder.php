<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterStudentdaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();
        $studentdays = [
            ['name' => 'ANIMASI X', 'teacher' => 94, 'created_at' => $now],
            ['name' => 'ANIMASI XI', 'teacher' => 94, 'created_at' => $now],
            ['name' => 'BAHASA KOREA X', 'teacher' => 95, 'created_at' => $now],
            ['name' => 'BAHASA KOREA XI', 'teacher' => 95, 'created_at' => $now],
            ['name' => 'BAHASA MANDARIN X', 'teacher' => 7, 'created_at' => $now],
            ['name' => 'BAHASA MANDARIN XI', 'teacher' => 7, 'created_at' => $now],
            ['name' => 'BROADCAST X', 'teacher' => 96, 'created_at' => $now],
            ['name' => 'BROADCAST XI', 'teacher' => 96, 'created_at' => $now],
            ['name' => 'CINEMATOGRAFI X A', 'teacher' => 97, 'created_at' => $now],
            ['name' => 'CINEMATOGRAFI X B', 'teacher' => 98, 'created_at' => $now],
            ['name' => 'CINEMATOGRAFI XI A', 'teacher' => 97, 'created_at' => $now],
            ['name' => 'CINEMATOGRAFI XI B', 'teacher' => 98, 'created_at' => $now],
            ['name' => 'ELEKTRO X', 'teacher' => 99, 'created_at' => $now],
            ['name' => 'ELEKTRO XI', 'teacher' => 99, 'created_at' => $now],
            ['name' => 'HEALTHY AND HERBS CENTER X', 'teacher' => 100, 'created_at' => $now],
            ['name' => 'HEALTHY AND HERBS CENTER XI', 'teacher' => 100, 'created_at' => $now],
            ['name' => 'JURNALISTIK X', 'teacher' => 101, 'created_at' => $now],
            ['name' => 'JURNALISTIK XI', 'teacher' => 101, 'created_at' => $now],
            ['name' => 'KESEKRETARISAN X', 'teacher' => 102, 'created_at' => $now],
            ['name' => 'KESEKRETARISAN XI', 'teacher' => 102, 'created_at' => $now],
            ['name' => 'MODELING X', 'teacher' => 103, 'created_at' => $now],
            ['name' => 'MODELING XI', 'teacher' => 103, 'created_at' => $now],
            ['name' => 'PROGRAMMING X B', 'teacher' => 14, 'created_at' => $now],
            ['name' => 'PROGRAMMING XI B', 'teacher' => 61, 'created_at' => $now],
            ['name' => 'ROBOTIKA XI', 'teacher' => 104, 'created_at' => $now],
            ['name' => 'SENI LUKIS X', 'teacher' => 105, 'created_at' => $now],
            ['name' => 'SENI LUKIS XI', 'teacher' => 105, 'created_at' => $now],
            ['name' => 'SENI MUSIK MODERN X A', 'teacher' => 113, 'created_at' => $now],
            ['name' => 'SENI MUSIK MODERN X B', 'teacher' => 113, 'created_at' => $now],
            ['name' => 'SENI MUSIK MODERN XI A', 'teacher' => 113, 'created_at' => $now],
            ['name' => 'SENI MUSIK MODERN XI B', 'teacher' => 113, 'created_at' => $now],
            ['name' => 'SENI MUSIK TRADISIONAL X', 'teacher' => 106, 'created_at' => $now],
            ['name' => 'SENI MUSIK TRADISIONAL XI', 'teacher' => 106, 'created_at' => $now],
            ['name' => 'SENI TARI TRADISIONAL X', 'teacher' => 107, 'created_at' => $now],
            ['name' => 'SENI TARI TRADISIONAL XI', 'teacher' => 107, 'created_at' => $now],
            ['name' => 'SENI TEATER X', 'teacher' => 51, 'created_at' => $now],
            ['name' => 'SENI TEATER XI', 'teacher' => 4, 'created_at' => $now],
            ['name' => 'TATA BOGA X A', 'teacher' => 108, 'created_at' => $now],
            ['name' => 'TATA BOGA X B', 'teacher' => 109, 'created_at' => $now],
            ['name' => 'TATA BOGA XI A', 'teacher' => 108, 'created_at' => $now],
            ['name' => 'TATA BOGA XI B', 'teacher' => 109, 'created_at' => $now],
            ['name' => 'TATA BUSANA X', 'teacher' => 110, 'created_at' => $now],
            ['name' => 'TATA BUSANA XI', 'teacher' => 110, 'created_at' => $now],
            ['name' => 'TATA RIAS RAMBUT DAN WAJAH X', 'teacher' => 111, 'created_at' => $now],
            ['name' => 'TATA RIAS RAMBUT DAN WAJAH XI', 'teacher' => 111, 'created_at' => $now],
            ['name' => 'TEKNIK ARSITEKTUR X', 'teacher' => 26, 'created_at' => $now],
            ['name' => 'TEKNIK ARSITEKTUR XI', 'teacher' => 26, 'created_at' => $now],
            ['name' => 'WEB DESIGN X', 'teacher' => 113, 'created_at' => $now],
            ['name' => 'WEB DESIGN XI', 'teacher' => 85, 'created_at' => $now],
            ['name' => 'WEB PROGRAMMING X', 'teacher' => 112, 'created_at' => $now],
            ['name' => 'WEB PROGRAMMING XI', 'teacher' => 112, 'created_at' => $now],
        ];

        DB::table('studentdays')->insert($studentdays);
    }
}
