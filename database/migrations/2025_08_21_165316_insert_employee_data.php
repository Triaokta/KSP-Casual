<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Masukkan data karyawan dari SQL dump
        DB::table('employees')->insert([
            [
                'id' => 5,
                'employee_id' => '0002',
                'name' => 'Dewi Director',
                'address' => 'serang',
                'nik_ktp' => '3601234567891012',
                'npwp' => '1234567891011122',
                'no_rek' => '3601234567891013',
                'department_id' => 9,
                'is_active' => 1,
                'created_at' => '2025-07-09 03:26:41',
                'updated_at' => '2025-07-09 03:26:41',
                'bank_id' => null,
                'nama_bank' => null
            ],
            [
                'id' => 6,
                'employee_id' => '0001',
                'name' => 'Arief Assistant',
                'address' => 'cilegon',
                'nik_ktp' => '3601234567891011',
                'npwp' => '1234567891011121',
                'no_rek' => '3601234567891012',
                'department_id' => 21,
                'is_active' => 1,
                'created_at' => '2025-07-09 04:08:49',
                'updated_at' => '2025-07-09 10:21:05',
                'bank_id' => null,
                'nama_bank' => null
            ],
            [
                'id' => 52,
                'employee_id' => '0003',
                'name' => 'Dedi Karyawan',
                'address' => 'serang',
                'nik_ktp' => '3600987654321001',
                'npwp' => '1234567891011123',
                'no_rek' => '3600987654321002',
                'department_id' => 15,
                'is_active' => 1,
                'created_at' => '2025-07-25 02:36:13',
                'updated_at' => '2025-07-25 02:37:01',
                'bank_id' => null,
                'nama_bank' => null
            ],
            [
                'id' => 53,
                'employee_id' => '0004',
                'name' => 'Budi Karyawan',
                'address' => 'bandung',
                'nik_ktp' => '3600987689764283',
                'npwp' => null,
                'no_rek' => '1234',
                'department_id' => 5,
                'is_active' => 0,
                'created_at' => '2025-07-25 02:36:13',
                'updated_at' => '2025-08-21 04:37:38',
                'bank_id' => null,
                'nama_bank' => null
            ]
        ]);

        // Reset AUTO_INCREMENT ke nilai yang lebih tinggi dari ID maksimum
        DB::statement('ALTER TABLE employees AUTO_INCREMENT = 54');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Hapus semua data yang telah dimasukkan
        DB::table('employees')->whereIn('id', [5, 6, 52, 53])->delete();
    }
};
