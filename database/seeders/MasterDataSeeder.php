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
        $positions = [
            'Administrator',
            'Guru Kelas / Pengajar',
            'Wali Kelas',
            'Guru Piket',
            'Penanggung Jawab Kegiatan',
            'PH (Penanggung Jawab Harian)',
            'Kepala Departemen',
            'Kepala Sekolah',
        ];

        $posMap = [];
        foreach ($positions as $posName) {
            $existing = DB::table('positions')->where('name', $posName)->first();
            if (!$existing) {
                $id = DB::table('positions')->insertGetId([
                    'name'       => $posName,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $posMap[$posName] = $id;
            } else {
                $posMap[$posName] = $existing->id;
            }
        }

        // Default Users per Role
        $defaultUsers = [
            [
                'name'     => 'Administrator Utama',
                'username' => 'admin',
                'password' => Hash::make('admin123'),
                'gender'   => 'L',
                'position' => $posMap['Administrator'] ?? 1,
                'user'     => 'system',
            ],
            [
                'name'     => 'Guru Pengajar Test',
                'username' => 'guru',
                'password' => Hash::make('guru123'),
                'gender'   => 'L',
                'position' => $posMap['Guru Kelas / Pengajar'] ?? 2,
                'user'     => 'system',
            ],
            [
                'name'     => 'Guru Wali Kelas X IPA 1',
                'username' => 'walikelas',
                'password' => Hash::make('walikelas123'),
                'gender'   => 'P',
                'position' => $posMap['Wali Kelas'] ?? 3,
                'user'     => 'system',
            ],
            [
                'name'     => 'Guru Piket Sekolah',
                'username' => 'piket',
                'password' => Hash::make('piket123'),
                'gender'   => 'P',
                'position' => $posMap['Guru Piket'] ?? 4,
                'user'     => 'system',
            ],
            [
                'name'     => 'Penanggung Jawab BBQ/Dhuha',
                'username' => 'pj_kegiatan',
                'password' => Hash::make('pj123'),
                'gender'   => 'L',
                'position' => $posMap['Penanggung Jawab Kegiatan'] ?? 5,
                'user'     => 'system',
            ],
            [
                'name'     => 'Bapak PH Harian',
                'username' => 'ph',
                'password' => Hash::make('ph123'),
                'gender'   => 'L',
                'position' => $posMap['PH (Penanggung Jawab Harian)'] ?? 6,
                'user'     => 'system',
            ],
            [
                'name'     => 'Kepala Departemen PESAT',
                'username' => 'kadep',
                'password' => Hash::make('kadep123'),
                'gender'   => 'L',
                'position' => $posMap['Kepala Departemen'] ?? 7,
                'user'     => 'system',
            ],
            [
                'name'     => 'Kepala Sekolah PESAT',
                'username' => 'kepsek',
                'password' => Hash::make('kepsek123'),
                'gender'   => 'L',
                'position' => $posMap['Kepala Sekolah'] ?? 8,
                'user'     => 'system',
            ],
        ];

        foreach ($defaultUsers as $userData) {
            if (DB::table('users')->where('username', $userData['username'])->count() == 0) {
                DB::table('users')->insert(array_merge($userData, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
        }
    }
}
