<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Exports\EmployeesExport;
use App\Imports\ImportKaryawan;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Imports\HeadingRowImport;


class EmployeeController extends Controller
{
    /**
     * Menampilkan daftar semua karyawan dengan fitur pencarian.
     */
    public function index(Request $request)
    {
        $query = Employee::query()->with('department');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // ⬇️ Tambahkan ini untuk mengurutkan berdasarkan 'employee_id' (nomor induk)
        $query->orderBy('employee_id', 'asc');

        $employees = $query->paginate(10);

        return view('employees.index', compact('employees'));
    }

    /**
     * Menampilkan form untuk membuat karyawan baru.
     */
    public function create()
    {
        $departments = Department::orderBy('name', 'asc')->get();
        return view('employees.create', compact('departments'));
    }

    /**
     * Menyimpan karyawan baru ke database.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'employee_id' => 'required|string|unique:employees,employee_id',
            'name' => 'required|string|max:255',
            'nik_ktp' => 'required|string|size:16|unique:employees,nik_ktp',
            'address' => 'nullable|string',
            'npwp' => 'nullable|string|size:16|unique:employees,npwp',
            'no_kk' => 'nullable|string|size:16',
            'department_id' => 'required|exists:departments,id',
            'is_active' => 'required|boolean',
        ]);

        Employee::create($validatedData);

        return redirect()->route('employees.index')
                         ->with('success', 'Karyawan baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail spesifik seorang karyawan.
     */
    public function show(Employee $employee)
    {
        return view('employees.show', compact('employee'));
    }

    /**
     * Menampilkan form untuk mengedit data karyawan.
     */
    public function edit(Employee $employee)
    {
        $departments = Department::orderBy('name', 'asc')->get();
        return view('employees.edit', compact('employee', 'departments'));
    }

    /**
     * Mengupdate data karyawan di database.
     */
    public function update(Request $request, Employee $employee)
    {
        $validatedData = $request->validate([
            'employee_id' => 'required|string|unique:employees,employee_id,' . $employee->id,
            'name' => 'required|string|max:255',
            'nik_ktp' => 'required|string|size:16|unique:employees,nik_ktp,' . $employee->id,
            'address' => 'nullable|string',
            'npwp' => 'nullable|string|size:16|unique:employees,npwp,' . $employee->id,
            'no_kk' => 'nullable|string|size:16',
            'department_id' => 'required|exists:departments,id',
            'is_active' => 'required|boolean',
        ]);

        $employee->update($validatedData);

        return redirect()->route('employees.index')
                         ->with('success', 'Data karyawan berhasil diubah.');
    }

    /**
     * Menghapus data karyawan dari database.
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')
                         ->with('success', 'Data karyawan berhasil dihapus.');
    }

    /**
     * Mengubah status aktif/nonaktif karyawan.
     */
    public function toggleStatus(Employee $employee)
    {
        $employee->is_active = !$employee->is_active;
        $employee->save();
        return redirect()->back()->with('success', 'Status karyawan berhasil diubah.');
    }

    /**
     * (BARU) Menampilkan halaman form untuk import.
     */
    public function importForm()
    {
        return view('employees.import');
    }

    /**
     * (BARU) Menangani proses import data dari file Excel.
     */
    
    public function importStore(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            Excel::import(new \App\Imports\ImportKaryawan, $request->file('file'));
            return redirect()->route('employees.index')->with('success', 'Data berhasil diimpor!');
        } catch (\Throwable $e) {
            \Log::error('❌ Gagal import: ' . $e->getMessage());
            return back()->withErrors(['file' => 'Import gagal: ' . $e->getMessage()]);
        }
    }



    /**
     * (BARU) Mengunduh file template Excel.
     */
    public function template()
    {
        $filePath = public_path('templates/template_karyawan.xlsx');
        
        if (!file_exists($filePath)) {
            return redirect()->route('employees.import.form')->withErrors(['file' => 'File template tidak ditemukan.']);
        }

        return response()->download($filePath);
    }

    /**
     * (BARU) Menangani proses ekspor data ke file Excel.
     */
    public function export()
    {
        return Excel::download(new EmployeesExport, 'daftar_karyawan.xlsx');
    }

    public function downloadTemplate()
    {
        $filePath = public_path('templates/template_karyawan.xlsx');

        if (!file_exists($filePath)) {
            return back()->withErrors(['file' => 'File template tidak ditemukan.']);
        }

        return response()->download($filePath);
    }

}
