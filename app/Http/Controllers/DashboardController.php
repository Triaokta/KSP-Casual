<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Employee;
use App\Models\Department;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Dapatkan department_id dari query parameter atau null jika tidak ada
        $departmentId = $request->input('department_id');
        
        // Ambil semua departemen untuk dropdown
        $departments = Department::orderBy('name')->get();
        
        // Buat query dasar
        $query = Employee::query();
        
        // Filter berdasarkan departemen jika parameter ada
        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }
        
        // Hitung total karyawan
        $totalEmployees = $query->count();
        
        // Hitung karyawan aktif
        $activeEmployees = (clone $query)->where('is_active', 1)->count();
        
        // Hitung karyawan non-aktif
        $inactiveEmployees = (clone $query)->where('is_active', 0)->count();

        // Ambil nama departemen yang dipilih jika ada
        $selectedDepartmentName = null;
        if ($departmentId) {
            $selectedDepartmentName = Department::find($departmentId)->name ?? 'Semua Departemen';
        }

        return view('pages.dashboard', [
            'greeting' => 'Selamat Datang!',
            'currentDate' => Carbon::now()->translatedFormat('l, d F Y'),
            'activeEmployees' => $activeEmployees,
            'inactiveEmployees' => $inactiveEmployees,
            'totalEmployees' => $totalEmployees,
            'departments' => $departments,
            'selectedDepartmentId' => $departmentId,
            'selectedDepartmentName' => $selectedDepartmentName,
        ]);
    }
}
