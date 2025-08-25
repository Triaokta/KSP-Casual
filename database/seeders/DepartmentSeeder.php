<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks for this transaction
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Hapus data yang ada terlebih dahulu
        Department::truncate();
        
        Department::insert([
            ['id' => 1, 'name' => 'Accounting', 'directorate_id' => 3],
            ['id' => 2, 'name' => 'Building Management & Office Rent', 'directorate_id' => 1],
            ['id' => 3, 'name' => 'Business Development', 'directorate_id' => 2],
            ['id' => 4, 'name' => 'Corporate Secretary', 'directorate_id' => 2],
            ['id' => 5, 'name' => 'Engineering', 'directorate_id' => 1],
            ['id' => 6, 'name' => 'Engineering Planning', 'directorate_id' => 1],
            ['id' => 7, 'name' => 'Executive Cheff', 'directorate_id' => 1],
            ['id' => 8, 'name' => 'Executive Marketing & Sales Hotel', 'directorate_id' => 2],
            ['id' => 9, 'name' => 'Finance', 'directorate_id' => 3],
            ['id' => 10, 'name' => 'Food & Beverage', 'directorate_id' => 1],
            ['id' => 11, 'name' => 'Front Office', 'directorate_id' => 1],
            ['id' => 12, 'name' => 'Golf & Sport Center Manager', 'directorate_id' => 1],
            ['id' => 13, 'name' => 'Housekeeping', 'directorate_id' => 1],
            ['id' => 14, 'name' => 'Human Capital', 'directorate_id' => 3],
            ['id' => 15, 'name' => 'Industrial Estate & Housing', 'directorate_id' => 2],
            ['id' => 16, 'name' => 'Internal Audit', 'directorate_id' => 3],
            ['id' => 17, 'name' => 'IT & Management System', 'directorate_id' => 3],
            ['id' => 18, 'name' => 'Legal & Compliance', 'directorate_id' => 2],
            ['id' => 19, 'name' => 'Marketing Industrial Estate & Housing', 'directorate_id' => 2],
            ['id' => 20, 'name' => 'Procurement', 'directorate_id' => 3],
            ['id' => 21, 'name' => 'Project Control', 'directorate_id' => 1],
            ['id' => 22, 'name' => 'Real Estate', 'directorate_id' => 2],
            ['id' => 23, 'name' => 'Security Fire & SHE Manager', 'directorate_id' => 1],
        ]);
        
        // Set auto increment to the next value
        \DB::statement('ALTER TABLE departments AUTO_INCREMENT = 24');
        
        // Enable foreign key checks
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
