<?php

namespace Database\Seeders;

use App\Models\User;
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

        // Default Users with Single or Multiple Positions
        $defaultUsers = [
            [
                'name'      => 'Administrator Utama',
                'username'  => 'admin',
                'password'  => Hash::make('admin123'),
                'gender'    => 'L',
                'position'  => $posMap['Administrator'] ?? 1,
                'user'      => 'system',
                'positions' => [$posMap['Administrator']],
            ],
            [
                'name'      => 'Tyo, S.Pd. (Multi-Jabatan)',
                'username'  => 'tyo',
                'password'  => Hash::make('tyo123'),
                'gender'    => 'L',
                'position'  => $posMap['Guru Kelas / Pengajar'] ?? 2,
                'user'      => 'system',
                // Tyo holds 4 positions simultaneously!
                'positions' => [
                    $posMap['Guru Kelas / Pengajar'],
                    $posMap['Wali Kelas'],
                    $posMap['Penanggung Jawab Kegiatan'],
                    $posMap['PH (Penanggung Jawab Harian)'],
                ],
            ],
            [
                'name'      => 'Guru Pengajar Test',
                'username'  => 'guru',
                'password'  => Hash::make('guru123'),
                'gender'    => 'L',
                'position'  => $posMap['Guru Kelas / Pengajar'] ?? 2,
                'user'      => 'system',
                'positions' => [$posMap['Guru Kelas / Pengajar']],
            ],
            [
                'name'      => 'Guru Wali Kelas X IPA 1',
                'username'  => 'walikelas',
                'password'  => Hash::make('walikelas123'),
                'gender'    => 'P',
                'position'  => $posMap['Wali Kelas'] ?? 3,
                'user'      => 'system',
                'positions' => [$posMap['Wali Kelas']],
            ],
            [
                'name'      => 'Guru Piket Sekolah',
                'username'  => 'piket',
                'password'  => Hash::make('piket123'),
                'gender'    => 'P',
                'position'  => $posMap['Guru Piket'] ?? 4,
                'user'      => 'system',
                'positions' => [$posMap['Guru Piket']],
            ],
            [
                'name'      => 'Penanggung Jawab BBQ/Dhuha',
                'username'  => 'pj_kegiatan',
                'password'  => Hash::make('pj123'),
                'gender'    => 'L',
                'position'  => $posMap['Penanggung Jawab Kegiatan'] ?? 5,
                'user'      => 'system',
                'positions' => [$posMap['Penanggung Jawab Kegiatan']],
            ],
            [
                'name'      => 'Bapak PH Harian',
                'username'  => 'ph',
                'password'  => Hash::make('ph123'),
                'gender'    => 'L',
                'position'  => $posMap['PH (Penanggung Jawab Harian)'] ?? 6,
                'user'      => 'system',
                'positions' => [$posMap['PH (Penanggung Jawab Harian)']],
            ],
            [
                'name'      => 'Kepala Departemen PESAT',
                'username'  => 'kadep',
                'password'  => Hash::make('kadep123'),
                'gender'    => 'L',
                'position'  => $posMap['Kepala Departemen'] ?? 7,
                'user'      => 'system',
                'positions' => [$posMap['Kepala Departemen']],
            ],
            [
                'name'      => 'Kepala Sekolah PESAT',
                'username'  => 'kepsek',
                'password'  => Hash::make('kepsek123'),
                'gender'    => 'L',
                'position'  => $posMap['Kepala Sekolah'] ?? 8,
                'user'      => 'system',
                'positions' => [$posMap['Kepala Sekolah']],
            ],
        ];

        foreach ($defaultUsers as $userData) {
            $posList = $userData['positions'];
            unset($userData['positions']);

            $existingUser = User::where('username', $userData['username'])->first();
            if (!$existingUser) {
                $newUser = User::create(array_merge($userData, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
                $newUser->positions()->sync($posList);
            } else {
                $existingUser->positions()->sync($posList);
            }
        }
    }
}
