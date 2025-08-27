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
        $departmentId = $request->input('department_id');
        
        $departments = Department::orderBy('name')->get();
        
        $query = Employee::query();
        
        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }
        
        $totalEmployees = $query->count();
      
        $activeEmployees = (clone $query)->where('is_active', 1)->count();
        
        $inactiveEmployees = (clone $query)->where('is_active', 0)->count();

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
