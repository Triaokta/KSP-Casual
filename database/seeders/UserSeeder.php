<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        
        // Truncate users table
        User::truncate();
        
        // 1. Director
        User::create([
            'registration_id' => 'DIR001',
            'name' => 'Dewi Director',
            'email' => 'director@ksp.co.id',
            'email_verified_at' => now(),
            'password' => Hash::make('admin'),
            'department_id' => 9, // Finance
            'jabatan_id' => 1, // Director
            'division_id' => null,
            'jabatan_full' => 'Direktur Utama',
        ]);

        // 2. Assistant Director
        User::create([
            'registration_id' => 'ADIR001',
            'name' => 'Arief Assistant',
            'email' => 'assistant@ksp.co.id',
            'email_verified_at' => now(),
            'password' => Hash::make('admin'),
            'department_id' => 14, // Human Capital
            'jabatan_id' => 2, // Assistant Director
            'division_id' => null,
            'jabatan_full' => 'Assistant Director',
        ]);

        // 3. IT Manager
        User::create([
            'registration_id' => 'IT001',
            'name' => 'Dedi IT',
            'email' => 'it@ksp.co.id',
            'email_verified_at' => now(),
            'password' => Hash::make('admin'),
            'department_id' => 17, // IT & Management System
            'jabatan_id' => 5, // Manager
            'division_id' => null,
            'jabatan_full' => 'IT Manager',
        ]);

        // 4. Admin
        User::create([
            'registration_id' => 'ADM001',
            'name' => 'Admin System',
            'email' => 'admin@ksp.co.id',
            'email_verified_at' => now(),
            'password' => Hash::make('admin'),
            'department_id' => 17, // IT & Management System
            'jabatan_id' => 6, // Staff
            'division_id' => null,
            'jabatan_full' => 'System Administrator',
        ]);

        // 5. HRD Manager
        User::create([
            'registration_id' => 'HRD001',
            'name' => 'HRD Manager',
            'email' => 'hrd@ksp.co.id',
            'email_verified_at' => now(),
            'password' => Hash::make('admin'),
            'department_id' => 14, // Human Capital
            'jabatan_id' => 5, // Manager
            'division_id' => null,
            'jabatan_full' => 'HRD Manager',
        ]);

        // Set auto increment
        \DB::statement('ALTER TABLE users AUTO_INCREMENT = 10');
        
        Schema::enableForeignKeyConstraints();
    }
}
