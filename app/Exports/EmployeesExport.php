<?php

namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class EmployeesExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    ShouldAutoSize,
    WithColumnFormatting,
    WithEvents
{
    public function collection()
    {
        return Employee::with('department')->get();
    }

    public function headings(): array
    {
        return [
            'NOMOR KARYAWAN',
            'NAMA LENGKAP',
            'NO. KTP',
            'ALAMAT',
            'NPWP',
            'NO. KK',
            'DEPARTEMEN',
            'STATUS',
        ];
    }

    public function map($employee): array
    {
        return [
            $employee->employee_id,
            $employee->name,
            '="' . $employee->nik_ktp . '"',
            $employee->address,
            '="' . $employee->npwp . '"',
            '="' . $employee->no_kk . '"',
            optional($employee->department)->name,
            $employee->is_active ? 'Aktif' : 'Nonaktif',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_TEXT, // NO KTP
            'E' => NumberFormat::FORMAT_TEXT, // NPWP
            'F' => NumberFormat::FORMAT_TEXT, // NO KK
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Format kolom sebagai teks: dari baris 2 sampai 1000
                foreach (['C', 'E', 'F'] as $column) {
                    $event->sheet->getDelegate()->getStyle("{$column}2:{$column}1000")
                        ->getNumberFormat()->setFormatCode('@');
                }
            },
        ];
    }
}
