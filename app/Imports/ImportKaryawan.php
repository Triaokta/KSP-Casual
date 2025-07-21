<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\Department;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ImportKaryawan implements 
    ToModel,
    WithHeadingRow,
    WithValidation,
    SkipsOnFailure,
    SkipsOnError,
    WithBatchInserts,
    WithChunkReading
{
    use SkipsFailures, SkipsErrors;

    public function model(array $row)
    {
        try {
            // Buat atau cari departemen
            $department = Department::updateOrCreate(
                ['name' => $row['departemen']],
                ['directorate_id' => 1]
            );

            Log::info('📥 Import baris:', $row);

            // Return employee untuk disimpan otomatis oleh Laravel Excel
            return new Employee([
                'employee_id'     => $row['nomor_karyawan'],
                'name'            => $row['nama_lengkap'],
                'nik_ktp'         => $row['no_ktp'],
                'address'         => $row['alamat'],
                'npwp'            => $row['npwp'],
                'no_kk'           => $row['no_kk'],
                'department_id'   => $department->id,
                'is_active'       => strtolower(trim($row['status_aktif'])) === 'aktif',
            ]);
        } catch (\Exception $e) {
            Log::error('❌ Gagal simpan employee: ' . json_encode($row));
            Log::error('Error: ' . $e->getMessage());
            return null;
        }
    }

    public function rules(): array
    {
        return [
            'nomor_karyawan' => 'required|string|unique:employees,employee_id',
            'nama_lengkap'   => 'required|string',
            'no_ktp'         => 'required|string|size:16|unique:employees,nik_ktp',
            'departemen'     => 'required|string',
            'status_aktif'   => 'required|in:Aktif,Nonaktif',
        ];
    }

    public function batchSize(): int
    {
        return 100;
    }

    public function chunkSize(): int
    {
        return 100;
    }
}
