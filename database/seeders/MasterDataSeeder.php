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
        $now = now();
        $positions = [
            1 => 'Administrator',
            2 => 'Guru Kelas / Pengajar',
            3 => 'Wali Kelas',
            4 => 'Guru Piket',
            5 => 'Penanggung Jawab Kegiatan',
            6 => 'PH (Penanggung Jawab Harian)',
            7 => 'Kepala Departemen',
            8 => 'Kepala Sekolah',
        ];

        foreach ($positions as $id => $name) {
            DB::table('positions')->updateOrInsert(
                ['id' => $id],
                ['name' => $name, 'created_at' => $now, 'updated_at' => $now]
            );
        }

        // List of all school staff & users
        $defaultUsers = [
            ['name' => 'Aga Yanu', 'username' => 'agayanu', 'password' => Hash::make('admin123'), 'gender' => 'L', 'position' => 1, 'user' => 'system', 'positions' => [1]],
            ['name' => 'Dr. Iwan Cakrayana, S.T., S.Pd., M.Si., Gr.', 'username' => 'icakrayana', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => 8, 'user' => 'system', 'positions' => [8]],
            ['name' => 'Afra Fitriani, S.S., S.Pd., M.Pd., Gr.', 'username' => 'afitriani', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 8, 'user' => 'system', 'positions' => [8]],
            ['name' => 'Nasukha Z.,M.Pd.', 'username' => 'nasukha', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => 7, 'user' => 'system', 'positions' => [2,7]],
            ['name' => 'Iwan Gunawan M.Pd', 'username' => 'iwangunawan', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => 7, 'user' => 'system', 'positions' => [2,7]],
            ['name' => 'Freddy Siahaan M.Pd', 'username' => 'freddysiahaan', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => 7, 'user' => 'system', 'positions' => [2,7]],
            ['name' => 'Ari Sucipto, SS.BA, MTSOOL', 'username' => 'arisucipto', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => 7, 'user' => 'system', 'positions' => [2,3,7]],
            ['name' => 'Liddia Hendriati , M.Pd', 'username' => 'liddiahendriati', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 6, 'user' => 'system', 'positions' => [2,3,6]],
            ['name' => 'Ika Septianawati M.Pd', 'username' => 'ikaseptianawati', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 6, 'user' => 'system', 'positions' => [2,3,6]],
            ['name' => 'Sri Hayati,M.Pd.', 'username' => 'srihayati', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 6, 'user' => 'system', 'positions' => [2,3,6]],
            ['name' => 'Oskal Frananda, S.Kom', 'username' => 'oskalfrananda', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => 6, 'user' => 'system', 'positions' => [2,6]],
            ['name' => 'Yanuar Hardi H.,S.Pd.Si.Gr', 'username' => 'yanuarhardi', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => 6, 'user' => 'system', 'positions' => [2,6]],
            ['name' => 'Titi Rahmawati, S.Si.MT.Gr', 'username' => 'titirahmawati', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 6, 'user' => 'system', 'positions' => [2,3,6]],
            ['name' => 'Supriyadi, S.Kom', 'username' => 'supriyadi', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => 6, 'user' => 'system', 'positions' => [2,3,6]],
            ['name' => 'Aman Abdurahman,S.Pdi.', 'username' => 'amanabdurahman', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => 6, 'user' => 'system', 'positions' => [2,6]],
            ['name' => 'Purwonugroho, S.Pd.Gr.', 'username' => 'purwonugroho', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => 6, 'user' => 'system', 'positions' => [2,3,6]],
            ['name' => 'Wirya Aini,M.Pd.', 'username' => 'wiryaaini', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 2, 'user' => 'system', 'positions' => [2]],
            ['name' => 'R Sri Wilujeng S.Pd, M.Pd', 'username' => 'rsriwilujeng', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 2, 'user' => 'system', 'positions' => [2,3]],
            ['name' => 'Dra. Ai Nurfaridah', 'username' => 'ainurfaridah', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 2, 'user' => 'system', 'positions' => [2,3]],
            ['name' => 'Dra. Hj. Rini Komalasari', 'username' => 'rinikomalasari', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 2, 'user' => 'system', 'positions' => [2,3]],
            ['name' => 'Asih Mulyani, S.Kom', 'username' => 'asihmulyani', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 2, 'user' => 'system', 'positions' => [2,3]],
            ['name' => 'Syarifah, M.Pdi.', 'username' => 'syarifah', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 2, 'user' => 'system', 'positions' => [2,3,5]],
            ['name' => 'Ike Yuniawati, S.Pd', 'username' => 'ikeyuniawati', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 2, 'user' => 'system', 'positions' => [2,3]],
            ['name' => 'Tri Rahayu, M.Pd.', 'username' => 'trirahayu', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 2, 'user' => 'system', 'positions' => [2,3]],
            ['name' => 'Fery Yantini, M.Pd', 'username' => 'feryyantini', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 2, 'user' => 'system', 'positions' => [2,3]],
            ['name' => 'Ahmad Muhammad, S.Kom.', 'username' => 'ahmadmuhammad', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => 2, 'user' => 'system', 'positions' => [2,5]],
            ['name' => 'Gina Fariani, S.P.', 'username' => 'ginafariani', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 2, 'user' => 'system', 'positions' => [2,3]],
            ['name' => 'Juju Juriah, S.P', 'username' => 'jujujuriah', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 2, 'user' => 'system', 'positions' => [2,3]],
            ['name' => 'Iwan Sutiawan, S.Pd.', 'username' => 'iwansutiawan', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => 2, 'user' => 'system', 'positions' => [2,3]],
            ['name' => 'Fia Fianti, M.Pd.', 'username' => 'fiafianti', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 2, 'user' => 'system', 'positions' => [2,3]],
            ['name' => 'Dedi Masri, S.Pd', 'username' => 'dedimasri', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => 2, 'user' => 'system', 'positions' => [2]],
            ['name' => 'Giyanti S.Pd', 'username' => 'giyanti', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 2, 'user' => 'system', 'positions' => [2,3]],
            ['name' => 'Dhiena Farida, S.T', 'username' => 'dhienafarida', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 2, 'user' => 'system', 'positions' => [2,3,4,5]],
            ['name' => 'Eki Syukeri,S.Pd.', 'username' => 'ekisyukeri', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => 2, 'user' => 'system', 'positions' => [2]],
            ['name' => 'Siti Ubahiyah,SE. M.Pd.', 'username' => 'sitiubahiyah', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 2, 'user' => 'system', 'positions' => [2,3,5]],
            ['name' => 'Ika Muharyani,S.Pd.Gr.', 'username' => 'ikamuharyani', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 2, 'user' => 'system', 'positions' => [2,3]],
            ['name' => 'Nisa Merdekawati,S.Psi. Gr', 'username' => 'nisamerdekawati', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 2, 'user' => 'system', 'positions' => [2,4,5]],
            ['name' => 'Tiodora Sabarina,M.Pd.Gr', 'username' => 'tiodorasabarina', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 2, 'user' => 'system', 'positions' => [2,3]],
            ['name' => 'Dwi Apriyani, S.Pd.Gr.', 'username' => 'dwiapriyani', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 2, 'user' => 'system', 'positions' => [2,3]],
            ['name' => 'Dwi  Oktaviani,S.Psi. Gr', 'username' => 'dwioktaviani', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 2, 'user' => 'system', 'positions' => [2,4,5]],
            ['name' => 'Diana Mustika Sari,S.Pd. Gr', 'username' => 'dianamustikasari', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 2, 'user' => 'system', 'positions' => [2,3]],
            ['name' => 'Ai Warni, S.Pd.', 'username' => 'aiwarni', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 2, 'user' => 'system', 'positions' => [2,3]],
            ['name' => 'Dinda Oktavia,A.Md.', 'username' => 'dindaoktavia', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 2, 'user' => 'system', 'positions' => [2,4]],
            ['name' => 'Giri Indah Sari,S.Pd. Gr', 'username' => 'giriindahsari', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 2, 'user' => 'system', 'positions' => [2,3]],
            ['name' => 'Wenza Anugrah,S.Pd.', 'username' => 'wenzaanugrah', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => 2, 'user' => 'system', 'positions' => [2,4]],
            ['name' => 'Januar Ambarwati,S.Pd.Gr.', 'username' => 'januarambarwati', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 2, 'user' => 'system', 'positions' => [2,3]],
            ['name' => "Ahmad Rifa'i,S.Pdi.", 'username' => 'ahmadrifai', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => 2, 'user' => 'system', 'positions' => [2,3]],
            ['name' => 'Geane Siskarani Yuanova,S.Psi.', 'username' => 'geanesiskaraniyuanova', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 2, 'user' => 'system', 'positions' => [2,4,5]],
            ['name' => 'Dwi Pratiwi,S.Pd. Gr', 'username' => 'dwipratiwi', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 2, 'user' => 'system', 'positions' => [2,3]],
            ['name' => 'Yusi Rakhmah Wati,S.Pd. Gr', 'username' => 'yusirakhmahwati', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 2, 'user' => 'system', 'positions' => [2,3]],
            ['name' => 'Mukti Alinur,M.Pd.Gr', 'username' => 'muktialinur', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => 2, 'user' => 'system', 'positions' => [2,3]],
            ['name' => 'Intan Baedari, S.Pd.Gr', 'username' => 'intanbaedari', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 2, 'user' => 'system', 'positions' => [2,3]],
            ['name' => 'Rizki Nurul Hidayah,S.Pd.Gr', 'username' => 'rizkinurulhidayah', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 2, 'user' => 'system', 'positions' => [2,3]],
            ['name' => 'Annisa Febriana, P.Psi.', 'username' => 'annisafebriana', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 2, 'user' => 'system', 'positions' => [2,5]],
            ['name' => 'Rizky Julian Pangestu Aji, S.Pd. Gr', 'username' => 'rizkyjulianpangestuaji', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => 2, 'user' => 'system', 'positions' => [2,3,5]],
            ['name' => 'Rendy Refsan Yami,S.Pd.', 'username' => 'rendyrefsanyami', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => 2, 'user' => 'system', 'positions' => [2,4,5]],
            ['name' => 'Kemala Saras Rianti, S.Pd.Gr', 'username' => 'kemalasarasrianti', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 2, 'user' => 'system', 'positions' => [2,3]],
            ['name' => 'Nurzaman,S.Pd.Gr', 'username' => 'nurzaman', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => 2, 'user' => 'system', 'positions' => [2,3,5]],
            ['name' => 'Fikri Maulana Ridho, S.Pd', 'username' => 'fikrimaulanaridho', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => 2, 'user' => 'system', 'positions' => [2,3]],
            ['name' => 'Aditya Dwi Nugroho, S.Pd.Gr', 'username' => 'adityadwinugroho', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => 2, 'user' => 'system', 'positions' => [2,3]],
            ['name' => 'Asyifa Qolby Rakhaputri, S.Kom', 'username' => 'asyifaqolbyrakhaputri', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 2, 'user' => 'system', 'positions' => [2,3]],
            ['name' => 'Lutfi Febrianyah, S.Pd', 'username' => 'lutfifebrianyah', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => 2, 'user' => 'system', 'positions' => [2,4]],
            ['name' => 'Annisa Marheliyana, S.Pd.', 'username' => 'annisamarheliyana', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 2, 'user' => 'system', 'positions' => [2,3]],
            ['name' => 'Muhammad Falahaen Jiddan,S.Pd.', 'username' => 'muhammadfalahaenjiddan', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => 2, 'user' => 'system', 'positions' => [2,3]],
            ['name' => 'Rahmat Robani, S.Or.', 'username' => 'rahmatrobani', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => 2, 'user' => 'system', 'positions' => [2,4]],
            ['name' => 'Dwi Murni Melisa Putri, S.Pd.Gr', 'username' => 'dwimurnimelisaputri', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 2, 'user' => 'system', 'positions' => [2,3,5]],
            ['name' => 'Nurul Indah Fitriana, S.Pd. Gr', 'username' => 'nurulindahfitriana', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 2, 'user' => 'system', 'positions' => [2,3]],
            ['name' => 'Maya Nurul, S.Pd.Gr', 'username' => 'mayanurul', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 2, 'user' => 'system', 'positions' => [2,3,5]],
            ['name' => 'Chamelia Asmarafuti, M.Pd', 'username' => 'chameliaasmarafuti', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 2, 'user' => 'system', 'positions' => [2,3]],
            ['name' => 'Indira Giri Dwi Prameswari, S.Pd', 'username' => 'indiragiridwiprameswari', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 2, 'user' => 'system', 'positions' => [2,3]],
            ['name' => 'Meli Nuryanti Amir, S.Psi.', 'username' => 'melinuryantiamir', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 2, 'user' => 'system', 'positions' => [2,5]],
            ['name' => 'Chafidha Torenia W, S.Pd', 'username' => 'chafidhatoreniaw', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 2, 'user' => 'system', 'positions' => [2,3]],
            ['name' => 'Rizal F, S.Pd', 'username' => 'rizalf', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => 2, 'user' => 'system', 'positions' => [2]],
            ['name' => 'Yeti Susilawati, S.Pd', 'username' => 'yetisusilawati', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 2, 'user' => 'system', 'positions' => [2,3,5]],
            ['name' => 'Puti Ayudya, S.Sos', 'username' => 'putiayudya', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 2, 'user' => 'system', 'positions' => [2]],
            ['name' => 'Muhammad Ardi Budiawan, S.Pd', 'username' => 'muhammadardibudiawan', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => 2, 'user' => 'system', 'positions' => [2,3,5]],
            ['name' => 'Farras Alifa Semendawai, S.Sos. M.Han', 'username' => 'farrasalifasemendawai', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 2, 'user' => 'system', 'positions' => [2,3]],
            ['name' => 'Rizky Lingga Ayuningtyas, M.H', 'username' => 'rizkylinggaayuningtyas', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 2, 'user' => 'system', 'positions' => [2,4]],
            ['name' => 'Uyan Ahmad Satibi, M.Pd', 'username' => 'uyanahmadsatibi', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => 2, 'user' => 'system', 'positions' => [2,3,5]],
            ['name' => 'Nurul Asih, S.Pd', 'username' => 'nurulasih', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 2, 'user' => 'system', 'positions' => [2,3,5]],
            ['name' => 'Indah Larasati, S.Sos', 'username' => 'indahlarasati', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 2, 'user' => 'system', 'positions' => [2,3]],
            ['name' => 'Khanza Bilbina, S.Pd', 'username' => 'khanzabilbina', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 2, 'user' => 'system', 'positions' => [2,3,5]],
            ['name' => 'Luthfia Rahma, ', 'username' => 'luthfiarahma', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 2, 'user' => 'system', 'positions' => [2]],
            ['name' => 'Novika Priliyana, S.Pd.Gr', 'username' => 'novikapriliyana', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 2, 'user' => 'system', 'positions' => [2]],
            ['name' => 'Tio Dwi Akbar, S.T', 'username' => 'tiodwiakbar', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => 2, 'user' => 'system', 'positions' => [2]],
            ['name' => 'Shafa Dania, S.Kom', 'username' => 'shafadania', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 2, 'user' => 'system', 'positions' => [2,3]],
            ['name' => 'Anugrah, S.Pd', 'username' => 'anugrah', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => 2, 'user' => 'system', 'positions' => [2,4]],
            ['name' => 'Raisya Nasywa A, S.Pd', 'username' => 'raisyanasywaa', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 2, 'user' => 'system', 'positions' => [2,3,5]],
            ['name' => 'Muhammad Nabil Fauzul, S.Pd', 'username' => 'muhammadnabilfauzul', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => 2, 'user' => 'system', 'positions' => [2,3,5]],
            ['name' => 'Gita Ayundari, S.Pd.Gr', 'username' => 'gitaayundari', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 2, 'user' => 'system', 'positions' => [2,3,4]],
            ['name' => 'Farely, S.Pd', 'username' => 'farely', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 2, 'user' => 'system', 'positions' => [2,3,4]],
            ['name' => 'Ria Sulistiawati, S.Pd.Gr', 'username' => 'riasulistiawati', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 2, 'user' => 'system', 'positions' => [2,4]],
            ['name' => 'Ilham Aulia', 'username' => 'ilhamaulia', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => 4, 'user' => 'system', 'positions' => [4]],
            ['name' => 'Nur Adita', 'username' => 'nuradita', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 2, 'user' => 'system', 'positions' => [2]],
            ['name' => 'Astari Sekar S., S.Hum.', 'username' => 'astarisekars', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 2, 'user' => 'system', 'positions' => [2]],
            ['name' => 'Nurulia Falah, S.Sos.', 'username' => 'nuruliafalah', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 2, 'user' => 'system', 'positions' => [2]],
            ['name' => 'Roni Hermawan', 'username' => 'ronihermawan', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => 2, 'user' => 'system', 'positions' => [2]],
            ['name' => 'Herlambang Irdiansyah S.', 'username' => 'herlambangirdiansyahs', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => 2, 'user' => 'system', 'positions' => [2]],
            ['name' => 'Rohajon', 'username' => 'rohajon', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => 2, 'user' => 'system', 'positions' => [2]],
            ['name' => 'Andi Sylvia F., S.E., A.Md.Kep', 'username' => 'andisylviaf', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 2, 'user' => 'system', 'positions' => [2]],
            ['name' => 'Dian Adesti, S.Kom.', 'username' => 'dianadesti', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 2, 'user' => 'system', 'positions' => [2]],
            ['name' => 'Nuri Nurjannah H., S.Pd.', 'username' => 'nurinurjannahh', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 2, 'user' => 'system', 'positions' => [2]],
            ['name' => 'Dewi Maytasari Diyani, S.Pd.', 'username' => 'dewimaytasaridiyani', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 2, 'user' => 'system', 'positions' => [2]],
            ['name' => 'Kukuh Firdaus', 'username' => 'kukuhfirdaus', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => 2, 'user' => 'system', 'positions' => [2]],
            ['name' => 'Hodirin Susanto', 'username' => 'hodirinsusanto', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => 2, 'user' => 'system', 'positions' => [2]],
            ['name' => 'Ilham Eka Permana', 'username' => 'ilhamekapermana', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => 2, 'user' => 'system', 'positions' => [2]],
            ['name' => 'Eneng Hindayah, S.Sn.', 'username' => 'enenghindayah', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 2, 'user' => 'system', 'positions' => [2]],
            ['name' => 'Dra. Rini Diah Astuti', 'username' => 'rinidiahastuti', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 2, 'user' => 'system', 'positions' => [2]],
            ['name' => 'Novani Nur Wijaya', 'username' => 'novaninurwijaya', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 2, 'user' => 'system', 'positions' => [2]],
            ['name' => 'Dewinta Nurhayati, S.Pd.', 'username' => 'dewintanurhayati', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 2, 'user' => 'system', 'positions' => [2]],
            ['name' => 'Dra. Kania Dewi', 'username' => 'kaniadewi', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 2, 'user' => 'system', 'positions' => [2]],
            ['name' => 'Muhidin, S.Kom', 'username' => 'muhidin', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => 2, 'user' => 'system', 'positions' => [2]],
            ['name' => 'Aga Yanupraba, S.T.', 'username' => 'agayanupraba', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => 2, 'user' => 'system', 'positions' => [2]],
            ['name' => 'Auliya Dhava Wima', 'username' => 'auliyadhavawima', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => 5, 'user' => 'system', 'positions' => [5]],
            ['name' => 'Muhammad Farhan Aula, S.Kom.', 'username' => 'muhammadfarhanaula', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => 5, 'user' => 'system', 'positions' => [5]],
            ['name' => 'Yanda Muhaimin', 'username' => 'kegiatan', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => 5, 'user' => 'system', 'positions' => [5]],
            ['name' => 'Rodalih, S.Pd.I.', 'username' => 'rodalih', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => 5, 'user' => 'system', 'positions' => [5]],
            ['name' => 'dr. Dewi Maulida Apsari', 'username' => 'dewimaulidaapsari', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 5, 'user' => 'system', 'positions' => [5]],
            ['name' => 'Vija Saputra, S.E.', 'username' => 'vijasaputra', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => 5, 'user' => 'system', 'positions' => [5]],
            ['name' => 'Dini Aliyanih, S.Pd.', 'username' => 'dinialiyanih', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 5, 'user' => 'system', 'positions' => [5]],
            ['name' => 'Abdul Hakim', 'username' => 'abdulhakim', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => 5, 'user' => 'system', 'positions' => [5]],
            ['name' => 'Nailla Azzahra Nur Ashyfa, S.Pd', 'username' => 'naillaazzahranurashyfa', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 5, 'user' => 'system', 'positions' => [5]],
            ['name' => 'Fatahillah', 'username' => 'fatahillah', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => 5, 'user' => 'system', 'positions' => [5]],
            ['name' => 'Achmad Rizky Eka Syaputra', 'username' => 'achmadrizkyekasyaputra', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => 5, 'user' => 'system', 'positions' => [5]],
            ['name' => 'Rizal Afrianto Wibowo, S.T. ', 'username' => 'rizalafriantowibowo', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => 5, 'user' => 'system', 'positions' => [5]],
            ['name' => 'Guntur Cahyono', 'username' => 'gunturcahyono', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => 5, 'user' => 'system', 'positions' => [5]],
            ['name' => 'Rendra Irawan A.Md S.Pd', 'username' => 'rendrairawana.mds.pd', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => 5, 'user' => 'system', 'positions' => [5]],
            ['name' => 'SAPUTRI QURNIASARI', 'username' => 'saputriqurniasari', 'password' => Hash::make('123456'), 'gender' => 'P', 'position' => 5, 'user' => 'system', 'positions' => [5]],
            ['name' => 'Wahyudi', 'username' => 'wahyudi', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => 5, 'user' => 'system', 'positions' => [5]],
            ['name' => 'Doni Dartafian Amirudin  S.Pd., Gr. ', 'username' => 'donidartafianamirudins.pd.', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => 5, 'user' => 'system', 'positions' => [5]],
            ['name' => 'Ujang Ruspendi', 'username' => 'ujangruspendi', 'password' => Hash::make('123456'), 'gender' => 'L', 'position' => 5, 'user' => 'system', 'positions' => [5]]
        ];

        foreach ($defaultUsers as $userData) {
            $posList = $userData['positions'];
            unset($userData['positions']);

            // $existingUser = User::where('username', $userData['username'])->first();
            $existingUser = User::firstWhere('username', $userData['username']);
            if (!$existingUser) {
                $userObj = User::create(array_merge($userData, [
                    'created_at' => $now,
                ]));
                // $userObj->positions()->sync($posList);
                $syncData = collect($posList)->mapWithKeys(fn ($id) => [
                    $id => [
                        'created_at' => $now,
                    ],
                ])->all();

                $userObj->positions()->sync($syncData);
            } else {
                $existingUser->update($userData);
                $existingUser->positions()->sync($posList);
            }
        }
    }
}
