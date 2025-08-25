<?php

namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Events\AfterSheet;

class EmployeesExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    ShouldAutoSize,
    WithColumnFormatting,
    WithEvents,
    WithCustomStartCell
{
    public function collection()
    {
        return Employee::with(['department', 'bank'])
            ->where('is_active', 1)
            ->orderBy('employee_id')
            ->get();
    }

    // Geser header agar mulai dari baris 2
    public function startCell(): string
    {
        return 'A2';
    }

    public function headings(): array
    {
        return [
            'ID KARYAWAN',
            'NAMA LENGKAP',
            'NIK',
            'ALAMAT',
            'NPWP',
            'DEPARTEMEN',
            'NO. REKENING',
            'BANK',
        ];
    }

    public function map($employee): array
    {
        $bankName = '';
        if ($employee->bank_id && $employee->bank) {
            $bankName = $employee->bank->name;
        } elseif ($employee->bank_name_manual) {
            $bankName = $employee->bank_name_manual;
        }

        return [
            $employee->employee_id,
            $employee->name,
            '="' . $employee->nik_ktp . '"',
            $employee->address,
            '="' . $employee->npwp . '"',
            optional($employee->department)->name,
            '="' . $employee->no_rek . '"',
            $bankName,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_TEXT,
            'E' => NumberFormat::FORMAT_TEXT,
            'F' => NumberFormat::FORMAT_TEXT,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Tambah judul di baris pertama
                $sheet->mergeCells('A1:H1');
                $sheet->setCellValue('A1', 'Data Karyawan Aktif Per Tanggal : ' . now()->format('d F Y'));

                // Style judul
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // Style header tabel (A2:H2)
                $sheet->getStyle('A2:H2')->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['rgb' => 'D9D9D9'],
                    ],
                ]);

                // Hitung jumlah baris data
                $rowCount = Employee::where('is_active', 1)->count() + 2; // baris header di row 2

                // Style border seluruh tabel
                $sheet->getStyle("A2:H{$rowCount}")->applyFromArray([
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                    ],
                ]);

                // Auto width
                foreach (range('A', 'H') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}
