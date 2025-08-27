<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Exports\EmployeesExport;
use App\Imports\ImportKaryawan;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Imports\HeadingRowImport;
use App\Models\Bank;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Barryvdh\DomPDF\Facade\Pdf;


class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::query()->with('department');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        $query->orderBy('employee_id', 'asc');

        $employees = $query->paginate(10);
        $departments = Department::orderBy('name')->get();

        return view('employees.index', compact('employees', 'departments'));
    }

    public function create()
    {
        $departments = Department::orderBy('name', 'asc')->get();
        $banks = Bank::orderBy('name')->get();
        return view('employees.create', compact('departments', 'banks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|string',
            'name' => 'required|string|max:255',
            'nik_ktp' => 'required|string|size:16',
            'address' => 'nullable|string',
            'npwp' => 'nullable|string|size:16',
            'no_rek' => 'nullable|string',
            'bank_id' => 'nullable|exists:banks,id',
            'nama_bank' => 'nullable|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'is_active' => 'required|boolean',
        ]);

        $existingRecord = Employee::where('employee_id', $request->employee_id)
            ->orWhere('nik_ktp', $request->nik_ktp)
            ->first();
        
        if ($request->filled('npwp')) {
            $existingRecordByNpwp = Employee::where('npwp', $request->npwp)->first();
            if ($existingRecordByNpwp) {
                $existingRecord = $existingRecordByNpwp;
            }
        }
        
        if ($existingRecord) {
            $message = 'Data ini sudah pernah diinput. ';
            
            if ($existingRecord->employee_id == $request->employee_id) {
                $message .= 'ID Karyawan "' . $request->employee_id . '" sudah digunakan. ';
            }
            
            if ($existingRecord->nik_ktp == $request->nik_ktp) {
                $message .= 'NIK "' . $request->nik_ktp . '" sudah digunakan. ';
            }
            
            if ($request->filled('npwp') && $existingRecord->npwp == $request->npwp) {
                $message .= 'NPWP "' . $request->npwp . '" sudah digunakan. ';
            }
            
            return redirect()->back()->withInput()->with('error', $message);
        }
        
        $employee = Employee::create($request->all());
        
        $employee->statusHistories()->create([
            'is_active' => $employee->is_active,
            'changed_by' => auth()->user()->name ?? 'System',
            'notes' => 'Status awal karyawan',
        ]);

        return redirect()->route('employees.index')
                         ->with('success', 'Karyawan baru berhasil ditambahkan.');
    }

    public function show(Employee $employee)
    {
        $statusHistories = $employee->statusHistories()->orderBy('created_at', 'desc')->get();
        
        $activeCount = $statusHistories->where('is_active', 1)->count();
        $inactiveCount = $statusHistories->where('is_active', 0)->count();
        
        return view('employees.show', compact('employee', 'statusHistories', 'activeCount', 'inactiveCount'));
    }

    public function edit(Employee $employee)
    {
        $departments = Department::orderBy('name', 'asc')->get();
        $banks = Bank::orderBy('name')->get();
        return view('employees.edit', compact('employee', 'departments', 'banks'));
    }

    public function update(Request $request, Employee $employee)
    {
        $validatedData = $request->validate([
            'employee_id' => 'required|string|unique:employees,employee_id,' . $employee->id,
            'name' => 'required|string|max:255',
            'nik_ktp' => 'required|string|size:16|unique:employees,nik_ktp,' . $employee->id,
            'address' => 'nullable|string',
            'npwp' => 'nullable|string|size:16|unique:employees,npwp,' . $employee->id,
            'no_rek' => 'nullable|string',
            'bank_id' => 'nullable|exists:banks,id',
            'nama_bank' => 'nullable|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'is_active' => 'required|boolean',
        ]);
        
        $oldStatus = $employee->is_active;
        $newStatus = $validatedData['is_active'];
        
        $employee->update($validatedData);
        
        if ($oldStatus != $newStatus) {
            $employee->statusHistories()->create([
                'is_active' => $newStatus,
                'changed_by' => auth()->user()->name ?? 'System',
                'notes' => $newStatus ? 'Status diubah menjadi aktif melalui form edit' : 'Status diubah menjadi nonaktif melalui form edit',
            ]);
        }

        return redirect()->route('employees.index')
                         ->with('success', 'Data karyawan berhasil diubah.');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')
                         ->with('success', 'Data karyawan berhasil dihapus.');
    }

    public function toggleStatus(Employee $employee)
    {
        $oldStatus = $employee->is_active;
        
        $employee->is_active = !$oldStatus;
        $employee->save();
    
        $employee->statusHistories()->create([
            'is_active' => $employee->is_active,
            'changed_by' => auth()->user()->name ?? 'System',
            'notes' => $employee->is_active ? 'Status diubah menjadi aktif' : 'Status diubah menjadi nonaktif',
        ]);
        
        return redirect()->back()->with('success', 'Status karyawan berhasil diubah.');
    }

    public function importForm()
    {
        return view('employees.import');
    }


    public function importStore(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            $import = new \App\Imports\ImportKaryawan;
            Excel::import($import, $request->file('file'));
            
            $duplicates = $import->getDuplicates();
            $rowCount = $import->getRowCount();
            
            \Log::info('Import completed. Rows processed: ' . $rowCount);
            \Log::info('Duplicates found: ' . count($duplicates));
            
            if ($rowCount == 0) {
                return back()->with('error', 'Tidak ada data valid untuk diimpor. Pastikan format data sesuai dengan template.');
            }
            
            if (!empty($duplicates)) {
                $duplicateMsg = 'Beberapa data tidak diimpor karena sudah ada:';
                $duplicateMsg .= '<ul>';
                
                $maxShow = 5;
                $count = 0;
                
                foreach ($duplicates as $duplicate) {
                    if ($count < $maxShow) {
                        $duplicateMsg .= '<li>ID: ' . $duplicate['row']['id_karyawan'] . ' - ' . $duplicate['row']['nama_lengkap'] . ' (' . $duplicate['reason'] . ')</li>';
                        $count++;
                    } else {
                        $remaining = count($duplicates) - $maxShow;
                        $duplicateMsg .= '<li>Dan ' . $remaining . ' data lainnya...</li>';
                        break;
                    }
                }
                
                $duplicateMsg .= '</ul>';
                
                if (count($duplicates) >= $rowCount) {
                    return back()->with('error', 'Semua data sudah pernah diinput! ' . $duplicateMsg);
                }
                
                return redirect()->route('employees.index')
                    ->with('warning', 'Data berhasil diimpor, tetapi beberapa data dilewati karena sudah ada. ' . $duplicateMsg);
            }
            
            return redirect()->route('employees.index')->with('success', 'Data berhasil diimpor!');
        } catch (\Throwable $e) {
            $errorMessage = $e->getMessage();
            $errorTrace = $e->getTraceAsString();
            
            \Log::error('❌ Gagal import: ' . $errorMessage);
            \Log::error('Stack trace: ' . $errorTrace);
            
            if (strpos($errorMessage, 'count()') !== false) {
                return back()->withErrors(['file' => 'Error saat memproses data duplikat. Silakan coba lagi.']);
            }
            
            return back()->withErrors(['file' => 'Import gagal: ' . $errorMessage]);
        }
    }

    public function template()
    {
        $filePath = public_path('templates/template_karyawan.xlsx');
        
        if (!file_exists($filePath)) {
            return redirect()->route('employees.import.form')->withErrors(['file' => 'File template tidak ditemukan.']);
        }

        return response()->download($filePath);
    }

    public function export()
    {
        // Format tanggal saat ini untuk nama file (dd-mm-yyyy)
        $currentDate = now()->format('d-m-Y');
        return Excel::download(new EmployeesExport, 'daftar_karyawan_aktif_' . $currentDate . '.xlsx');
    }

    public function exportPDF()
    {
        $employees = Employee::with(['department', 'bank'])
            ->where('is_active', 1)
            ->orderBy('employee_id')
            ->get();
        
        $currentDate = now()->format('d-m-Y');
            
        $pdf = PDF::loadView('employees.pdf', compact('employees'));
        return $pdf->download('daftar_karyawan_aktif_' . $currentDate . '.pdf');
    }

    public function downloadTemplate()
    {
        $filePath = public_path('templates/template_karyawan.xlsx');

        if (!file_exists($filePath)) {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            $headers = [
                'id_karyawan', 'nama_lengkap', 'no_ktp', 'alamat', 'npwp', 
                'no_rek', 'nama_bank', 'departemen', 'status_aktif'
            ];
            
            foreach ($headers as $key => $header) {
                $sheet->setCellValueByColumnAndRow($key + 1, 1, $header);
            }
            
            $headerStyle = $sheet->getStyle('A1:I1');
            $headerStyle->getFont()->setBold(true);
            $headerStyle->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FFCCCCCC');
                
            foreach (range('A', 'I') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }
            
            $sheet->setCellValue('A2', 'K001');
            $sheet->setCellValue('B2', 'Nama Karyawan');
            $sheet->setCellValue('C2', '1234567890123456');
            $sheet->setCellValue('D2', 'Alamat Karyawan');
            $sheet->setCellValue('E2', '1234567890123456');
            $sheet->setCellValue('F2', '12345678901234');
            $sheet->setCellValue('G2', 'BCA');
            $sheet->setCellValue('H2', 'Accounting');
            $sheet->setCellValue('I2', 'Aktif');
            
            if (!file_exists(public_path('templates'))) {
                mkdir(public_path('templates'), 0777, true);
            }

            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save($filePath);
        }

        return response()->download($filePath);
    }

}
