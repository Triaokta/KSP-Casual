<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Exports\AttendanceExport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Attendance::with('employee');
        
        // Filter berdasarkan tanggal
        if ($request->has('date')) {
            $query->whereDate('date', $request->date);
        } else {
            // Default menampilkan absensi hari ini
            $query->whereDate('date', Carbon::today());
        }
        
        // Filter berdasarkan karyawan
        if ($request->has('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }
        
        // Filter berdasarkan status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        $attendances = $query->latest()->paginate(15);
        $employees = Employee::where('is_active', 1)->orderBy('name')->get();
        
        return view('attendance.index', compact('attendances', 'employees'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $employees = Employee::where('is_active', 1)->orderBy('name')->get();
        $today = Carbon::today()->format('Y-m-d');
        
        return view('attendance.create', compact('employees', 'today'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'time_in' => 'required|date_format:H:i',
            'time_out' => 'nullable|date_format:H:i|after:time_in',
            'status' => 'required|in:hadir,izin,sakit,cuti,tanpa_keterangan',
            'notes' => 'nullable|string|max:255',
        ]);
        
        // Cek apakah data absensi sudah ada untuk karyawan dan tanggal yang sama
        $existingAttendance = Attendance::where('employee_id', $validated['employee_id'])
            ->where('date', $validated['date'])
            ->first();
            
        if ($existingAttendance) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Karyawan ini sudah memiliki data absensi pada tanggal tersebut.');
        }
        
        // Simpan data absensi
        Attendance::create($validated);
        
        return redirect()->route('attendance.index')
            ->with('success', 'Data absensi berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $attendance = Attendance::with('employee')->findOrFail($id);
        return view('attendance.show', compact('attendance'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $attendance = Attendance::findOrFail($id);
        $employees = Employee::where('is_active', 1)->orderBy('name')->get();
        
        return view('attendance.edit', compact('attendance', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $attendance = Attendance::findOrFail($id);
        
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'time_in' => 'required|date_format:H:i',
            'time_out' => 'nullable|date_format:H:i|after:time_in',
            'status' => 'required|in:hadir,izin,sakit,cuti,tanpa_keterangan',
            'notes' => 'nullable|string|max:255',
        ]);
        
        // Cek jika ada perubahan karyawan atau tanggal, pastikan tidak duplikat
        if ($validated['employee_id'] != $attendance->employee_id || $validated['date'] != $attendance->date) {
            $existingAttendance = Attendance::where('employee_id', $validated['employee_id'])
                ->where('date', $validated['date'])
                ->where('id', '!=', $id)
                ->first();
                
            if ($existingAttendance) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Karyawan ini sudah memiliki data absensi pada tanggal tersebut.');
            }
        }
        
        $attendance->update($validated);
        
        return redirect()->route('attendance.index')
            ->with('success', 'Data absensi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->delete();
        
        return redirect()->route('attendance.index')
            ->with('success', 'Data absensi berhasil dihapus.');
    }
    
    /**
     * Menampilkan laporan absensi.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function report(Request $request)
    {
        $employees = Employee::where('is_active', 1)->orderBy('name')->get();
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        $employeeId = $request->input('employee_id');
        
        $query = Attendance::query()->with('employee');
        
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $employeeId);
        }
        
        $query->whereBetween('date', [$startDate, $endDate]);
        
        $attendances = $query->orderBy('date', 'desc')->get();
        
        // Hitung statistik
        $summary = [
            'total' => $attendances->count(),
            'hadir' => $attendances->where('status', 'hadir')->count(),
            'izin' => $attendances->where('status', 'izin')->count(),
            'sakit' => $attendances->where('status', 'sakit')->count(),
            'cuti' => $attendances->where('status', 'cuti')->count(),
            'tanpa_keterangan' => $attendances->where('status', 'tanpa_keterangan')->count(),
        ];
        
        return view('attendance.report', compact('attendances', 'employees', 'startDate', 'endDate', 'employeeId', 'summary'));
    }
    
    /**
     * Export laporan absensi ke Excel.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\RedirectResponse
     */
    public function exportExcel(Request $request)
    {
        try {
            $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
            $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
            $employeeId = $request->input('employee_id');
            
            // Format tanggal untuk nama file (dd-mm-yyyy)
            $startFormatted = date('d-m-Y', strtotime($startDate));
            $endFormatted = date('d-m-Y', strtotime($endDate));
            
            if ($employeeId) {
                // Jika ada filter karyawan, tambahkan nama karyawan
                $employee = Employee::findOrFail($employeeId);
                $fileName = 'Absensi_' . $employee->name . '_' . $startFormatted . '_sampai_' . $endFormatted . '.xlsx';
            } else {
                // Jika semua karyawan
                $fileName = 'Absensi_' . $startFormatted . '_sampai_' . $endFormatted . '.xlsx';
            }
            
            return Excel::download(new AttendanceExport($startDate, $endDate, $employeeId), $fileName);
        } catch (\Exception $e) {
            // Jika ada error, log dan beri tahu user
            \Log::error('Excel Export Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengekspor data. Silakan coba lagi.');
        }
    }
}
