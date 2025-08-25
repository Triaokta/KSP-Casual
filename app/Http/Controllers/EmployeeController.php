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
    /**
     * Menampilkan daftar semua karyawan dengan fitur pencarian.
     */
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


    /**
     * Menampilkan form untuk membuat karyawan baru.
     */
    public function create()
    {
        $departments = Department::orderBy('name', 'asc')->get();
        $banks = Bank::orderBy('name')->get();
        return view('employees.create', compact('departments', 'banks'));
    }

    /**
     * Menyimpan karyawan baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi dasar
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

        // Cek apakah data sudah ada berdasarkan beberapa kriteria
        $existingRecord = Employee::where('employee_id', $request->employee_id)
            ->orWhere('nik_ktp', $request->nik_ktp)
            ->first();
        
        // Jika NPWP tidak null, tambahkan ke kondisi cek
        if ($request->filled('npwp')) {
            $existingRecordByNpwp = Employee::where('npwp', $request->npwp)->first();
            if ($existingRecordByNpwp) {
                $existingRecord = $existingRecordByNpwp;
            }
        }
        
        // Jika data sudah ada
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
        
        // Jika data belum ada, simpan data baru
        $employee = Employee::create($request->all());
        
        // Catat status awal karyawan
        $employee->statusHistories()->create([
            'is_active' => $employee->is_active,
            'changed_by' => auth()->user()->name ?? 'System',
            'notes' => 'Status awal karyawan',
        ]);

        return redirect()->route('employees.index')
                         ->with('success', 'Karyawan baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail spesifik seorang karyawan.
     */
    public function show(Employee $employee)
    {
        // Ambil riwayat status karyawan
        $statusHistories = $employee->statusHistories()->orderBy('created_at', 'desc')->get();
        
        // Hitung berapa kali status aktif dan nonaktif
        $activeCount = $statusHistories->where('is_active', 1)->count();
        $inactiveCount = $statusHistories->where('is_active', 0)->count();
        
        return view('employees.show', compact('employee', 'statusHistories', 'activeCount', 'inactiveCount'));
    }

    /**
     * Menampilkan form untuk mengedit data karyawan.
     */
    public function edit(Employee $employee)
    {
        $departments = Department::orderBy('name', 'asc')->get();
        $banks = Bank::orderBy('name')->get();
        return view('employees.edit', compact('employee', 'departments', 'banks'));
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
            'no_rek' => 'nullable|string',
            'bank_id' => 'nullable|exists:banks,id',
            'nama_bank' => 'nullable|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'is_active' => 'required|boolean',
        ]);
        
        // Simpan status lama sebelum diubah
        $oldStatus = $employee->is_active;
        $newStatus = $validatedData['is_active'];
        
        $employee->update($validatedData);
        
        // Jika status berubah, catat dalam riwayat
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
        // Simpan status lama sebelum diubah
        $oldStatus = $employee->is_active;
        
        // Ubah status menjadi kebalikannya
        $employee->is_active = !$oldStatus;
        $employee->save();
        
        // Simpan riwayat perubahan status
        $employee->statusHistories()->create([
            'is_active' => $employee->is_active,
            'changed_by' => auth()->user()->name ?? 'System',
            'notes' => $employee->is_active ? 'Status diubah menjadi aktif' : 'Status diubah menjadi nonaktif',
        ]);
        
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
            $import = new \App\Imports\ImportKaryawan;
            Excel::import($import, $request->file('file'));
            
            // Dapatkan data duplikat dan jumlah baris
            $duplicates = $import->getDuplicates();
            $rowCount = $import->getRowCount();
            
            \Log::info('Import completed. Rows processed: ' . $rowCount);
            \Log::info('Duplicates found: ' . count($duplicates));
            
            // Jika tidak ada data yang diproses
            if ($rowCount == 0) {
                return back()->with('error', 'Tidak ada data valid untuk diimpor. Pastikan format data sesuai dengan template.');
            }
            
            // Jika ada data duplikat
            if (!empty($duplicates)) {
                // Buat pesan untuk data duplikat
                $duplicateMsg = 'Beberapa data tidak diimpor karena sudah ada:';
                $duplicateMsg .= '<ul>';
                
                // Batas maksimal data yang ditampilkan
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
                
                // Jika semua data adalah duplikat
                if (count($duplicates) >= $rowCount) {
                    return back()->with('error', 'Semua data sudah pernah diinput! ' . $duplicateMsg);
                }
                
                // Jika sebagian data berhasil diimpor
                return redirect()->route('employees.index')
                    ->with('warning', 'Data berhasil diimpor, tetapi beberapa data dilewati karena sudah ada. ' . $duplicateMsg);
            }
            
            // Jika semua data berhasil diimpor
            return redirect()->route('employees.index')->with('success', 'Data berhasil diimpor!');
        } catch (\Throwable $e) {
            $errorMessage = $e->getMessage();
            $errorTrace = $e->getTraceAsString();
            
            \Log::error('❌ Gagal import: ' . $errorMessage);
            \Log::error('Stack trace: ' . $errorTrace);
            
            // Cek jika error terkait dengan penghitungan
            if (strpos($errorMessage, 'count()') !== false) {
                return back()->withErrors(['file' => 'Error saat memproses data duplikat. Silakan coba lagi.']);
            }
            
            return back()->withErrors(['file' => 'Import gagal: ' . $errorMessage]);
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
        // Format tanggal saat ini untuk nama file (dd-mm-yyyy)
        $currentDate = now()->format('d-m-Y');
        return Excel::download(new EmployeesExport, 'daftar_karyawan_aktif_' . $currentDate . '.xlsx');
    }

    /**
     * (BARU) Menangani proses ekspor data ke PDF.
     */
    public function exportPDF()
    {
        // Hanya mengambil karyawan dengan status aktif
        $employees = Employee::with(['department', 'bank'])
            ->where('is_active', 1)
            ->orderBy('employee_id')
            ->get();
        
        // Format tanggal saat ini untuk nama file (dd-mm-yyyy)
        $currentDate = now()->format('d-m-Y');
            
        $pdf = PDF::loadView('employees.pdf', compact('employees'));
        return $pdf->download('daftar_karyawan_aktif_' . $currentDate . '.pdf');
    }

    public function downloadTemplate()
    {
        $filePath = public_path('templates/template_karyawan.xlsx');

        // Jika file tidak ada, maka buat template baru menggunakan Excel
        if (!file_exists($filePath)) {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            // Set header kolom
            $headers = [
                'id_karyawan', 'nama_lengkap', 'no_ktp', 'alamat', 'npwp', 
                'no_rek', 'nama_bank', 'departemen', 'status_aktif'
            ];
            
            // Tulis header ke Excel
            foreach ($headers as $key => $header) {
                $sheet->setCellValueByColumnAndRow($key + 1, 1, $header);
            }
            
            // Style untuk header
            $headerStyle = $sheet->getStyle('A1:I1');
            $headerStyle->getFont()->setBold(true);
            $headerStyle->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FFCCCCCC');
                
            // Auto-size kolom
            foreach (range('A', 'I') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }
            
            // Tambahkan contoh data
            $sheet->setCellValue('A2', 'K001');
            $sheet->setCellValue('B2', 'Nama Karyawan');
            $sheet->setCellValue('C2', '1234567890123456');
            $sheet->setCellValue('D2', 'Alamat Karyawan');
            $sheet->setCellValue('E2', '1234567890123456');
            $sheet->setCellValue('F2', '12345678901234');
            $sheet->setCellValue('G2', 'BCA');
            $sheet->setCellValue('H2', 'Accounting');
            $sheet->setCellValue('I2', 'Aktif');
            
            // Buat folder jika belum ada
            if (!file_exists(public_path('templates'))) {
                mkdir(public_path('templates'), 0777, true);
            }
            
            // Simpan file
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save($filePath);
        }

        return response()->download($filePath);
    }

}
