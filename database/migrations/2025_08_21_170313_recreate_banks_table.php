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
        // Hapus foreign key terlebih dahulu untuk menghindari error cascade
        Schema::disableForeignKeyConstraints();
        
        // Hapus tabel banks jika ada
        Schema::dropIfExists('banks');

        // Buat tabel banks baru
        Schema::create('banks', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('name');
            $table->timestamps();
        });
        
        // Jalankan seeder banks
        $seeder = new \Database\Seeders\BankSeeder();
        $seeder->run();
        
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('banks');
    }
};
