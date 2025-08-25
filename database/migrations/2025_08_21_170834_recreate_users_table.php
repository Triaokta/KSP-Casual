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
        // Hapus tabel users jika sudah ada
        Schema::dropIfExists('users');

        // Buat tabel users dengan struktur yang diinginkan
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // bigint UNSIGNED AUTO_INCREMENT PRIMARY KEY
            $table->string('registration_id')->index(); // varchar(255) dengan index
            $table->string('name'); // varchar(255)
            $table->string('email')->unique()->index(); // varchar(255) dengan index dan unique
            $table->timestamp('email_verified_at')->nullable(); // timestamp NULL
            $table->string('password'); // varchar(255)
            $table->rememberToken(); // varchar(100) untuk remember token
            $table->timestamps(); // created_at dan updated_at
            
            // Tambahkan kolom lain yang mungkin diperlukan untuk aplikasi Laravel
            $table->string('signature_path')->nullable(); // untuk tanda tangan digital
            $table->string('paraf_path')->nullable(); // untuk paraf digital
            $table->foreignId('department_id')->nullable()->constrained('departments')->onDelete('set null');
            $table->foreignId('jabatan_id')->nullable()->constrained('jabatans')->onDelete('set null');
            $table->foreignId('division_id')->nullable()->constrained('divisions')->onDelete('set null');
            $table->string('jabatan_full')->nullable(); // jabatan lengkap
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
