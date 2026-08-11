<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();
        $positions = [
            ['name' => 'Administrator','created_at' => $now],
            ['name' => 'Guru Kelas / Pengajar','created_at' => $now],
            ['name' => 'Wali Kelas','created_at' => $now],
            ['name' => 'Guru Piket','created_at' => $now],
            ['name' => 'Penanggung Jawab Kegiatan','created_at' => $now],
            ['name' => 'PH (Penanggung Jawab Harian)','created_at' => $now],
            ['name' => 'Kepala Departemen','created_at' => $now],
            ['name' => 'Kepala Sekolah','created_at' => $now],
        ];
        DB::table('positions')->insert($positions);

        // Default Users per Role
        $defaultUsers = [
            ['name' => 'Administrator Utama','username' => 'admin', 'password' => Hash::make('admin123'), 'gender' => 'L', 'position' => 1,'user' => 'system', 'created_at' => $now],
            ['name' => 'Guru Pengajar Test', 'username' => 'guru', 'password' => Hash::make('guru123'), 'gender' => 'L', 'position' => 2, 'user' => 'system', 'created_at' => $now],
            ['name' => 'Guru Wali Kelas X IPA 1', 'username' => 'walikelas', 'password' => Hash::make('walikelas123'), 'gender' => 'P', 'position' => 3, 'user' => 'system', 'created_at' => $now],
            ['name' => 'Guru Piket Sekolah', 'username' => 'piket', 'password' => Hash::make('piket123'), 'gender' => 'P', 'position' => 4, 'user' => 'system', 'created_at' => $now],
            ['name' => 'Penanggung Jawab BBQ/Dhuha', 'username' => 'pj_kegiatan', 'password' => Hash::make('pj123'), 'gender' => 'L', 'position' => 5, 'user' => 'system', 'created_at' => $now],
            ['name' => 'Bapak PH Harian', 'username' => 'ph', 'password' => Hash::make('ph123'), 'gender' => 'L', 'position' => 6, 'user' => 'system', 'created_at' => $now],
            ['name' => 'Kepala Departemen PESAT', 'username' => 'kadep', 'password' => Hash::make('kadep123'), 'gender' => 'L', 'position' => 7, 'user' => 'system', 'created_at' => $now],
            ['name' => 'Kepala Sekolah PESAT', 'username' => 'kepsek', 'password' => Hash::make('kepsek123'), 'gender' => 'L', 'position' => 8, 'user' => 'system', 'created_at' => $now],
        ];
        DB::table('users')->insert($defaultUsers);
    }
}
