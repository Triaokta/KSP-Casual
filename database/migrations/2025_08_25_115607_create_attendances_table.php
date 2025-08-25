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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->date('date')->comment('Tanggal Absensi');
            $table->time('time_in')->nullable()->comment('Jam Masuk');
            $table->time('time_out')->nullable()->comment('Jam Keluar');
            $table->enum('status', ['hadir', 'izin', 'sakit', 'cuti', 'tanpa_keterangan'])->default('hadir')->comment('Status Kehadiran');
            $table->string('notes')->nullable()->comment('Catatan');
            $table->string('photo_in')->nullable()->comment('Foto saat masuk');
            $table->string('photo_out')->nullable()->comment('Foto saat keluar');
            $table->decimal('latitude_in', 10, 7)->nullable()->comment('Koordinat Masuk - Latitude');
            $table->decimal('longitude_in', 10, 7)->nullable()->comment('Koordinat Masuk - Longitude');
            $table->decimal('latitude_out', 10, 7)->nullable()->comment('Koordinat Keluar - Latitude');
            $table->decimal('longitude_out', 10, 7)->nullable()->comment('Koordinat Keluar - Longitude');
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
        Schema::dropIfExists('attendances');
    }
};
