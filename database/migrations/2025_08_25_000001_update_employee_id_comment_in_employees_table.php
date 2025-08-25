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
        // Ubah komentar pada kolom employee_id
        DB::statement("ALTER TABLE `employees` MODIFY COLUMN `employee_id` varchar(255) 
            CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ID Karyawan'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Kembalikan komentar kolom employee_id
        DB::statement("ALTER TABLE `employees` MODIFY COLUMN `employee_id` varchar(255) 
            CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Kode Karyawan'");
    }
};
