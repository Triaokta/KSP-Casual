<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BankSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        // 2. KOSONGKAN tabel (sekarang akan berhasil)
        DB::table('banks')->truncate();

        // 3. AKTIFKAN KEMBALI aturan tersebut (SANGAT PENTING!)
        Schema::enableForeignKeyConstraints();

        DB::table('banks')->insert([
        // --- BANK BESAR NASIONAL ---
        ['code' => '002', 'name' => 'Bank Rakyat Indonesia (BRI)'],
        ['code' => '008', 'name' => 'Bank Mandiri'],
        ['code' => '009', 'name' => 'Bank Negara Indonesia (BNI)'],
        ['code' => '014', 'name' => 'Bank Central Asia (BCA)'],
        ['code' => '200', 'name' => 'Bank Tabungan Negara (BTN)'],

        // --- BANK SWASTA / DIGITAL ---
        ['code' => '011', 'name' => 'Bank Danamon'],
        ['code' => '013', 'name' => 'Bank Permata'],
        ['code' => '016', 'name' => 'Bank Maybank Indonesia'],
        ['code' => '019', 'name' => 'Bank Panin'],
        ['code' => '022', 'name' => 'CIMB Niaga'],
        ['code' => '028', 'name' => 'Bank OCBC NISP'],
        ['code' => '048', 'name' => 'Bank UOB Indonesia'],
        ['code' => '153', 'name' => 'Bank Sinarmas'],
        ['code' => '213', 'name' => 'Bank BTPN'],
        ['code' => '426', 'name' => 'Bank Mega'],
        ['code' => '542', 'name' => 'Bank Jago'],
        ['code' => '501', 'name' => 'Bank Digital BCA (Blu)'],
        ['code' => '567', 'name' => 'SeaBank'],
        ['code' => '947', 'name' => 'Allo Bank'],
        ['code' => '950', 'name' => 'Bank Neo Commerce'],

        // --- BANK SYARIAH (DENGAN KODE UNIK) ---
        ['code' => '451', 'name' => 'Bank Syariah Indonesia (BSI)'],
        ['code' => '147', 'name' => 'Bank Muamalat'],
        ['code' => '506', 'name' => 'Bank Mega Syariah'],
        ['code' => '517', 'name' => 'Panin Dubai Syariah Bank'],
        ['code' => '536', 'name' => 'BCA Syariah'],
        ['code' => '949', 'name' => 'Bank Aladin Syariah'],

        // --- BANK PEMBANGUNAN DAERAH (BPD) - KODE & NAMA DIPERBAIKI ---
        ['code' => '110', 'name' => 'Bank BJB'],
        ['code' => '111', 'name' => 'Bank DKI'],
        ['code' => '112', 'name' => 'Bank BPD DIY'],
        ['code' => '113', 'name' => 'Bank Jateng'],
        ['code' => '114', 'name' => 'Bank Jatim'],
        ['code' => '115', 'name' => 'Bank BPD Jambi'],
        ['code' => '116', 'name' => 'Bank Aceh Syariah'],
        ['code' => '117', 'name' => 'Bank Sumut'],
        ['code' => '118', 'name' => 'Bank Nagari'],
        ['code' => '119', 'name' => 'Bank Riau Kepri Syariah'],
        ['code' => '120', 'name' => 'Bank Sumsel Babel'],
        ['code' => '121', 'name' => 'Bank Lampung'],
        ['code' => '122', 'name' => 'Bank Kalsel'],
        ['code' => '123', 'name' => 'Bank Kalbar'],
        ['code' => '124', 'name' => 'Bank Kaltimtara'],
        ['code' => '125', 'name' => 'Bank Kalteng'],
        ['code' => '126', 'name' => 'Bank SulutGo'],
        ['code' => '127', 'name' => 'Bank Sulteng'],
        ['code' => '128', 'name' => 'Bank Sultra'],
        ['code' => '129', 'name' => 'Bank BPD Bali'],
        ['code' => '130', 'name' => 'Bank Sulselbar'],
        ['code' => '131', 'name' => 'Bank NTB Syariah'],
        ['code' => '132', 'name' => 'Bank NTT'],
        ['code' => '133', 'name' => 'Bank Maluku Malut'],
        ['code' => '134', 'name' => 'Bank Papua'],
        ['code' => '135', 'name' => 'Bank Bengkulu'],
        ['code' => '136', 'name' => 'Bank Sulawesi Tengah'],
        ['code' => '137', 'name' => 'Bank Banten'],

        // --- BANK ASING & LAINNYA ---
        ['code' => '031', 'name' => 'Citibank'],
        ['code' => '041', 'name' => 'HSBC'],
        ['code' => '046', 'name' => 'DBS Indonesia'],
        ['code' => '067', 'name' => 'Standard Chartered'],
        ['code' => '095', 'name' => 'Bank JTrust Indonesia'],
        ['code' => '164', 'name' => 'Bank ICBC Indonesia'],
        ['code' => '484', 'name' => 'Bank KEB Hana'],
        ['code' => '425', 'name' => 'Bank Resona Perdania'],
        ['code' => '427', 'name' => 'Bank Mizuho Indonesia'],
        ['code' => '494', 'name' => 'Bank Raya Indonesia'],
        ['code' => '555', 'name' => 'Bank Index Selindo'],
    ]);
    }
}
