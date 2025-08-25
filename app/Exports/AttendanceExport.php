<?php

namespace App\Exports;

use App\Models\Attendance;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AttendanceExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $startDate;
    protected $endDate;
    protected $employeeId;

    public function __construct($startDate, $endDate, $employeeId = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->employeeId = $employeeId;
    }
    
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = Attendance::with('employee')
            ->whereBetween('date', [$this->startDate, $this->endDate]);
            
        if ($this->employeeId) {
            $query->where('employee_id', $this->employeeId);
        }
        
        return $query->orderBy('date', 'desc')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'ID Karyawan',
            'Nama Karyawan',
            'Jam Masuk',
            'Jam Keluar',
            'Status',
            'Catatan',
        ];
    }

    /**
     * @param mixed $attendance
     * @return array
     */
    public function map($attendance): array
    {
        static $rowNumber = 0;
        $rowNumber++;
        
        $status = ucfirst($attendance->status);
        
        return [
            $rowNumber,
            $attendance->date->format('d/m/Y'),
            $attendance->employee->employee_id,
            $attendance->employee->name,
            $attendance->time_in,
            $attendance->time_out ?? '-',
            $status,
            $attendance->notes ?? '-',
        ];
    }
    
    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style header row
            1 => ['font' => ['bold' => true], 'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E9ECEF']]],
        ];
    }
}
