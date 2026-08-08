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
            ['name' => 'Aga Yanu', 'username' => 'agayanu', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => $posMap['Administrator'] ?? 1, 'user' => 'system'],
            ['name' => 'R Sri Wilujeng S.Pd, M.Pd', 'username' => 'rsriwilujeng', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Wirya Aini, M.Pd.', 'username' => 'wiryaaini', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Dra. Ai Nurfaridah', 'username' => 'ainurfaridah', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Dra. Hj. Rini Komalasari', 'username' => 'rinikomalasari', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Asih Mulyani, S.Kom', 'username' => 'asihmulyani', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Syarifah, M.Pdi.', 'username' => 'syarifah', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Ike Yuniawati, S.Pd', 'username' => 'ikeyuniawati', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Tri Rahayu, M.Pd.', 'username' => 'trirahayu', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Fery Yantini, M.Pd', 'username' => 'feryyantini', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Ahmad Muhammad, S.Kom.', 'username' => 'ahmadmuhammad', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Gina Fariani, S.P.', 'username' => 'ginafariani', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Juju Juriah, S.P', 'username' => 'jujujuriah', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Iwan Sutiawan, S.Pd.', 'username' => 'iwansutiawan', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Fia Fianti, M.Pd.', 'username' => 'fiafianti', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Dedi Masri, S.Pd', 'username' => 'dedimasri', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Giyanti S.Pd', 'username' => 'giyanti', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Dhiena Farida, S.T', 'username' => 'dhienafarida', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Eki Syukeri,S.Pd.', 'username' => 'ekisyukeri', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Siti Ubahiyah,SE. M.Pd.', 'username' => 'sitiubahiyah', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Ika Muharyani,S.Pd.Gr.', 'username' => 'ikamuharyani', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Nisa Merdekawati,S.Psi. Gr', 'username' => 'nisamerdekawati', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Tiodora Sabarina,M.Pd.Gr', 'username' => 'tiodorasabarina', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Dwi Apriyani, S.Pd.Gr.', 'username' => 'dwiapriyani', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Dwi  Oktaviani,S.Psi. Gr', 'username' => 'dwioktaviani', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Diana Mustika Sari,S.Pd. Gr', 'username' => 'dianamustikasari', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Ai Warni, S.Pd.', 'username' => 'aiwarni', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Dinda Oktavia,A.Md.', 'username' => 'dindaoktavia', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Giri Indah Sari,S.Pd. Gr', 'username' => 'giriindahsari', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Wenza Anugrah,S.Pd.', 'username' => 'wenzaanugrah', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Januar Ambarwati,S.Pd.Gr.', 'username' => 'januarambarwati', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => "Ahmad Rifa'i,S.Pdi.", 'username' => 'ahmadrifai', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Geane Siskarani Yuanova,S.Psi.', 'username' => 'geanesiskaraniyuanova', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Dwi Pratiwi,S.Pd. Gr', 'username' => 'dwipratiwi', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Yusi Rakhmah Wati,S.Pd. Gr', 'username' => 'yusirakhmahwati', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Mukti Alinur,M.Pd.Gr', 'username' => 'muktialinur', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Intan Baedari, S.Pd.Gr', 'username' => 'intanbaedari', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Rizki Nurul Hidayah,S.Pd.Gr', 'username' => 'rizkinurulhidayah', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Annisa Febriana, P.Psi.', 'username' => 'annisafebriana', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Rizky Julian Pangestu Aji, S.Pd. Gr', 'username' => 'rizkyjulianpangestuaji', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Rendy Refsan Yami,S.Pd.', 'username' => 'rendyrefsanyami', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Kemala Saras Rianti, S.Pd.Gr', 'username' => 'kemalasarasrianti', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Nurzaman,S.Pd.Gr', 'username' => 'nurzaman', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Fikri Maulana Ridho, S.Pd', 'username' => 'fikrimaulanaridho', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Aditya Dwi Nugroho, S.Pd.Gr', 'username' => 'adityadwinugroho', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Asyifa Qolby Rakhaputri, S.Kom', 'username' => 'asyifaqolbyrakhaputri', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Lutfi Febrianyah, S.Pd', 'username' => 'lutfifebrianyah', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Annisa Marheliyana, S.Pd.', 'username' => 'annisamarheliyana', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Muhammad Falahaen Jiddan,S.Pd.', 'username' => 'muhammadfalahaenjiddan', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Rahmat Robani, S.Or.', 'username' => 'rahmatrobani', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Dwi Murni Melisa Putri, S.Pd.Gr', 'username' => 'dwimurnimelisaputri', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Nurul Indah Fitriana, S.Pd. Gr', 'username' => 'nurulindahfitriana', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Maya Nurul, S.Pd.Gr', 'username' => 'mayanurul', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Chamelia Asmarafuti, M.Pd', 'username' => 'chameliaasmarafuti', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Indira Giri Dwi Prameswari, S.Pd', 'username' => 'indiragiridwiprameswari', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Meli Nuryanti Amir, S.Psi.', 'username' => 'melinuryantiamir', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Chafidha Torenia W, S.Pd', 'username' => 'chafidhatoreniaw', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Rizal F, S.Pd', 'username' => 'rizalf', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Yeti Susilawati, S.Pd', 'username' => 'yetisusilawati', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Puti Ayudya, S.Sos', 'username' => 'putiayudya', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Muhammad Ardi Budiawan, S.Pd', 'username' => 'muhammadardibudiawan', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Farras Alifa Semendawai, S.Sos. M.Han', 'username' => 'farrasalifasemendawai', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Rizky Lingga Ayuningtyas, M.H', 'username' => 'rizkylinggaayuningtyas', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Uyan Ahmad Satibi, M.Pd', 'username' => 'uyanahmadsatibi', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Nurul Asih, S.Pd', 'username' => 'nurulasih', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Indah Larasati, S.Sos', 'username' => 'indahlarasati', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Khanza Bilbina, S.Pd', 'username' => 'khanzabilbina', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Luthfia Rahma, ', 'username' => 'luthfiarahma', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Novika Priliyana, S.Pd.Gr', 'username' => 'novikapriliyana', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Tio Dwi Akbar, S.T', 'username' => 'tiodwiakbar', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Shafa Dania, S.Kom', 'username' => 'shafadania', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Anugrah, S.Pd', 'username' => 'anugrah', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Raisya Nasywa A, S.Pd', 'username' => 'raisyanasywaa', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Muhammad Nabil Fauzul, S.Pd', 'username' => 'muhammadnabilfauzul', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Gita Ayundari, S.Pd.Gr', 'username' => 'gitaayundari', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Farely, S.Pd', 'username' => 'farely', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'Ria Sulistiawati, S.Pd.Gr', 'username' => 'riasulistiawati', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => $posMap['Guru Kelas / Pengajar'] ?? 2, 'user' => 'system'],
            ['name' => 'M. Ilham Aulia', 'username' => 'milhamaulia', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => $posMap['Guru Piket'] ?? 3, 'user' => 'system'],
            ['name' => 'Aulia Dhava Wima', 'username' => 'adhavawima', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => $posMap['Penanggung Jawab Kegiatan'] ?? 4, 'user' => 'system'],
            ['name' => 'Liddia Hendriati , M.Pd', 'username' => 'liddiahendriati', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => $posMap['PH (Penanggung Jawab Harian)'] ?? 5, 'user' => 'system'],
            ['name' => 'Ika Septianawati M.Pd', 'username' => 'ikaseptianawati', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => $posMap['PH (Penanggung Jawab Harian)'] ?? 5, 'user' => 'system'],
            ['name' => 'Sri Hayati,M.Pd.', 'username' => 'srihayati', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => $posMap['PH (Penanggung Jawab Harian)'] ?? 5, 'user' => 'system'],
            ['name' => 'Oskal Frananda, S.Kom', 'username' => 'oskalfrananda', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => $posMap['PH (Penanggung Jawab Harian)'] ?? 5, 'user' => 'system'],
            ['name' => 'Yanuar Hardi H.,S.Pd.Si.Gr', 'username' => 'yanuarhardi', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => $posMap['PH (Penanggung Jawab Harian)'] ?? 5, 'user' => 'system'],
            ['name' => 'Titi Rahmawati, S.Si.MT.Gr', 'username' => 'titirahmawati', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => $posMap['PH (Penanggung Jawab Harian)'] ?? 5, 'user' => 'system'],
            ['name' => 'Supriyadi, S.Kom', 'username' => 'supriyadi', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => $posMap['PH (Penanggung Jawab Harian)'] ?? 5, 'user' => 'system'],
            ['name' => 'Aman Abdurahman,S.Pdi.', 'username' => 'amanabdurahman', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => $posMap['PH (Penanggung Jawab Harian)'] ?? 5, 'user' => 'system'],
            ['name' => 'Purwonugroho, S.Pd.Gr.', 'username' => 'purwonugroho', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => $posMap['PH (Penanggung Jawab Harian)'] ?? 5, 'user' => 'system'],
            ['name' => 'Nasukha Z.,M.Pd.', 'username' => 'nasukha', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => $posMap['Kepala Departemen'] ?? 6, 'user' => 'system'],
            ['name' => 'Iwan Gunawan M.Pd', 'username' => 'iwangunawan', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => $posMap['Kepala Departemen'] ?? 6, 'user' => 'system'],
            ['name' => 'Freddy Siahaan M.Pd', 'username' => 'freddysiahaan', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => $posMap['Kepala Departemen'] ?? 6, 'user' => 'system'],
            ['name' => 'Ari Sucipto, SS.BA, MTSOOL', 'username' => 'arisucipto', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => $posMap['Kepala Departemen'] ?? 6, 'user' => 'system'],
            ['name' => 'Dr. Iwan Cakrayana, S.T., S.Pd., M.Si., Gr.', 'username' => 'icakrayana', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => $posMap['Kepala Sekolah'] ?? 7, 'user' => 'system'],
            ['name' => 'Afra Fitriani, S.S., S.Pd., M.Pd., Gr.', 'username' => 'afitriani', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => $posMap['Kepala Sekolah'] ?? 7, 'user' => 'system'],
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
