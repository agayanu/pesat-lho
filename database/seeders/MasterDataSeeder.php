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
        // 1. Seed Positions
        $posAdmin = DB::table('positions')->insertGetId([
            'name'       => 'Administrator',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $posGuru = DB::table('positions')->insertGetId([
            'name'       => 'Guru',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Seed Default Admin User if not exist
        if (DB::table('users')->where('username', 'admin')->count() == 0) {
            DB::table('users')->insert([
                'name'       => 'Administrator Utama',
                'username'   => 'admin',
                'password'   => Hash::make('admin123'),
                'gender'     => 'L',
                'position'   => $posAdmin,
                'user'       => 'system',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
