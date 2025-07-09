<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\Department;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class EmployeesImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Cari departemen berdasarkan nama. Jika tidak ada, buat baru.
        $department = Department::firstOrCreate([
            // 'name' => $row['department_name'] // Jika nama kolom di excel adalah department_name
            'name' => $row['departemen'] // Sesuaikan dengan nama kolom di file Excel Anda
        ]);

        return new Employee([
            'employee_id'     => $row['nomor_karyawan'],
            'name'            => $row['nama_lengkap'],
            'nik_ktp'         => $row['no_ktp'],
            'address'         => $row['alamat'],
            'npwp'            => $row['npwp'],
            'no_kk'           => $row['no_kk'],
            'department_id'   => $department->id,
            'is_active'       => $row['status_aktif'] == 1 ? true : false,
        ]);
    }

    /**
     * Tentukan aturan validasi untuk setiap baris di Excel.
     */
    public function rules(): array
    {
        return [
            'nomor_karyawan' => 'required|string|unique:employees,employee_id',
            'nama_lengkap' => 'required|string',
            'no_ktp' => 'required|string|size:16|unique:employees,nik_ktp',
            // 'department_name' => 'required|string',
            'departemen' => 'required|string', // Sesuaikan dengan nama kolom di file Excel Anda
            'status_aktif' => 'required|boolean',
        ];
    }
}
