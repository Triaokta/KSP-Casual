<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Exports\AttendanceExport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

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
        
        if ($request->has('date')) {
            $query->whereDate('date', $request->date);
        } else {
            $query->whereDate('date', Carbon::today());
        }
        
        if ($request->has('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

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
    public function create(Request $request)
    {
        $date = $request->input('date', Carbon::today()->format('Y-m-d'));
        $today = $date;
        
        $attendedEmployeeIds = Attendance::where('date', $date)
            ->pluck('employee_id')
            ->toArray();
            
        $employees = Employee::where('is_active', 1)
            ->whereNotIn('id', $attendedEmployeeIds)
            ->orderBy('name')
            ->get();
        
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
        $rules = [
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'status' => 'required|in:hadir,izin,sakit,cuti,tanpa_keterangan',
            'notes' => 'nullable|string|max:255',
        ];
        
        if ($request->status === 'hadir') {
            $rules['time_in'] = 'required|date_format:H:i';
            $rules['time_out'] = 'nullable|date_format:H:i|after:time_in';
        } else {
            $rules['time_in'] = 'nullable|date_format:H:i';
            $rules['time_out'] = 'nullable|date_format:H:i|after:time_in';
        }
        
        $validated = $request->validate($rules);
        
        $existingAttendance = Attendance::where('employee_id', $validated['employee_id'])
            ->where('date', $validated['date'])
            ->first();
            
        if ($existingAttendance) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Karyawan ini sudah memiliki data absensi pada tanggal tersebut.');
        }
        
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
        
        $rules = [
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'status' => 'required|in:hadir,izin,sakit,cuti,tanpa_keterangan',
            'notes' => 'nullable|string|max:255',
        ];
        
        if ($request->status === 'hadir') {
            $rules['time_in'] = 'required|date_format:H:i';
            $rules['time_out'] = 'nullable|date_format:H:i|after:time_in';
        } else {
            $rules['time_in'] = 'nullable|date_format:H:i';
            $rules['time_out'] = 'nullable|date_format:H:i|after:time_in';
        }
        
        $validated = $request->validate($rules);
        
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
            
            $startFormatted = date('d-m-Y', strtotime($startDate));
            $endFormatted = date('d-m-Y', strtotime($endDate));
            
            if ($employeeId) {
                $employee = Employee::findOrFail($employeeId);
                $fileName = 'Absensi_' . $employee->name . '_' . $startFormatted . '_sampai_' . $endFormatted . '.xlsx';
            } else {
                $fileName = 'Absensi_' . $startFormatted . '_sampai_' . $endFormatted . '.xlsx';
            }
            
            return Excel::download(new AttendanceExport($startDate, $endDate, $employeeId), $fileName);
        } catch (\Exception $e) {
            \Log::error('Excel Export Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengekspor data. Silakan coba lagi.');
        }
    }

    /**
     * Export laporan absensi ke PDF.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function exportPDF(Request $request)
    {
        try {
            $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
            $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
            $employeeId = $request->input('employee_id');
            
            $query = Attendance::query()->with('employee');
            
            if ($request->filled('employee_id')) {
                $query->where('employee_id', $employeeId);
            }
            
            $query->whereBetween('date', [$startDate, $endDate]);
            
            $attendances = $query->orderBy('date', 'desc')->get();
            
            $summary = [
                'total' => $attendances->count(),
                'hadir' => $attendances->where('status', 'hadir')->count(),
                'izin' => $attendances->where('status', 'izin')->count(),
                'sakit' => $attendances->where('status', 'sakit')->count(),
                'cuti' => $attendances->where('status', 'cuti')->count(),
                'tanpa_keterangan' => $attendances->where('status', 'tanpa_keterangan')->count(),
            ];
            
            $startFormatted = date('d-m-Y', strtotime($startDate));
            $endFormatted = date('d-m-Y', strtotime($endDate));
            
            $title = 'Laporan Absensi';
            $periodText = "Periode: $startFormatted s/d $endFormatted";
            
            if ($employeeId) {
                $employee = Employee::findOrFail($employeeId);
                $employeeText = "Karyawan: " . $employee->name;
                $fileName = 'Absensi_' . $employee->name . '_' . $startFormatted . '_sampai_' . $endFormatted . '.pdf';
            } else {
                $employeeText = "Karyawan: Semua Karyawan";
                $fileName = 'Absensi_' . $startFormatted . '_sampai_' . $endFormatted . '.pdf';
            }
            
            $pdf = PDF::loadView('attendance.pdf', compact('attendances', 'summary', 'title', 'periodText', 'employeeText'));
            
            $pdf->setPaper('a4', 'landscape');
            
            return $pdf->download($fileName);
        } catch (\Exception $e) {
            \Log::error('PDF Export Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengekspor PDF. Silakan coba lagi.');
        }
    }
}
