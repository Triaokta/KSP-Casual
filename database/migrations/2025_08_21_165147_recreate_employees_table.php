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
        // Menghapus foreign key constraints jika ada
        Schema::disableForeignKeyConstraints();

        // Hapus tabel employees jika ada
        Schema::dropIfExists('employees');

        // Buat kembali tabel employees sesuai struktur SQL
        DB::statement("
            CREATE TABLE `employees` (
              `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
              `employee_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ID Karyawan',
              `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nama',
              `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Alamat',
              `nik_ktp` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'No KTP',
              `npwp` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'NPWP',
              `no_rek` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nonor Rekening',
              `department_id` bigint UNSIGNED DEFAULT NULL,
              `is_active` tinyint(1) NOT NULL DEFAULT '1',
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL,
              `bank_id` bigint UNSIGNED DEFAULT NULL,
              `nama_bank` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
              PRIMARY KEY (`id`),
              UNIQUE KEY `employees_employee_id_unique` (`employee_id`),
              UNIQUE KEY `employees_nik_ktp_unique` (`nik_ktp`),
              UNIQUE KEY `employees_npwp_unique` (`npwp`),
              KEY `employees_department_id_foreign` (`department_id`),
              KEY `employees_bank_id_foreign` (`bank_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        // Tambahkan foreign key constraints
        DB::statement("
            ALTER TABLE `employees`
            ADD CONSTRAINT `employees_bank_id_foreign` FOREIGN KEY (`bank_id`) REFERENCES `banks` (`id`) ON DELETE SET NULL,
            ADD CONSTRAINT `employees_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
        ");

        // Aktifkan kembali foreign key constraints
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
};
