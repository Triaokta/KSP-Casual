<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id(); // Ini akan menjadi kolom 'nomor' (Auto-increment)
            $table->string('employee_id')->unique()->comment('Kode Karyawan');
            $table->string('name')->comment('Nama');
            $table->text('address')->nullable()->comment('Alamat');
            $table->string('nik_ktp', 16)->unique()->comment('No KTP');
            $table->string('npwp', 16)->unique()->nullable()->comment('NPWP');
            
            // Kolom untuk menghubungkan ke departemen/divisi
            $table->foreignId('department_id')->nullable()->constrained('departments')->onUpdate('cascade')->onDelete('set null');
            
            $table->timestamps();
        });
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
