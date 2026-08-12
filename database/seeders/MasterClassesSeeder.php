<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterClassesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();
        $classes = [
            ['code' => 'X.I-1', 'homeroom' => 7, 'program' => 'INTERNASIONAL', 'school' => 'Unggulan', 'user' => 'system', 'created_at' => $now],
            ['code' => 'X.I-2', 'homeroom' => 64, 'program' => 'INTERNASIONAL', 'school' => 'Unggulan', 'user' => 'system', 'created_at' => $now],
            ['code' => 'X.I-3', 'homeroom' => 46, 'program' => 'INTERNASIONAL', 'school' => 'Unggulan', 'user' => 'system', 'created_at' => $now],
            ['code' => 'X.I-4', 'homeroom' => 21, 'program' => 'INTERNASIONAL', 'school' => 'Unggulan', 'user' => 'system', 'created_at' => $now],
            ['code' => 'X.U-1', 'homeroom' => 58, 'program' => 'UNGGULAN', 'school' => 'Unggulan', 'user' => 'system', 'created_at' => $now],
            ['code' => 'X.U-2', 'homeroom' => 52, 'program' => 'UNGGULAN', 'school' => 'Unggulan', 'user' => 'system', 'created_at' => $now],
            ['code' => 'X.U-3', 'homeroom' => 86, 'program' => 'UNGGULAN', 'school' => 'Unggulan', 'user' => 'system', 'created_at' => $now],
            ['code' => 'X.U-4', 'homeroom' => 28, 'program' => 'UNGGULAN', 'school' => 'Unggulan', 'user' => 'system', 'created_at' => $now],
            ['code' => 'X.U-5', 'homeroom' => 80, 'program' => 'UNGGULAN', 'school' => 'Unggulan', 'user' => 'system', 'created_at' => $now],
            ['code' => 'X.U-6', 'homeroom' => 59, 'program' => 'UNGGULAN', 'school' => 'Unggulan', 'user' => 'system', 'created_at' => $now],
            ['code' => 'X.U-7', 'homeroom' => 79, 'program' => 'UNGGULAN', 'school' => 'Unggulan', 'user' => 'system', 'created_at' => $now],
            ['code' => 'X.U-8', 'homeroom' => 32, 'program' => 'UNGGULAN', 'school' => 'Unggulan', 'user' => 'system', 'created_at' => $now],
            ['code' => 'XI.I-1', 'homeroom' => 69, 'program' => 'INTERNASIONAL', 'school' => 'Unggulan', 'user' => 'system', 'created_at' => $now],
            ['code' => 'XI.I-2', 'homeroom' => 42, 'program' => 'INTERNASIONAL', 'school' => 'Unggulan', 'user' => 'system', 'created_at' => $now],
            ['code' => 'XI.I-3', 'homeroom' => 72, 'program' => 'INTERNASIONAL', 'school' => 'Unggulan', 'user' => 'system', 'created_at' => $now],
            ['code' => 'XI.I-4', 'homeroom' => 9, 'program' => 'INTERNASIONAL', 'school' => 'Unggulan', 'user' => 'system', 'created_at' => $now],
            ['code' => 'XI.U-1', 'homeroom' => 76, 'program' => 'UNGGULAN', 'school' => 'Unggulan', 'user' => 'system', 'created_at' => $now],
            ['code' => 'XI.U-2', 'homeroom' => 89, 'program' => 'UNGGULAN', 'school' => 'Unggulan', 'user' => 'system', 'created_at' => $now],
            ['code' => 'XI.U-3', 'homeroom' => 51, 'program' => 'UNGGULAN', 'school' => 'Unggulan', 'user' => 'system', 'created_at' => $now],
            ['code' => 'XI.U-4', 'homeroom' => 88, 'program' => 'UNGGULAN', 'school' => 'Unggulan', 'user' => 'system', 'created_at' => $now],
            ['code' => 'XI.U-5', 'homeroom' => 53, 'program' => 'UNGGULAN', 'school' => 'Unggulan', 'user' => 'system', 'created_at' => $now],
            ['code' => 'XI.U-6', 'homeroom' => 77, 'program' => 'UNGGULAN', 'school' => 'Unggulan', 'user' => 'system', 'created_at' => $now],
            ['code' => 'XI.U-7', 'homeroom' => 35, 'program' => 'UNGGULAN', 'school' => 'Unggulan', 'user' => 'system', 'created_at' => $now],
            ['code' => 'XI.U-8', 'homeroom' => 81, 'program' => 'UNGGULAN', 'school' => 'Unggulan', 'user' => 'system', 'created_at' => $now],
            ['code' => 'XII.I-1', 'homeroom' => 23, 'program' => 'INTERNASIONAL', 'school' => 'Unggulan', 'user' => 'system', 'created_at' => $now],
            ['code' => 'XII.I-2', 'homeroom' => 49, 'program' => 'INTERNASIONAL', 'school' => 'Unggulan', 'user' => 'system', 'created_at' => $now],
            ['code' => 'XII.I-3', 'homeroom' => 29, 'program' => 'INTERNASIONAL', 'school' => 'Unggulan', 'user' => 'system', 'created_at' => $now],
            ['code' => 'XII.I-4', 'homeroom' => 25, 'program' => 'INTERNASIONAL', 'school' => 'Unggulan', 'user' => 'system', 'created_at' => $now],
            ['code' => 'XII.R-1', 'homeroom' => 57, 'program' => 'REGULER', 'school' => 'Unggulan', 'user' => 'system', 'created_at' => $now],
            ['code' => 'XII.R-2', 'homeroom' => 19, 'program' => 'REGULER', 'school' => 'Unggulan', 'user' => 'system', 'created_at' => $now],
            ['code' => 'XII.R-3', 'homeroom' => 24, 'program' => 'REGULER', 'school' => 'Unggulan', 'user' => 'system', 'created_at' => $now],
            ['code' => 'XII.R-4', 'homeroom' => 20, 'program' => 'REGULER', 'school' => 'Unggulan', 'user' => 'system', 'created_at' => $now],
            ['code' => 'XII.R-5', 'homeroom' => 60, 'program' => 'REGULER', 'school' => 'Unggulan', 'user' => 'system', 'created_at' => $now],
            ['code' => 'XII.R-6', 'homeroom' => 44, 'program' => 'REGULER', 'school' => 'Unggulan', 'user' => 'system', 'created_at' => $now],
            ['code' => 'XII.R-7', 'homeroom' => 63, 'program' => 'REGULER', 'school' => 'Unggulan', 'user' => 'system', 'created_at' => $now],
            ['code' => 'XII.R-8', 'homeroom' => 41, 'program' => 'REGULER', 'school' => 'Unggulan', 'user' => 'system', 'created_at' => $now],
            ['code' => 'XII.R-9', 'homeroom' => 47, 'program' => 'REGULER', 'school' => 'Unggulan', 'user' => 'system', 'created_at' => $now],
            ['code' => 'XII.U-1', 'homeroom' => 18, 'program' => 'UNGGULAN', 'school' => 'Unggulan', 'user' => 'system', 'created_at' => $now],
            ['code' => 'XII.U-2', 'homeroom' => 22, 'program' => 'UNGGULAN', 'school' => 'Unggulan', 'user' => 'system', 'created_at' => $now],
            ['code' => 'XII.U-3', 'homeroom' => 8, 'program' => 'UNGGULAN', 'school' => 'Unggulan', 'user' => 'system', 'created_at' => $now],
            ['code' => 'XII.U-4', 'homeroom' => 13, 'program' => 'UNGGULAN', 'school' => 'Unggulan', 'user' => 'system', 'created_at' => $now],
            ['code' => 'XII.U-5', 'homeroom' => 39, 'program' => 'UNGGULAN', 'school' => 'Unggulan', 'user' => 'system', 'created_at' => $now],
            ['code' => 'XII.U-6', 'homeroom' => 30, 'program' => 'UNGGULAN', 'school' => 'Unggulan', 'user' => 'system', 'created_at' => $now],
            ['code' => 'XII.U-7', 'homeroom' => 14, 'program' => 'UNGGULAN', 'school' => 'Unggulan', 'user' => 'system', 'created_at' => $now],
            ['code' => 'XII.U-8', 'homeroom' => 36, 'program' => 'UNGGULAN', 'school' => 'Unggulan', 'user' => 'system', 'created_at' => $now],
            ['code' => 'X.R-1', 'homeroom' => 10, 'program' => 'REGULER', 'school' => 'Reguler', 'user' => 'system', 'created_at' => $now],
            ['code' => 'X.R-2', 'homeroom' => 67, 'program' => 'REGULER', 'school' => 'Reguler', 'user' => 'system', 'created_at' => $now],
            ['code' => 'X.R-3', 'homeroom' => 68, 'program' => 'REGULER', 'school' => 'Reguler', 'user' => 'system', 'created_at' => $now],
            ['code' => 'X.R-4', 'homeroom' => 27, 'program' => 'REGULER', 'school' => 'Reguler', 'user' => 'system', 'created_at' => $now],
            ['code' => 'X.R-5', 'homeroom' => 61, 'program' => 'REGULER', 'school' => 'Reguler', 'user' => 'system', 'created_at' => $now],
            ['code' => 'X.R-6', 'homeroom' => 74, 'program' => 'REGULER', 'school' => 'Reguler', 'user' => 'system', 'created_at' => $now],
            ['code' => 'X.R-7', 'homeroom' => 33, 'program' => 'REGULER', 'school' => 'Reguler', 'user' => 'system', 'created_at' => $now],
            ['code' => 'XI.R-1', 'homeroom' => 16, 'program' => 'REGULER', 'school' => 'Reguler', 'user' => 'system', 'created_at' => $now],
            ['code' => 'XI.R-10', 'homeroom' => 50, 'program' => 'REGULER', 'school' => 'Reguler', 'user' => 'system', 'created_at' => $now],
            ['code' => 'XI.R-2', 'homeroom' => 82, 'program' => 'REGULER', 'school' => 'Reguler', 'user' => 'system', 'created_at' => $now],
            ['code' => 'XI.R-3', 'homeroom' => 66, 'program' => 'REGULER', 'school' => 'Reguler', 'user' => 'system', 'created_at' => $now],
            ['code' => 'XI.R-4', 'homeroom' => 38, 'program' => 'REGULER', 'school' => 'Reguler', 'user' => 'system', 'created_at' => $now],
            ['code' => 'XI.R-5', 'homeroom' => 55, 'program' => 'REGULER', 'school' => 'Reguler', 'user' => 'system', 'created_at' => $now],
            ['code' => 'XI.R-6', 'homeroom' => 91, 'program' => 'REGULER', 'school' => 'Reguler', 'user' => 'system', 'created_at' => $now],
            ['code' => 'XI.R-7', 'homeroom' => 84, 'program' => 'REGULER', 'school' => 'Reguler', 'user' => 'system', 'created_at' => $now],
            ['code' => 'XI.R-8', 'homeroom' => 90, 'program' => 'REGULER', 'school' => 'Reguler', 'user' => 'system', 'created_at' => $now],
            ['code' => 'XI.R-9', 'homeroom' => 70, 'program' => 'REGULER', 'school' => 'Reguler', 'user' => 'system', 'created_at' => $now]
        ];

        DB::table('classes')->insert($classes);
    }
}
