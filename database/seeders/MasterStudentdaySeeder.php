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
            ['name' => 'Senin', 'teacher' => 1, 'created_at' => $now],
            ['name' => 'Selasa', 'teacher' => 2, 'created_at' => $now],
            ['name' => 'Rabu', 'teacher' => 3, 'created_at' => $now],
            ['name' => 'Kamis', 'teacher' => 4, 'created_at' => $now],
            ['name' => 'Jumat', 'teacher' => 5, 'created_at' => $now],
            ['name' => 'Sabtu', 'teacher' => 6, 'created_at' => $now],
        ];

        DB::table('studentdays')->insert($studentdays);
    }
}
